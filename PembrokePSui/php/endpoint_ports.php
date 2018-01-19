<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['ViewManager'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		
		// Need to search the Queue Manager and Workflow manager
			// for the port ID, then go to that page and filter on that port ID
		$sql = "select ID as Manager_ID from queue_manager where QMAN_PORT_ID like $ID or KICKER_PORT_ID like $ID";
		foreach ($pdo->query($sql) as $returndata){
			echo $returndata['Manager_ID'];
			if(!empty($returndata['Manager_ID'])){
				echo "Made it into the if";
				$Manager_ID = $returndata['Manager_ID'];
				header("Refresh:0 url=queue_manager.php?Manager_ID=$Manager_ID");
			}
		}
		$sql = "select ID as Manager_ID from workflow_manager where WKFLW_PORT_ID like $ID or KICKER_PORT_ID like $ID";
		foreach ($pdo->query($sql) as $returndata){
			if(!empty($returndata['Manager_ID'])){
				$Manager_ID = $returndata['Manager_ID'];
				header("Refresh:0 url=workflow_manager.php?Manager_ID=$Manager_ID");
			}
		}
	}
	elseif (!empty($_GET['NewPort'])){
		$PortNumber=$_GET['PortNumber'];
		include 'components/database.php';
		$pdo = Database::connect();

		$sql = "insert into endpoint_ports (Port,Endpoint_Assigned_Status,Endpoint_Status) VALUES ('$PortNumber',13,1)";
		$pdo->query($sql);
		header("Refresh:0 url=endpoint_ports.php");		
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
				<h3>PembrokePS Available Endpoint Ports</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Port</th>
							<th>Endpoint_Assigned_Status</th>
							<th>Endpoint_Status</th>
							<th>date_modified</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select ep.ID, '
										. 'ep.Port, '
										. 'ep.Endpoint_Assigned_Status, '
										. 'ep.Endpoint_Status, '
										. 'ep.date_modified, '
										. 's.HtmlColor, '
										. 'ss.HtmlColor as Ep_Status_Color, '
										. 's.Status_Name, '
										. 'ss.Status_Name as Ep_Status '
									. 'from Endpoint_Ports ep '
									. 'join status s on ep.Endpoint_Assigned_Status=s.ID '
									. 'join status ss on ep.Endpoint_Status=ss.ID ';
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="endpoint_ports.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">' . $row['ID'] . '</td>';
								echo '<td>' . $row['Port'] . '</td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '>'. $row['Status_Name'] . '</td>';
								echo '<td style=background-color:'. $row['Ep_Status_Color'] . '>'. $row['Ep_Status'] . '</td>';
								echo '<td>' . $row['date_modified'] . '</td>';
							   	echo '<td><input type="hidden" name="ViewManager" value="TRUE"><input type="submit" class="btn btn-info" value="View Manager"></td>';
								echo '</form>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
					</table>
					<table  class="table table-striped table-bordered">
						<tr>
							<form>
								<td><b>Add a New Port</b></td>
								<td>
									<input type="text" name="PortNumber" value="Enter Port Number">
								</td>
								<td>
									<input type="hidden" name="NewPort" value="TRUE"><input type="submit" class="btn btn-success" value="Add Port"></td>
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