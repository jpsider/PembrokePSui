<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['new_status_id'])) {
		$new_status_id=$_GET['new_status_id'];
		$ID=$_GET['ID'];
		include 'components/database.php';
		// Update the database to set the test to aborted
		$sql = "UPDATE queue_manager SET Status_ID = $new_status_id where ID=$ID";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=queue_manager.php");
	}
	elseif(!empty($_GET['NewQueueMgr'])){
		include 'components/database.php';
		$Queue_Manager_Type_ID=$_GET['Queue_Manager_Type_ID'];
		$Qman_Port_ID=$_GET['Qman_Port_ID'];
		$Kicker_Port_ID=$_GET['Kicker_Port_ID'];
		$Wait=$_GET['Wait'];
		$Kicker_Wait=$_GET['Kicker_Wait'];
		$Qman_Description=$_GET['Qman_Description'];
		$sql = "INSERT INTO QUEUE_MANAGER (QUEUE_MANAGER_TYPE_ID,QMAN_PORT_ID,KICKER_PORT_ID,STATUS_ID,KICKER_STATUS_ID,Wait,Kicker_Wait,Log_File,QMan_Description) VALUES ('$Queue_Manager_Type_ID','$Qman_Port_ID','$Kicker_Port_ID',1,1,'$Wait','$Kicker_Wait','NoLog','$Qman_Description')";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Set the endpoint ports to assigned
		$sql = "update ENDPOINT_PORTS set ENDPOINT_ASSIGNED_STATUS=7 where ID in ('$Qman_Port_ID','$Kicker_Port_ID')";
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=queue_manager.php");	
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
				<h3>PembrokePS Queue Manager</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Queue Manager Type</th>
							<th>Wait</th>
							<th>Rest Port</th>
							<th>TableName</th>
							<th>Log_File</th>
							<th>HeartBeat</th>
							<th>Status</th>
							<th>date_modified</th>
							<th>Kicker Status</th>
							<th>Kicker Port</th>
							<th>Kicker Wait</th>
							<th>Kicker Heartbeat</th>
							<th>Description</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							if(!empty($_GET['Manager_ID'])){
								$Manager_ID = $_GET['Manager_ID'];
							} else {
								$Manager_ID = "%%";
							}
							if(!empty($_GET['QUEUE_MANAGER_TYPE_ID'])){
								$QUEUE_MANAGER_TYPE_ID = $_GET['QUEUE_MANAGER_TYPE_ID'];
							} else {
								$QUEUE_MANAGER_TYPE_ID = "%%";
							}
							$sql = "select qm.ID, " 
										. "qm.Status_ID, "
										. "qm.QUEUE_MANAGER_TYPE_ID, "
										. "qm.Wait, "
										. "qm.QMAN_PORT_ID, "
										. "qm.Log_File as Qman_Log, "
										. "qm.Heartbeat, "
										. "qm.KICKER_PORT_ID, "
										. "qm.KICKER_STATUS_ID, "
										. "qm.KICKER_Heartbeat, "
										. "qm.KICKER_Wait, "
										. "qm.QMan_Description, "
										. "qm.date_modified, "
										. "s.HtmlColor as Qman_Color, "
										. "ks.HtmlColor as Kicker_Color, "
										. "s.Status_Name as Qman_Status, "
										. "ks.Status_Name as Kicker_Status, "
										. "ep.Port as QMAN_PORT, "
										. "epk.Port as Kicker_PORT, "
										. "qt.Name as Qman_Type, "
										. "qt.TableName "
									. "from QUEUE_MANAGER qm "
									. "join STATUS s on qm.Status_ID=s.ID "
									. "join STATUS ks on qm.KICKER_STATUS_ID=ks.ID "
									. "join ENDPOINT_PORTS ep on qm.QMAN_PORT_ID=ep.ID "
									. "join ENDPOINT_PORTS epk on qm.KICKER_PORT_ID=epk.ID "
									. "join QUEUE_MANAGER_TYPE qt on qm.QUEUE_MANAGER_TYPE_ID=qt.ID "
									. "where qm.ID like '$Manager_ID' and qm.QUEUE_MANAGER_TYPE_ID like '$QUEUE_MANAGER_TYPE_ID'";

							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Qman_Type'] . '</td>';
								echo '<td>'. $row['Wait'] . '</td>';
								echo '<td>'. $row['QMAN_PORT'] . '</td>';
								echo '<td>'. $row['TableName'] . '</td>';
								echo '<td><form action="singleLogByName.php" method="get"><input type="hidden" name="Log_File" value='.$row['Qman_Log'].'><input type="submit" class="btn btn-info" value="View Log"></form></td>';
								echo '<td>'. $row['Heartbeat'] . '</td>';
								echo '<td style=background-color:'. $row['Qman_Color'] . '>'. $row['Qman_Status'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '<td style=background-color:'. $row['Kicker_Color'] . '>'. $row['Kicker_Status'] . '</td>';
								echo '<td>'. $row['Kicker_PORT'] . '</td>';
								echo '<td>'. $row['KICKER_Wait'] . '</td>';
								echo '<td>'. $row['KICKER_Heartbeat'] . '</td>';
								echo '<td>'. $row['QMan_Description'] . '</td>';
								echo '<td width=250>';
								if ($row['Status_ID'] == 1) {
									echo '<form action="queue_manager.php" method="get"><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="new_status_id" value="3"><input type="submit" class="btn btn-success" value="Start Manager"></form>';
								} elseif ($row['Status_ID'] == 2) {
									echo '<form action="queue_manager.php" method="get"><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="new_status_id" value="4"><input type="submit" class="btn btn-danger" value="Stop Manager"></form>';
								} elseif ($row['Status_ID'] == 3) {
									echo '<form action="queue_manager.php" method="get"><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="new_status_id" value="4"><input type="submit" class="btn btn-danger" value="Stop Manager"></form>';
								}else {
									echo '<a class="btn btn-info" href="queue_manager.php">Refresh</a>';
								}
								echo '</td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
					</table>
					<table  class="table table-striped table-bordered">
						<tr>
							<form>
								<td><b>Add a New Queue Manager</b></td>
								<td>
									<?php
										echo "<select name='Queue_Manager_Type_ID'>";
										$sql = "select * from QUEUE_MANAGER_TYPE";
										foreach ($pdo->query($sql) as $row) {
											echo "<option value=". $row['ID'] .">". $row['Name'] ."</option>";
										}
										echo "</select>"
									?>
								</td>
								<td>
									<?php
										echo "<select name='Qman_Port_ID'>";
										$sql = "select * from ENDPOINT_PORTS where ENDPOINT_ASSIGNED_STATUS in (13)";
										foreach ($pdo->query($sql) as $EProw) {
											echo "<option value=". $EProw['ID'] .">". $EProw['PORT'] ."</option>";
										}
										echo "</select>"
									?>
								</td>
								<td>
									<?php
										echo "<select name='Kicker_Port_ID'>";
										$sql = "select * from ENDPOINT_PORTS where ENDPOINT_ASSIGNED_STATUS in (13)";
										foreach ($pdo->query($sql) as $KProw) {
											echo "<option value=". $KProw['ID'] .">". $KProw['PORT'] ."</option>";
										}
										echo "</select>"
									?>								
								</td>
								<td>
									<input type="text" name="Wait" value="Enter a Qman Wait">
								</td>
								<td>
									<input type="text" name="Kicker_Wait" value="Enter a Kicker Wait">
								</td>
								<td>
									<input type="text" name="Qman_Description" value="Enter a description">
								</td>
								<td>
									<input type="hidden" name="NewQueueMgr" value="TRUE"><input type="submit" class="btn btn-success" value="Add Queue Manager"></td>
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