<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewTask'])) {
		$TARGET_ID=$_GET['TARGET_ID'];
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
		$Arguments=$_GET['Arguments'];
		include 'components/database.php';
		$sql = "INSERT INTO tasks (TARGET_ID,TASK_TYPE_ID,STATUS_ID,RESULT_ID,Arguments,Hidden) VALUES ('$TARGET_ID',$TASK_TYPE_ID,5,6'$Arguments',0)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=tasks.php");		
	}
	elseif (!empty($_GET['AbortTask'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update tasks set STATUS_ID=9,RESULT_ID=5 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=tasks.php");
	}
	elseif (!empty($_GET['HideTask'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update tasks set hidden=1 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=tasks.php");		
	}
	elseif (!empty($_GET['RerunTask'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update tasks set hidden=1 where ID=$ID";
        $pdo->query($sql);
		$TARGET_ID=$_GET['TARGET_ID'];
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
        $Arguments=$_GET['Arguments'];
        // TODO: Determine if the Task is over the retry limit for the Target_Task_Type
        // Insert the new task 
        $sql2 = "INSERT INTO tasks (STATUS_ID,RESULT_ID,Log_File,WORKFLOW_MANAGER_ID,TASK_TYPE_ID,TARGET_ID,Arguments,Hidden) VALUES (5,6,'nolog',9999,$TASK_TYPE_ID,$TARGET_ID,'$Arguments',0)";
        $pdo->exec($sql2);
        // Grab the last ID inserted.
        $Last_ID = $pdo->lastInsertId();
        //Mark the Original as a parent with this new child task.
        $sql = "INSERT INTO PARENT_TASKS (Parent_Task_ID,Child_Task_ID,isRetry) VALUES ($ID,$Last_ID,1)";
        $pdo->query($sql);
		header("Refresh:0 url=tasks.php");			
	}
	else {

?>
<script> 
	$(document).ready(function() {
  		$('#example').dataTable();
	});
</script>
<body>
    <div class="container" style="margin-left:10px">
    	<div class="row">
			<?php
				require_once 'components/Side_Bar.html';
			?>
			<div class="col-sm-9 col-md-10 col-lg-10 main">
				<h3>PembrokePS Tasks</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Target Name</th>
							<th>System</th>
							<th>Task Name</th>
							<th>Task Args</th>
                            <th>WorkFlow Manager</th>
							<th>Status</th>
							<th>Result</th>
							<th>Action</th>
                            <th>Parent Task</th>
							<th>date_modified</th>
							<th>Hide</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							if (!empty($_GET['Target_Type_ID'])) {
								$Target_Type_ID=$_GET['Target_Type_ID'];
							} else {
								$Target_Type_ID='%%';
							}

							$sql = "select t.ID, "
									    . "t.TARGET_ID, "
									    . "t.TASK_TYPE_ID, "
									    . "t.Log_File, "
									    . "t.Status_ID, "
									    . "t.Result_ID, "
									    . "t.WORKFLOW_MANAGER_ID, "
									    . "t.Arguments, "
									    . "t.Hidden, "
									    . "t.date_modified, "
									    . "tg.Target_Name, "
									    . "tg.System_ID, "
									    . "tt.Task_Name, "
									    . "ss.SYSTEM_NAME, "
									    . "w.HostName, "
									    . "s.STATUS_NAME, "
									    . "r.RESULT_NAME, "
                                        . "s.HTMLCOLOR as StatusColor, "                                 
                                        . "r.HTMLCOLOR "                                 
                                    . "from tasks t "                                   
                                    . "join status s on t.Status_ID=s.ID "
                                    . "join results r on t.Result_ID=r.ID "
                                    . "join targets tg on t.TARGET_ID=tg.ID "
                                    . "join SYSTEMS ss on tg.System_ID=ss.ID "
									. "join TASK_TYPES tt on t.TASK_TYPE_ID=tt.ID "
                                    . "join WORKFLOW_MANAGER w on t.WORKFLOW_MANAGER_ID=w.ID "
                                    . "where t.Hidden=0";

							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="tasks.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="hidden" name="TARGET_ID" value="' . $row['TARGET_ID'] . '">'. $row['Target_Name'] . '</td>';
								echo '<td><input type="hidden" name="System_ID" value="' . $row['System_ID'] . '">'. $row['SYSTEM_NAME'] . '</td>';
								echo '<td><input type="hidden" name="TASK_TYPE_ID" value="' . $row['TASK_TYPE_ID'] . '">'. $row['Task_Name'] . '</td>';
								echo '<td><input type="hidden" name="Arguments" value="' . $row['Arguments'] . '">'. $row['Arguments'] . '</td>';
                                echo '<td>'. $row['HostName'] . '</td>';
                                echo '<td style=background-color:'. $row['StatusColor'] . '>' . $row['STATUS_NAME'] . '</td>';
                                echo '<td style=background-color:'. $row['HTMLCOLOR'] . '>' . $row['RESULT_NAME'] . '</td>';
								if($row['Status_ID'] == 9){
									echo '<td><input type="hidden" name="RerunTask" value="TRUE"><input type="submit" class="btn btn-warning" value="Rerun"></td>';
									echo '</form>';
								} else {
									echo '<td><input type="hidden" name="EnableTarget" value="TRUE"><input type="submit" class="btn btn-primary" value="Refresh"></td>';
									echo '</form>';
								}                                
                                echo '<td><form action="related_tasks.php" method="get"><input type="hidden" name="Related_Task_ID" value="' . $row['ID'] . '"><input type="submit" class="btn btn-info" value="Related Tasks"></form></td>';
								echo '<td>'. $row['date_modified'] . '</td>';
                                echo '<td><form action="tasks.php" method="get"><input type="hidden" name="HideTask" value="TRUE"><input type="hidden" name="ID" value="' . $row['ID'] . '"><input type="submit" class="btn btn-info" value="Hide"></form></td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
					</table>					
		   		</div>
			</div>
		</div>
	</div> <!-- /container -->
</body>
<?php
	require_once 'components/footer.php';
?>
<?php
  	}
?>
</html>