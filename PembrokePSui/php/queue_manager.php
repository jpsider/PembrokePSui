<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NEW_STATUS_ID'])) {
		$NEW_STATUS_ID=$_GET['NEW_STATUS_ID'];
		$ID=$_GET['ID'];
		include 'components/database.php';
		// Update the database to SET the test to aborted
		$sql = "UPDATE QUEUE_MANAGER SET STATUS_ID = $NEW_STATUS_ID WHERE ID=$ID";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=queue_manager.php");
	}
	elseif(!empty($_GET['NewQueueMgr'])){
		include 'components/database.php';
		$Queue_Manager_Type_ID=$_GET['Queue_Manager_Type_ID'];
		$QMAN_PORT_ID=$_GET['QMAN_PORT_ID'];
		$IP_ADDRESS=$_GET['IP_ADDRESS'];
		$HOSTNAME=$_GET['HOSTNAME'];
		$WAIT=$_GET['WAIT'];
		$KICKER_WAIT=$_GET['KICKER_WAIT'];
		$Qman_Description=$_GET['Qman_Description'];
		$sql = "INSERT INTO QUEUE_MANAGER (QUEUE_MANAGER_TYPE_ID,QMAN_PORT_ID,STATUS_ID,KICKER_STATUS_ID,HOSTNAME,IP_ADDRESS,WAIT,KICKER_WAIT,LOG_FILE,QMan_Description) VALUES ('$Queue_Manager_Type_ID','$QMAN_PORT_ID','$KICKER_PORT_ID',1,1,'$HOSTNAME','$IP_ADDRESS','$WAIT','$KICKER_WAIT','NoLog','$Qman_Description')";
		$pdo = Database::connect();
		$pdo->query($sql);
		//Set the endpoint ports to assigned
		$sql = "UPDATE ENDPOINT_PORTS SET ENDPOINT_ASSIGNED_STATUS=7 WHERE ID in ('$QMAN_PORT_ID'";
		$pdo->query($sql);
		//Send the user back to the same page (without get)
		header("Refresh:0 url=queue_manager.php");	
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Queue Manager</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Queue Manager Type</th>
				<th>HOSTNAME</th>
				<th>IP_ADDRESS</th>
				<th>WAIT</th>
				<th>Rest Port</th>
				<th>TABLENAME</th>
				<th>LOG_FILE</th>
				<th>HEARTBEAT</th>
				<th>Status</th>
				<th>Action</th>
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
				if(!empty($_GET['QUEUE_MANAGER_TYPE_ID'])){
					$QUEUE_MANAGER_TYPE_ID = $_GET['QUEUE_MANAGER_TYPE_ID'];
				} else {
					$QUEUE_MANAGER_TYPE_ID = "%%";
				}
				$sql = "select qm.ID, " 
							. "qm.STATUS_ID, "
							. "qm.REGISTRATION_STATUS_ID, "
							. "qm.QUEUE_MANAGER_TYPE_ID, "
							. "qm.HOSTNAME, "
							. "qm.IP_ADDRESS, "
							. "qm.WAIT, "
							. "qm.QMAN_PORT_ID, "
							. "qm.LOG_FILE as Qman_Log, "
							. "qm.HEARTBEAT, "
							. "qm.KICKER_STATUS_ID, "
							. "qm.KICKER_HEARTBEAT, "
							. "qm.KICKER_WAIT, "
							. "qm.QMan_Description, "
							. "qm.date_modified, "
							. "s.HTMLCOLOR as Qman_Color, "
							. "ks.HTMLCOLOR as Kicker_Color, "
							. "rs.HTMLCOLOR as Regis_Color, "
							. "s.STATUS_NAME as Qman_Status, "
							. "ks.STATUS_NAME as Kicker_Status, "
							. "rs.STATUS_NAME as Regis_Status, "
							. "ep.Port as QMAN_PORT, "
							. "qt.NAME as Qman_Type, "
							. "qt.TABLENAME "
						. "from QUEUE_MANAGER qm "
						. "join STATUS s on qm.STATUS_ID=s.ID "
						. "join STATUS ks on qm.KICKER_STATUS_ID=ks.ID "
						. "join STATUS rs on qm.REGISTRATION_STATUS_ID=rs.ID "
						. "join ENDPOINT_PORTS ep on qm.QMAN_PORT_ID=ep.ID "
						. "join QUEUE_MANAGER_TYPE qt on qm.QUEUE_MANAGER_TYPE_ID=qt.ID "
						. "where qm.ID like '$MANAGER_ID' and qm.QUEUE_MANAGER_TYPE_ID like '$QUEUE_MANAGER_TYPE_ID'";
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<td>'. $row['Qman_Type'] . '</td>';
					echo '<td>'. $row['HOSTNAME'] . '</td>';
					echo '<td>'. $row['IP_ADDRESS'] . '</td>';
					echo '<td>'. $row['WAIT'] . '</td>';
					echo '<td>'. $row['QMAN_PORT'] . '</td>';
					echo '<td>'. $row['TABLENAME'] . '</td>';
					echo '<form action="singleLogByNAME.php" method="get"><td><input type="hidden" name="LOG_FILE" value='.$row['Qman_Log'].'><input type="Submit" class="btn btn-info btn-sm" value="View Log"></td></form>';
					echo '<td>'. $row['HEARTBEAT'] . '</td>';
					echo '<td style=background-color:'. $row['Qman_Color'] . '><b>'. $row['Qman_Status'] . '</b></td>';
					if ($row['STATUS_ID'] == 1) {
						echo '<form action="queue_manager.php" method="get"><td><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="NEW_STATUS_ID" value="3"><input type="Submit" class="btn btn-success-outline btn-sm" value="Start Manager"></td></form>';
					} elseif ($row['STATUS_ID'] == 2) {
						echo '<form action="queue_manager.php" method="get"><td><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="NEW_STATUS_ID" value="4"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Stop Manager"></td></form>';
					} elseif ($row['STATUS_ID'] == 3) {
						echo '<form action="queue_manager.php" method="get"><td><input type="hidden" name="ID" value='.$row['ID'].'><input type="hidden" name="NEW_STATUS_ID" value="4"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Stop Manager"></td></form>';
					}else {
						echo '<td><a class="btn btn-info btn-sm" href="queue_manager.php">Refresh</a>';
					}
					echo '<td>'. $row['date_modified'] . '</td>';
					echo '<td style=background-color:'. $row['Kicker_Color'] . '><b>'. $row['Kicker_Status'] . '</b></td>';
					echo '<td>'. $row['KICKER_WAIT'] . '</td>';
					echo '<td>'. $row['KICKER_HEARTBEAT'] . '</td>';
					echo '<td>'. $row['QMan_Description'] . '</td>';
					echo '<td style=background-color:'. $row['Regis_Color'] . '><b>'. $row['Regis_Status'] . '</b></td>';
					echo '</tr>';
				}
				Database::disconnect();
				?>
			</tbody>
		</table>
		<table  class="table table-compact">
			<tr>
				<form>
					<td><b>Add a New Queue Manager</b></td>
					<td>
						<input type="text" name="HOSTNAME" value="Enter a Qman HOSTNAME">
					</td>
					<td>
						<input type="text" name="IP_ADDRESS" value="Enter a Qman IP">
					</td>																
					<td>
						<?php
							echo "<select name='Queue_Manager_Type_ID'>";
							$sql = "SELECT * FROM QUEUE_MANAGER_TYPE";
							foreach ($pdo->query($sql) as $row) {
								echo "<option value=". $row['ID'] .">". $row['NAME'] ."</option>";
							}
							echo "</select>"
						?>
					</td>
					<td>
						<?php
							echo "<select name='QMAN_PORT_ID'>";
							$sql = "SELECT * FROM ENDPOINT_PORTS WHERE ENDPOINT_ASSIGNED_STATUS in (13)";
							foreach ($pdo->query($sql) as $EProw) {
								echo "<option value=". $EProw['ID'] .">". $EProw['PORT'] ."</option>";
							}
							echo "</select>"
						?>
					</td>
					<td>
						<input type="text" name="WAIT" value="Enter a Qman WAIT">
					</td>
					<td>
						<input type="text" name="KICKER_WAIT" value="Enter a Kicker WAIT">
					</td>
					<td>
						<input type="text" name="Qman_Description" value="Enter a description">
					</td>
					<td>
						<input type="hidden" name="NewQueueMgr" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Add Queue Manager"></td>
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