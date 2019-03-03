<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
		if (!empty($_GET['ViewManager'])) {
				$ID=$_GET['ID'];
				include 'components/database.php';
				$pdo = Database::connect();
				// Need to search the Queue Manager and Workflow manager
					// for the port ID, then go to that page and filter on that port ID
				$sql = "select ID as MANAGER_ID from QUEUE_MANAGER WHERE QMAN_PORT_ID like $ID or KICKER_PORT_ID like $ID";
				foreach ($pdo->query($sql) as $returndata){
					echo $returndata['MANAGER_ID'];
					if(!empty($returndata['MANAGER_ID'])){
						echo "Made it into the if";
						$MANAGER_ID = $returndata['MANAGER_ID'];
						header("Refresh:0 url=queue_manager.php?MANAGER_ID=$MANAGER_ID");
					}
				}
				$sql = "select ID as MANAGER_ID from WORKFLOW_MANAGER WHERE WKFLW_PORT_ID like $ID or KICKER_PORT_ID like $ID";
				foreach ($pdo->query($sql) as $returndata){
					if(!empty($returndata['MANAGER_ID'])){
						$MANAGER_ID = $returndata['MANAGER_ID'];
						header("Refresh:0 url=workflow_manager.php?MANAGER_ID=$MANAGER_ID");
					}
				}
		}
		elseif (!empty($_GET['NewPort'])){
				$PORTNUMBER=$_GET['PORTNUMBER'];
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = "INSERT INTO ENDPOINT_PORTS (Port,ENDPOINT_ASSIGNED_STATUS,ENDPOINT_STATUS) VALUES ('$PORTNUMBER',13,1)";
				$pdo->query($sql);
				header("Refresh:0 url=endpoint_ports.php");		
		}
		else {

?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
				<h3>Available Endpoint Ports</h3>
					<table id="example" class="table table-compact">
						<thead>
							<tr>
							<th>ID</th>
							<th>Port</th>
							<th>ENDPOINT_ASSIGNED_STATUS</th>
							<th>ENDPOINT_STATUS</th>
							<th>date_modified</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select ep.ID, '
										. 'ep.PORT, '
										. 'ep.ENDPOINT_ASSIGNED_STATUS, '
										. 'ep.ENDPOINT_STATUS, '
										. 'ep.date_modified, '
										. 's.HTMLCOLOR, '
										. 'ss.HTMLCOLOR as EP_STATUS_COLOR, '
										. 's.STATUS_NAME, '
										. 'ss.STATUS_NAME as Ep_Status '
									. 'from ENDPOINT_PORTS ep '
									. 'join STATUS s on ep.ENDPOINT_ASSIGNED_STATUS=s.ID '
									. 'join STATUS ss on ep.ENDPOINT_STATUS=ss.ID ';
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="endpoint_ports.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">' . $row['ID'] . '</td>';
								echo '<td>' . $row['PORT'] . '</td>';
								echo '<td style=background-color:'. $row['HTMLCOLOR'] . '>'. $row['STATUS_NAME'] . '</td>';
								echo '<td style=background-color:'. $row['EP_STATUS_COLOR'] . '><b>'. $row['Ep_Status'] . '</b></td>';
								echo '<td>' . $row['date_modified'] . '</td>';
							  echo '<td><input type="hidden" name="ViewManager" value="TRUE"><input type="Submit" class="btn btn-info" value="View Manager"></td>';
								echo '</form>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
					</table>
					<table  class="table table-compact">
						<tr>
							<form>
								<td><b>Add a New Port</b></td>
								<td>
									<input type="text" name="PORTNUMBER" value="Enter Port Number">
								</td>
								<td>
									<input type="hidden" name="NewPort" value="TRUE"><input type="Submit" class="btn btn-success" value="Add Port"></td>
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