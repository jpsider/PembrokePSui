<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewTASK'])) {
		$TARGET_ID=$_GET['TARGET_ID'];
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
		$ARGUMENTS=$_GET['ARGUMENTS'];
		include 'components/database.php';
		$sql = "INSERT INTO TASKS (TARGET_ID,TASK_TYPE_ID,STATUS_ID,RESULT_ID,ARGUMENTS,HIDDEN) VALUES ('$TARGET_ID',$TASK_TYPE_ID,5,6'$ARGUMENTS',0)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=tasks.php");		
	}
	elseif (!empty($_GET['AbortTASK'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TASKS SET STATUS_ID=9,RESULT_ID=5 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=tasks.php");
	}
	elseif (!empty($_GET['HideTASK'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TASKS SET HIDDEN=1 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=tasks.php");		
	}
	elseif (!empty($_GET['RerunTASK'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TASKS SET HIDDEN=1 WHERE ID=$ID";
        $pdo->query($sql);
		$TARGET_ID=$_GET['TARGET_ID'];
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
        $ARGUMENTS=$_GET['ARGUMENTS'];
        // TODO: Determine if the TASK is over the retry limit for the Target_TASK_Type
        // Insert the new TASK 
        $sql2 = "INSERT INTO TASKS (STATUS_ID,RESULT_ID,LOG_FILE,WORKFLOW_MANAGER_ID,TASK_TYPE_ID,TARGET_ID,ARGUMENTS,HIDDEN) VALUES (5,6,'nolog',9999,$TASK_TYPE_ID,$TARGET_ID,'$ARGUMENTS',0)";
        $pdo->exec($sql2);
        // Grab the last ID inserted.
        $Last_ID = $pdo->lastInsertId();
        //Mark the Original as a parent with this new child TASK.
        $sql = "INSERT INTO PARENT_TASKS (PARENT_TASK_ID,CHILD_TASK_ID,isRetry) VALUES ($ID,$Last_ID,1)";
        $pdo->query($sql);
		header("Refresh:0 url=tasks.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Task Status</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Target NAME</th>
				<th>System</th>
				<th>TASK NAME</th>
				<th>TASK Args</th>
                <th>WorkFlow Manager</th>
				<th>Status</th>
				<th>Result</th>
				<th>Action</th>
				<th>LogFile</th>
                <th>Parent TASK</th>
				<th>date_modified</th>
				<th>Hide</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				if (!empty($_GET['PARENT_TASK_ID'])) {
					$ID=$_GET['PARENT_TASK_ID'];
				} elseif (!empty($_GET['CHILD_TASK_ID'])) {
					$ID=$_GET['CHILD_TASK_ID'];					
				} else {
					$ID='%%';
				}
				if (!empty($_GET['TARGET_ID'])){
					$TARGET_ID=$_GET['TARGET_ID'];
				} else {
					$TARGET_ID='%%';
				}
				if (!empty($_GET['TASK_TYPE_ID'])){
					$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
				} else {
					$TASK_TYPE_ID='%%';
				}
				$sql = "select t.ID, "
						    . "t.TARGET_ID, "
						    . "t.TASK_TYPE_ID, "
						    . "t.LOG_FILE, "
						    . "t.STATUS_ID, "
						    . "t.Result_ID, "
						    . "t.WORKFLOW_MANAGER_ID, "
						    . "t.ARGUMENTS, "
						    . "t.HIDDEN, "
						    . "t.date_modified, "
						    . "tg.TARGET_NAME, "
						    . "tg.SYSTEM_ID, "
						    . "tt.TASK_NAME, "
						    . "ss.SYSTEM_NAME, "
						    . "w.HOSTNAME, "
						    . "s.STATUS_NAME, "
						    . "r.RESULT_NAME, "
                            . "s.HTMLCOLOR as STATUSCOLOR, "                                 
                            . "r.HTMLCOLOR "                                 
                        . "from TASKS t "                                   
                        . "join STATUS s on t.STATUS_ID=s.ID "
                        . "join results r on t.Result_ID=r.ID "
                        . "join targets tg on t.TARGET_ID=tg.ID "
                        . "join SYSTEMS ss on tg.SYSTEM_ID=ss.ID "
						. "join TASK_TYPES tt on t.TASK_TYPE_ID=tt.ID "
                        . "join WORKFLOW_MANAGER w on t.WORKFLOW_MANAGER_ID=w.ID "
                        . "where t.HIDDEN=0 and t.ID like '$ID' and t.TARGET_ID like '$TARGET_ID' and t.TASK_TYPE_ID like '$TASK_TYPE_ID'";
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<form action="targets.php" method="get">';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<td><input type="hidden" name="TARGET_ID" value="' . $row['TARGET_ID'] . '"><input type="Submit" class="btn btn-primary" value="'. $row['TARGET_NAME'] . '"></td>';
					echo '</form>';
					echo '<form action="tasks.php" method="get">';
					echo '<td><input type="hidden" name="SYSTEM_ID" value="' . $row['SYSTEM_ID'] . '">'. $row['SYSTEM_NAME'] . '</td>';
					echo '<td><input type="hidden" name="TASK_TYPE_ID" value="' . $row['TASK_TYPE_ID'] . '">'. $row['TASK_NAME'] . '</td>';
					echo '<td><input type="hidden" name="ARGUMENTS" value="' . $row['ARGUMENTS'] . '">'. $row['ARGUMENTS'] . '</td>';
                    echo '<td>'. $row['HOSTNAME'] . '</td>';
                    echo '<td style=background-color:'. $row['STATUSCOLOR'] . '><b>' . $row['STATUS_NAME'] . '</b></td>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>' . $row['RESULT_NAME'] . '</b></td>';
					echo '<input type="hidden" name="TARGET_ID" value="' . $row['TARGET_ID'] . '">';
					echo '<input type="hidden" name="ID" value="' . $row['ID'] . '">';
					if($row['STATUS_ID'] == 9){
						echo '<td><input type="hidden" name="RerunTASK" value="TRUE"><input type="Submit" class="btn btn-warning" value="Rerun"></td>';
						echo '</form>';
					} else {
						echo '<td><input type="hidden" name="EnableTarget" value="TRUE"><input type="Submit" class="btn btn-primary" value="Refresh"></td>';
						echo '</form>';
					}
					echo '<td><form action="singleLogByNAME.php" method="get"><input type="hidden" name="LOG_FILE" value='.$row['LOG_FILE'].'><input type="Submit" class="btn btn-default" value="View Log"></form></td>';                             
                    echo '<td><form action="related_tasks.php" method="get"><input type="hidden" name="Related_TASK_ID" value="' . $row['ID'] . '"><input type="Submit" class="btn btn-info" value="Related TASKs"></form></td>';
					echo '<td>'. $row['date_modified'] . '</td>';
                    echo '<td><form action="tasks.php" method="get"><input type="hidden" name="HideTASK" value="TRUE"><input type="hidden" name="ID" value="' . $row['ID'] . '"><input type="Submit" class="btn btn-info" value="Hide"></form></td>';
					echo '</tr>';
				}
				Database::disconnect();
				?>
			</tbody>
		</table>
	</div><!-- End content-area -->
    <nav class="sidenav">
		<?php
			require_once 'components/Side_Bar.html';
		?>
	</nav>
</div><!-- End content-container (From Header) -->
</body>
<!-- Insert if there is Head PHP -->
<?php
  	}
?>
<!-- End Head PHP closing statement -->
</html>