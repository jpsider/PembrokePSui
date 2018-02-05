<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewTargetTaskType'])) {
		$TARGET_TYPES_ID=$_GET['TARGET_TYPES_ID'];
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
		$MAX_Retries=$_GET['MAX_Retries'];
		include 'components/database.php';
		$sql = "INSERT INTO TARGET_TASKS_TYPES (TARGET_TYPES_ID,TASK_TYPE_ID,MAX_Retries,STATUS_ID) VALUES ('$TARGET_TYPES_ID','$TASK_TYPE_ID','$MAX_Retries',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=target_task_types.php");		
	}
	elseif (!empty($_GET['DisableParentTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update TARGET_TASKS_TYPES set STATUS_ID=12 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_task_types.php");		
	}
	elseif (!empty($_GET['EnableParentTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update TARGET_TASKS_TYPES set STATUS_ID=11 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_task_types.php");			
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
				<h3>PembrokePS Target Task Type</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Parent Target Type</th>
                            <th>Child Target Type</th>
                            <th>Status</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select ttt.ID, '
									    . 'ttt.TARGET_TYPES_ID, '
									    . 'ttt.TASK_TYPE_ID, '
									    . 'ttt.STATUS_ID, '
									    . 'ttt.MAX_Retries, '
									    . 'ttt.date_modified, '
									    . 'tt.Name, '
									    . 'tts.Task_Name, '
									    . 's.STATUS_NAME, '
                                        . 's.HTMLCOLOR '                                    
                                    . 'from TARGET_TASKS_TYPES ttt '                                    
                                    . 'join status s on ttt.Status_ID=s.ID ' 
                                    . 'join TARGET_TYPES tt on ttt.TARGET_TYPES_ID=tt.ID '
                                    . 'join TASK_TYPES tts on ttt.TASK_TYPE_ID=tts.ID ';
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Name'] . '</td>';
								echo '<td>'. $row['Task_Name'] . '</td>';
								echo '<td>'. $row['MAX_Retries'] . '</td>';
								if($row['STATUS_NAME'] == 'Enabled'){
									echo '<form action="target_task_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableParentTargetType" value="TRUE"><input type="submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="target_task_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableParentTargetType" value="TRUE"><input type="submit" class="btn btn-success" value="Enable"></td>';
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
                            <td><b>Add a Target Task Combo</b></td>
                            <td><b>Target Type</b></td>
                            <td><b>Task Type</b></td>
                            <td><b>Max Retries</b></td>
                            <td><b>Submit</b></td>
                        </tr>
                        <tr>
                            <form>
                                <td><b>Select:</b></td>
								<td>
                                    <?php
                                        echo "<select name='TARGET_TYPES_ID'>";
                                        $sql = "select * from TARGET_TYPES";
                                        foreach ($pdo->query($sql) as $row) {
                                            echo "<option value=". $row['ID'] .">". $row['Name'] ."</option>";
                                        }
                                        echo "</select>"
                                    ?>
                                </td>
								<td>
                                    <?php
                                        echo "<select name='TASK_TYPE_ID'>";
                                        $sql = "select * from TASK_TYPES";
                                        foreach ($pdo->query($sql) as $row) {
                                            echo "<option value=". $row['ID'] .">". $row['Task_Name'] ."</option>";
                                        }
                                        echo "</select>"
                                    ?>
                                </td>
                                <td>
									<input type="text" name="MAX_Retries" value="Enter a Max Retry">
								</td>
								<td>
									<input type="hidden" name="NewTargetTaskType" value="TRUE"><input type="submit" class="btn btn-success" value="Add Target Task Type Combo"></td>
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