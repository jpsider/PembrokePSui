<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/header.php';
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
				<h3>PembrokePS Properties</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Value</th>
							<th>Status</th>
							<th>Update</th>
							<th>Action</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select p.ID, '
									. 'p.Prop_Name, '
									. 'p.Prop_Value, '
									. 'p.date_modified, '
									. 'p.STATUS_ID, '
									. 's.STATUS_NAME, '
									. 's.HTMLCOLOR '
									. 'from PROPERTIES p '
							. 'join STATUS s on p.STATUS_ID=s.ID '; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Prop_Name'] . '</td>';
								echo '<td>'. $row['Prop_Value'] . '</td>';
								echo '<td style=background-color:'. $row['HTMLCOLOR'] . '>'. $row['STATUS_NAME'] . '</td>';
							   	echo '<td><a class="btn btn-success" href="properties.php?id='.$row['ID'].'">Update</a></td>';
								echo '<td><a class="btn btn-danger" href="properties.php?id='.$row['ID'].'">Enable/Disable</a></td>';
								echo '<td>'. $row['date_modified'] . '</td>';								   
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
					</table>
		   		</div>
			</div>
		</div>
	</div> <!-- /container -->
</body>
<?php
require_once 'components/footer.php';
?>
</html>