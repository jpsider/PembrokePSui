<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewSubtask'])) {
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
		$Pass_SubTask_ID=$_GET['Pass_SubTask_ID'];
		$Fail_SubTask_ID=$_GET['Fail_SubTask_ID'];
		include 'components/database.php';
		$sql = "INSERT INTO subtask_generator (TASK_TYPE_ID,Pass_SubTask_ID,Fail_SubTask_ID,STATUS_ID) VALUES ('$TASK_TYPE_ID','$Pass_SubTask_ID','$Fail_SubTask_ID',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=subtask_generator.php");		
	}
	elseif (!empty($_GET['DisableSubtask'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update subtask_generator set STATUS_ID=12 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=subtask_generator.php");		
	}
	elseif (!empty($_GET['EnableSubtask'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update subtask_generator set STATUS_ID=11 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=subtask_generator.php");			
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
				<h3>PembrokePS Subtask Generator</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Primary Task</th>
                            <th>Pass Task</th>
                            <th>Fail Task</th>
                            <th>Status</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select sg.ID, '
									    . 'sg.TASK_TYPE_ID, '
									    . 'sg.Pass_SubTask_ID, '
									    . 'sg.Fail_SubTask_ID, '
									    . 'sg.date_modified, '
									    . 'tt.Task_Name as Primary_Task, '
									    . 'pt.Task_Name as Pass_Task, '
									    . 'ft.Task_Name as Fail_Task, '
									    . 's.STATUS_NAME, '
                                        . 's.HTMLCOLOR '                                    
                                    . 'from SUBTASK_GENERATOR sg '                                    
                                    . 'join status s on sg.Status_ID=s.ID ' 
                                    . 'join TASK_TYPES tt on sg.TASK_TYPE_ID=tt.ID '
                                    . 'join TASK_TYPES pt on sg.Pass_SubTask_ID=pt.ID ' 
                                    . 'join TASK_TYPES ft on sg.Fail_SubTask_ID=ft.ID ';
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Primary_Task'] . '</td>';
								echo '<td>'. $row['Pass_Task'] . '</td>';
								echo '<td>'. $row['Fail_Task'] . '</td>';
								if($row['STATUS_NAME'] == 'Enabled'){
									echo '<form action="subtask_generator.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableSubtask" value="TRUE"><input type="submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="subtask_generator.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableSubtask" value="TRUE"><input type="submit" class="btn btn-success" value="Enable"></td>';
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
                            <td><b>Add a New Subtask</b></td>
                            <td><b>Primary Task</b></td>
                            <td><b>Passed Subtask</b></td>
                            <td><b>Failed Subtask</b></td>
                            <td><b>Submit</b></td>
                        </tr>
                        <tr>
                            <form>
                                <td><b>Select:</b></td>
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
                                    <?php
                                        echo "<select name='Pass_SubTask_ID'>";
                                        $sql = "select * from task_types";
                                        foreach ($pdo->query($sql) as $row) {
                                            echo "<option value=". $row['ID'] .">". $row['Task_Name'] ."</option>";
                                        }
                                        echo "</select>"
                                    ?>
                                </td>
								<td>
                                    <?php
                                        echo "<select name='Fail_SubTask_ID'>";
                                        $sql = "select * from task_types";
                                        foreach ($pdo->query($sql) as $row) {
                                            echo "<option value=". $row['ID'] .">". $row['Task_Name'] ."</option>";
                                        }
                                        echo "</select>"
                                    ?>
                                </td>
								<td>
									<input type="hidden" name="NewSubtask" value="TRUE"><input type="submit" class="btn btn-success" value="Add SubTask"></td>
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