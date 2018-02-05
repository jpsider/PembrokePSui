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
		$sql = "UPDATE workflow_manager SET Status_ID = $new_status_id where ID=$ID";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=workflow_manager.php");
	}
	elseif(!empty($_GET['NewWorkflowMgr'])){
		include 'components/database.php';
		$Workflow_Manager_Type_ID=$_GET['Workflow_Manager_Type_ID'];
		$WKFLW_PORT_ID=$_GET['WKFLW_PORT_ID'];
		$Kicker_Port_ID=$_GET['Kicker_Port_ID'];
		$IP_Address=$_GET['IP_Address'];
		$HostName=$_GET['HostName'];		
		$Wait=$_GET['Wait'];
		$Kicker_Wait=$_GET['Kicker_Wait'];
		$Max_Concurrent=$_GET['Max_Concurrent'];
		$Wman_Description=$_GET['Wman_Description'];
		$sql = "INSERT INTO workflow_manager (workflow_manager_TYPE_ID,WKFLW_PORT_ID,KICKER_PORT_ID,STATUS_ID,KICKER_STATUS_ID,HostName,IP_AddressWait,Kicker_Wait,Max_Concurrent_Tasks,Log_File,Wman_Description) VALUES ('$Workflow_Manager_Type_ID','$WKFLW_PORT_ID','$Kicker_Port_ID',1,1,'$HostName','$IP_Address','$Wait','$Kicker_Wait','$Max_Concurrent','NoLog','$Wman_Description')";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Set the endpoint ports to assigned
		$sql = "update ENDPOINT_PORTS set ENDPOINT_ASSIGNED_STATUS=7 where ID in ('$WKFLW_PORT_ID','$Kicker_Port_ID')";
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=workflow_manager.php");	
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
				<h3>PembrokePS Workflow Manager</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Workflow Manager Type</th>
							<th>HostName</th>
							<th>IP_Address</th>
							<th>Wait</th>
							<th>Rest Port</th>
							<th>Max Tasks</th>
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
							if(!empty($_GET['WORKFLOW_MANAGER_TYPE_ID'])){
								$WORKFLOW_MANAGER_TYPE_ID = $_GET['WORKFLOW_MANAGER_TYPE_ID'];
							} else {
								$WORKFLOW_MANAGER_TYPE_ID = "%%";
							}							
							$sql = "select wm.ID, " 
										. "wm.Status_ID, "
										. "wm.WORKFLOW_MANAGER_TYPE_ID, "
										. "wm.HostName, "
										. "wm.IP_Address, "
										. "wm.Wait, "
										. "wm.WKFLW_PORT_ID, "
										. "wm.Max_Concurrent_Tasks, "
										. "wm.Log_File as Wman_Log, "
										. "wm.Heartbeat, "
										. "wm.KICKER_PORT_ID, "
										. "wm.KICKER_STATUS_ID, "
										. "wm.KICKER_Heartbeat, "
										. "wm.KICKER_Wait, "
										. "wm.Wman_Description, "
										. "wm.date_modified, "
										. "s.HtmlColor as Wman_Color, "
										. "ks.HtmlColor as Kicker_Color, "
										. "s.Status_Name as Wman_Status, "
										. "ks.Status_Name as Kicker_Status, "
										. "ep.Port as WMAN_PORT, "
										. "epk.Port as Kicker_PORT, "
										. "wt.Name as Wman_Type, "
										. "wt.TableName "
									. "from workflow_manager wm "
									. "join STATUS s on wm.Status_ID=s.ID "
									. "join STATUS ks on wm.KICKER_STATUS_ID=ks.ID "
									. "join ENDPOINT_PORTS ep on wm.WKFLW_PORT_ID=ep.ID "
									. "join ENDPOINT_PORTS epk on wm.KICKER_PORT_ID=epk.ID "
									. "join workflow_manager_TYPE wt on wm.workflow_manager_TYPE_ID=wt.ID "
									. "where wm.ID like '$Manager_ID' and wm.WORKFLOW_MANAGER_TYPE_ID like '$WORKFLOW_MANAGER_TYPE_ID'";

							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Wman_Type'] . '</td>';
								echo '<td>'. $row['HostName'] . '</td>';
								echo '<td>'. $row['IP_Address'] . '</td>';
								echo '<td>'. $row['Wait'] . '</td>';
								echo '<td>'. $row['WMAN_PORT'] . '</td>';
								echo '<td>'. $row['Max_Concurrent_Tasks'] . '</td>';
								echo '<td>'. $row['TableName'] . '</td>';
								echo '<td><form action="singleLogByName.php" method="get"><input type="hidden" name="Log_File" value='.$row['Wman_Log'].'><input type="submit" class="btn btn-info" value="View Log"></form></td>';
								echo '<td>'. $row['Heartbeat'] . '</td>';
								echo '<td style=background-color:'. $row['Wman_Color'] . '>'. $row['Wman_Status'] . '</td>';
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '<td style=background-color:'. $row['Kicker_Color'] . '>'. $row['Kicker_Status'] . '</td>';
								echo '<td>'. $row['Kicker_PORT'] . '</td>';
								echo '<td>'. $row['KICKER_Wait'] . '</td>';
								echo '<td>'. $row['KICKER_Heartbeat'] . '</td>';
								echo '<td>'. $row['Wman_Description'] . '</td>';
								echo '<td width=250>';
								if ($row['Status_ID'] == 1) {
									echo '<form action="workflow_manager.php" method="get"><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="new_status_id" value="3"><input type="submit" class="btn btn-success" value="Start Manager"></form>';
								} elseif ($row['Status_ID'] == 2) {
									echo '<form action="workflow_manager.php" method="get"><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="new_status_id" value="4"><input type="submit" class="btn btn-danger" value="Stop Manager"></form>';
								} elseif ($row['Status_ID'] == 3) {
									echo '<form action="workflow_manager.php" method="get"><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="new_status_id" value="4"><input type="submit" class="btn btn-danger" value="Stop Manager"></form>';
								}else {
									echo '<a class="btn btn-info" href="workflow_manager.php">Refresh</a>';
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
								<td><b>Add a New Workflow Manager</b></td>
								<td>
									<input type="text" name="HostName" value="Enter a HostName">
								</td>
								<td>
									<input type="text" name="IP_Address" value="Enter an IP">
								</td>																
								<td>
									<?php
										echo "<select name='Workflow_Manager_Type_ID'>";
										$sql = "select * from Workflow_MANAGER_TYPE";
										foreach ($pdo->query($sql) as $row) {
											echo "<option value=". $row['ID'] .">". $row['Name'] ."</option>";
										}
										echo "</select>"
									?>
								</td>
								<td>
									<?php
										echo "<select name='WKFLW_PORT_ID'>";
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
									<input type="text" name="Max_Concurrent" value="Enter a Max Tasks">
								</td>
								<td>
									<input type="text" name="Wait" value="Enter a Wman Wait">
								</td>
								<td>
									<input type="text" name="Kicker_Wait" value="Enter a Kicker Wait">
								</td>
								<td>
									<input type="text" name="Wman_Description" value="Enter a description">
								</td>
								<td>
									<input type="hidden" name="NewWorkflowMgr" value="TRUE"><input type="submit" class="btn btn-success" value="Add Workflow Manager"></td>
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