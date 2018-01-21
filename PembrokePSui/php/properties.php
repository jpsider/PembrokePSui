<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewProperty'])) {
		$Prop_Name=$_GET['Prop_Name'];
		$Prop_Value=$_GET['Prop_Value'];
		include 'components/database.php';
		$sql = "INSERT INTO PROPERTIES (Prop_Name,Prop_Value,STATUS_ID) VALUES ('$Prop_Name','$Prop_Value',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=properties.php");		
	}
	elseif (!empty($_GET['UpdateProperty'])) {
		$ID=$_GET['ID'];
		$Prop_Name=$_GET['Prop_Name'];
		$Prop_Value=$_GET['Prop_Value'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update properties set Prop_Name='$Prop_Name',Prop_Value='$Prop_Value' where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=properties.php");
	}
	elseif (!empty($_GET['EnableProperty'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update properties set STATUS_ID=11 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=properties.php");		
	}
	elseif (!empty($_GET['DisableProperty'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update properties set STATUS_ID=12 where ID=$ID";
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
								echo '<form action="properties.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="Prop_Name" value="'. $row['Prop_Name'] . '"></td>';
								echo '<td><input type="text" name="Prop_Value" value="'. $row['Prop_Value'] . '"></td>';
								echo '<td style=background-color:'. $row['HTMLCOLOR'] . '>'. $row['STATUS_NAME'] . '</td>';
								echo '<td><input type="hidden" name="UpdateProperty" value="TRUE"><input type="submit" class="btn btn-warning" value="Update"></td>';
								echo '</form>';
								if($row['STATUS_NAME'] == 'Enabled'){
									echo '<form action="properties.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableProperty" value="TRUE"><input type="submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="properties.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableProperty" value="TRUE"><input type="submit" class="btn btn-success" value="Enable"></td>';
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
									<input type="text" name="Prop_Name" value="Enter a Name">
								</td>
								<td>
									<input type="text" name="Prop_Value" value="Enter a Value">
								</td>
								<td>
									<input type="hidden" name="NewProperty" value="TRUE"><input type="submit" class="btn btn-success" value="Add Property"></td>
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