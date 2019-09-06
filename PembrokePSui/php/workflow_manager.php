<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['new_STATUS_ID'])) {
		$new_STATUS_ID=$_GET['new_STATUS_ID'];
		$ID=$_GET['ID'];
		include 'components/database.php';
		// Update the database to SET the test to aborted
		$sql = "UPDATE WORKFLOW_MANAGER SET STATUS_ID = $new_STATUS_ID WHERE ID=$ID";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=workflow_manager.php");
	}
	elseif(!empty($_GET['NewWorkflowMgr'])){
		include 'components/database.php';
		$WORKFLOW_MANAGER_TYPE_ID=$_GET['WORKFLOW_MANAGER_TYPE_ID'];
		$WKFLW_PORT_ID=$_GET['WKFLW_PORT_ID'];
		$IP_ADDRESS=$_GET['IP_ADDRESS'];
		$HOSTNAME=$_GET['HOSTNAME'];		
		$WAIT=$_GET['WAIT'];
		$KICKER_WAIT=$_GET['KICKER_WAIT'];
		$MAX_CONCURRENT=$_GET['MAX_CONCURRENT'];
		$Wman_Description=$_GET['Wman_Description'];
		$sql = "INSERT INTO WORKFLOW_MANAGER (WORKFLOW_MANAGER_TYPE_ID,WKFLW_PORT_ID,STATUS_ID,KICKER_STATUS_ID,HOSTNAME,IP_ADDRESSWAIT,KICKER_WAIT,MAX_CONCURRENT_TASKS,LOG_FILE,Wman_Description) VALUES ('$WORKFLOW_MANAGER_TYPE_ID','$WKFLW_PORT_ID','$KICKER_PORT_ID',1,1,'$HOSTNAME','$IP_ADDRESS','$WAIT','$KICKER_WAIT','$MAX_CONCURRENT','NoLog','$Wman_Description')";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Set the endpoint ports to assigned
		$sql = "UPDATE ENDPOINT_PORTS SET ENDPOINT_ASSIGNED_STATUS=7 WHERE ID in ('$WKFLW_PORT_ID')";
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=workflow_manager.php");	
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Workflow Manager</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Workflow Manager Type</th>
				<th>HOSTNAME</th>
				<th>IP_ADDRESS</th>
				<th>WAIT</th>
				<th>Rest Port</th>
				<th>Max Tasks</th>
				<th>TABLENAME</th>
				<th>LOG_FILE</th>
				<th>HEARTBEAT</th>
				<th>Status</th>
				<th>date_modified</th>
				<th>Kicker Status</th>
				<th>Kicker WAIT</th>
				<th>Kicker HEARTBEAT</th>
				<th>Description</th>
				<th>Registration</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				if(!empty($_GET['MANAGER_ID'])){
					$MANAGER_ID = $_GET['MANAGER_ID'];
				} else {
					$MANAGER_ID = "%%";
				}
				if(!empty($_GET['WORKFLOW_MANAGER_TYPE_ID'])){
					$WORKFLOW_MANAGER_TYPE_ID = $_GET['WORKFLOW_MANAGER_TYPE_ID'];
				} else {
					$WORKFLOW_MANAGER_TYPE_ID = "%%";
				}							
				$sql = "select wm.ID, " 
							. "wm.STATUS_ID, "
							. "wm.REGISTRATION_STATUS_ID, "
							. "wm.WORKFLOW_MANAGER_TYPE_ID, "
							. "wm.HOSTNAME, "
							. "wm.IP_ADDRESS, "
							. "wm.WAIT, "
							. "wm.WKFLW_PORT_ID, "
							. "wm.MAX_CONCURRENT_TASKS, "
							. "wm.LOG_FILE as Wman_Log, "
							. "wm.HEARTBEAT, "
							. "wm.KICKER_STATUS_ID, "
							. "wm.KICKER_HEARTBEAT, "
							. "wm.KICKER_WAIT, "
							. "wm.Wman_Description, "
							. "wm.date_modified, "
							. "s.HtmlColor as Wman_Color, "
							. "ks.HtmlColor as Kicker_Color, "
							. "rs.HtmlColor as Regis_Color, "
							. "s.Status_Name as Wman_Status, "
							. "ks.Status_Name as Kicker_Status, "
							. "rs.Status_Name as Regis_Status, "
							. "ep.Port as WMAN_PORT, "
							. "wt.Name as Wman_Type, "
							. "wt.TABLENAME "
						. "from WORKFLOW_MANAGER wm "
						. "join STATUS s on wm.STATUS_ID=s.ID "
						. "join STATUS ks on wm.KICKER_STATUS_ID=ks.ID "
						. "join STATUS rs on wm.REGISTRATION_STATUS_ID=rs.ID "
						. "join ENDPOINT_PORTS ep on wm.WKFLW_PORT_ID=ep.ID "
						. "join WORKFLOW_MANAGER_TYPE wt on wm.WORKFLOW_MANAGER_TYPE_ID=wt.ID "
						. "where wm.ID like '$MANAGER_ID' and wm.WORKFLOW_MANAGER_TYPE_ID like '$WORKFLOW_MANAGER_TYPE_ID'";
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<td>'. $row['Wman_Type'] . '</td>';
					echo '<td>'. $row['HOSTNAME'] . '</td>';
					echo '<td>'. $row['IP_ADDRESS'] . '</td>';
					echo '<td>'. $row['WAIT'] . '</td>';
					echo '<td>'. $row['WMAN_PORT'] . '</td>';
					echo '<td>'. $row['MAX_CONCURRENT_TASKS'] . '</td>';
					echo '<td>'. $row['TABLENAME'] . '</td>';
					echo '<form action="singleLogByName.php" method="get"><td><input type="hidden" name="LOG_FILE" value='.$row['Wman_Log'].'><input type="submit" class="btn btn-info btn-sm" value="View Log"></td></form>';
					echo '<td>'. $row['HEARTBEAT'] . '</td>';
					echo '<td style=background-color:'. $row['Wman_Color'] . '><b>'. $row['Wman_Status'] . '</b></td>';
					if ($row['STATUS_ID'] == 1) {
						echo '<form action="workflow_manager.php" method="get"><td><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="new_STATUS_ID" value="3"><input type="submit" class="btn btn-success-outline btn-sm" value="Start Manager"></td></form>';
					} elseif ($row['STATUS_ID'] == 2) {
						echo '<form action="workflow_manager.php" method="get"><td><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="new_STATUS_ID" value="4"><input type="submit" class="btn btn-warning-outline btn-sm" value="Stop Manager"></td></form>';
					} elseif ($row['STATUS_ID'] == 3) {
						echo '<form action="workflow_manager.php" method="get"><td><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="new_STATUS_ID" value="4"><input type="submit" class="btn btn-warning-outline btn-sm" value="Stop Manager"></td></form>';
					}else {
						echo '<a class="btn btn-info" href="workflow_manager.php">Refresh</a>';
					}
					echo '<td>'. $row['date_modified'] . '</td>';
					echo '<td style=background-color:'. $row['Kicker_Color'] . '><b>'. $row['Kicker_Status'] . '</b></td>';
					echo '<td>'. $row['KICKER_WAIT'] . '</td>';
					echo '<td>'. $row['KICKER_HEARTBEAT'] . '</td>';
					echo '<td>'. $row['Wman_Description'] . '</td>';
					echo '<td style=background-color:'. $row['Regis_Color'] . '><b>'. $row['Regis_Status'] . '</b></td>';
					echo '</tr>';
					//echo '<tr>';
					//echo '</tr>';
				}
				Database::disconnect();
				?>
			</tbody>
		</table>
		<table  class="table table-compact">
			<tr>
				<form>
					<td><b>Add a New Workflow Manager</b></td>
					<td>
						<input type="text" name="HOSTNAME" value="Enter a HOSTNAME">
					</td>
					<td>
						<input type="text" name="IP_ADDRESS" value="Enter an IP">
					</td>																
					<td>
						<?php
							echo "<select name='WORKFLOW_MANAGER_TYPE_ID'>";
							$sql = "SELECT * FROM WORKFLOW_MANAGER_TYPE";
							foreach ($pdo->query($sql) as $row) {
								echo "<option value=". $row['ID'] .">". $row['NAME'] ."</option>";
							}
							echo "</select>"
						?>
					</td>
					<td>
						<?php
							echo "<select name='WKFLW_PORT_ID'>";
							$sql = "SELECT * FROM ENDPOINT_PORTS WHERE ENDPOINT_ASSIGNED_STATUS in (13)";
							foreach ($pdo->query($sql) as $EProw) {
								echo "<option value=". $EProw['ID'] .">". $EProw['PORT'] ."</option>";
							}
							echo "</select>"
						?>
					</td>
					<td>
						<input type="text" name="MAX_CONCURRENT" value="Enter a Max Tasks">
					</td>
					<td>
						<input type="text" name="WAIT" value="Enter a Wman WAIT">
					</td>
					<td>
						<input type="text" name="KICKER_WAIT" value="Enter a Kicker WAIT">
					</td>
					<td>
						<input type="text" name="Wman_Description" value="Enter a description">
					</td>
					<td>
						<input type="hidden" name="NewWorkflowMgr" value="TRUE"><input type="submit" class="btn btn-success-outline btn-sm" value="Add Workflow Manager"></td>
					</td>
				</form>
			</tr>
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