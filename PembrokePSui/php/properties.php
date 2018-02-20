<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewProperty'])) {
		$PROP_NAME=$_GET['PROP_NAME'];
		$PROP_VALUE=$_GET['PROP_VALUE'];
		include 'components/database.php';
		$sql = "INSERT INTO PROPERTIES (PROP_NAME,PROP_VALUE,STATUS_ID) VALUES ('$PROP_NAME','$PROP_VALUE',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=properties.php");		
	}
	elseif (!empty($_GET['UpdateProperty'])) {
		$ID=$_GET['ID'];
		$PROP_NAME=$_GET['PROP_NAME'];
		$PROP_VALUE=$_GET['PROP_VALUE'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE PROPERTIES SET PROP_NAME='$PROP_NAME',PROP_VALUE='$PROP_VALUE' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=properties.php");
	}
	elseif (!empty($_GET['EnableProperty'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE PROPERTIES SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=properties.php");		
	}
	elseif (!empty($_GET['DisableProperty'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE PROPERTIES SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=properties.php");			
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
				<h3>Properties</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>NAME</th>
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
									. 'p.PROP_NAME, '
									. 'p.PROP_VALUE, '
									. 'p.date_modified, '
									. 'p.STATUS_ID, '
									. 's.STATUS_NAME, '
									. 's.HTMLCOLOR '
									. 'from PROPERTIES p '
							. 'join STATUS s on p.STATUS_ID=s.ID '; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="properties.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="PROP_NAME" value="'. $row['PROP_NAME'] . '"></td>';
								echo '<td><input type="text" name="PROP_VALUE" value="'. $row['PROP_VALUE'] . '"></td>';
								echo '<td style=background-color:'. $row['HTMLCOLOR'] . '>'. $row['STATUS_NAME'] . '</td>';
								echo '<td><input type="hidden" name="UpdateProperty" value="TRUE"><input type="Submit" class="btn btn-warning" value="Update"></td>';
								echo '</form>';
								if($row['STATUS_NAME'] == 'Enabled'){
									echo '<form action="properties.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableProperty" value="TRUE"><input type="Submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="properties.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableProperty" value="TRUE"><input type="Submit" class="btn btn-success" value="Enable"></td>';
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
                                <td><b>Add a New Property</b></td>
                                <td>
									<input type="text" name="PROP_NAME" value="Enter a NAME">
								</td>
								<td>
									<input type="text" name="PROP_VALUE" value="Enter a Value">
								</td>
								<td>
									<input type="hidden" name="NewProperty" value="TRUE"><input type="Submit" class="btn btn-success" value="Add Property"></td>
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