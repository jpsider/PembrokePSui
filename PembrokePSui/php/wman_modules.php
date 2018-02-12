<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewWmantask'])) {
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
		$WORKFLOW_MANAGER_TYPE_ID=$_GET['WORKFLOW_MANAGER_TYPE_ID'];
		include 'components/database.php';
		$sql = "INSERT INTO WMAN_TASK_TYPES (WORKFLOW_MANAGER_TYPE_ID,TASK_TYPE_ID,STATUS_ID) VALUES ('$WORKFLOW_MANAGER_TYPE_ID','$TASK_TYPE_ID',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=wman_tasks.php");		
	}
	elseif (!empty($_GET['DisableWmantask'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update WMAN_TASK_TYPES set STATUS_ID=12 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=wman_tasks.php");		
	}
	elseif (!empty($_GET['EnableWmantask'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update WMAN_TASK_TYPES set STATUS_ID=11 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=wman_tasks.php");			
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
				<h3>PembrokePS Workflow Manager Tasks</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Workflow Manager Type</th>
                            <th>Task Name</th>
                            <th>Table Name</th>
                            <th>Status</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select wtt.ID, '
									    . 'wtt.WORKFLOW_MANAGER_TYPE_ID, '
									    . 'wtt.TASK_TYPE_ID, '
									    . 'wtt.date_modified, '
									    . 'wt.Name, '
									    . 'wt.TableName, '
									    . 'tt.Task_Name, '
									    . 's.STATUS_NAME, '
                                        . 's.HTMLCOLOR '                                    
                                    . 'from WMAN_TASK_TYPES wtt '                                    
                                    . 'join status s on wtt.Status_ID=s.ID ' 
                                    . 'join TASK_TYPES tt on wtt.TASK_TYPE_ID=tt.ID '
                                    . 'join WORKFLOW_MANAGER_TYPE wt on wtt.WORKFLOW_MANAGER_TYPE_ID=wt.ID ';
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Name'] . '</td>';
								echo '<td>'. $row['Task_Name'] . '</td>';
								echo '<td>'. $row['TableName'] . '</td>';
								if($row['STATUS_NAME'] == 'Enabled'){
									echo '<form action="wman_tasks.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableWmantask" value="TRUE"><input type="submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="wman_tasks.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableWmantask" value="TRUE"><input type="submit" class="btn btn-success" value="Enable"></td>';
									echo '</form>';
								}                                
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
					</table>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <td><b>Add a New Wman Task</b></td>
                            <td><b>Workflow Mgr Type</b></td>
                            <td><b>Task Type</b></td>
                            <td><b>Submit</b></td>
                        </tr>
                        <tr>
                            <form>
                                <td><b>Select:</b></td>
								<td>
                                    <?php
                                        echo "<select name='WORKFLOW_MANAGER_TYPE_ID'>";
                                        $sql = "select * from WORKFLOW_MANAGER_TYPE";
                                        foreach ($pdo->query($sql) as $row) {
                                            echo "<option value=". $row['ID'] .">". $row['Name'] ."</option>";
                                        }
                                        echo "</select>"
                                    ?>
                                </td>
								<td>
                                    <?php
                                        echo "<select name='TASK_TYPE_ID'>";
                                        $sql = "select * from task_types";
                                        foreach ($pdo->query($sql) as $row) {
                                            echo "<option value=". $row['ID'] .">". $row['Task_Name'] ."</option>";
                                        }
                                        echo "</select>"
                                    ?>
                                </td>
								<td>
									<input type="hidden" name="NewWmantask" value="TRUE"><input type="submit" class="btn btn-success" value="Add Workflow Mgr Task"></td>
								</td>
							</form>
						</tr>
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