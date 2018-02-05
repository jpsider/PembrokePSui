<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewTaskType'])) {
		$Task_Name=$_GET['Task_Name'];
		$Task_Path=$_GET['Task_Path'];
		$PRIORITY=$_GET['PRIORITY'];
		include 'components/database.php';
		$sql = "INSERT INTO task_types (Task_Name,Task_Path,PRIORITY,STATUS_ID) VALUES ('$Task_Name','$Task_Path','$PRIORITY',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=task_types.php");		
	}
	elseif (!empty($_GET['UpdateTaskType'])) {
		$ID=$_GET['ID'];
		$Task_Name=$_GET['Task_Name'];
        $Task_Path=$_GET['Task_Path'];
		$PRIORITY=$_GET['PRIORITY'];        
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update task_types set Task_Name='$Task_Name',Task_Path='$Task_Path',PRIORITY='$PRIORITY' where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=task_types.php");
	}
	elseif (!empty($_GET['DisableTaskType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update task_types set STATUS_ID=12 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=task_types.php");		
	}
	elseif (!empty($_GET['EnableTaskType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update task_types set STATUS_ID=11 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=task_types.php");			
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
				<h3>PembrokePS Task Types</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
                            <th>Path</th>
                            <th>Priority</th>
                            <th>Update</th>
                            <th>Status</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select tt.ID, '
									    . 'tt.Task_Name, '
									    . 'tt.Task_Path, '
									    . 'tt.Status_ID, '
									    . 'tt.PRIORITY, '
									    . 'tt.date_modified, '
									    . 's.STATUS_NAME, '
                                        . 's.HTMLCOLOR '                                    
                                    . 'from task_types tt '                                    
                                    . 'join status s on tt.Status_ID=s.ID'; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="task_types.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="Task_Name" value="'. $row['Task_Name'] . '"></td>';
								echo '<td><input type="text" name="Task_Path" value="'. $row['Task_Path'] . '"></td>';
								echo '<td><input type="text" name="PRIORITY" value="'. $row['PRIORITY'] . '"></td>';
                                echo '<td><input type="hidden" name="UpdateTaskType" value="TRUE"><input type="submit" class="btn btn-success" value="Update"</td>';
                                echo '</form>';
								if($row['STATUS_NAME'] == 'Enabled'){
									echo '<form action="task_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableTaskType" value="TRUE"><input type="submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="task_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableTaskType" value="TRUE"><input type="submit" class="btn btn-success" value="Enable"></td>';
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
                            <form>
                                <td><b>Add a New Task Type</b></td>
                                <td>
									<input type="text" name="Task_Name" value="Enter a Name">
								</td>
                                <td>
									<input type="text" name="Task_Path" value="Enter a Path">
								</td>
                                <td>
									<input type="text" name="PRIORITY" value="Enter a Priority">
								</td>
								<td>
									<input type="hidden" name="NewTaskType" value="TRUE"><input type="submit" class="btn btn-success" value="Add TaskType"></td>
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