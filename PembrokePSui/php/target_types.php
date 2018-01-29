<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewTargetType'])) {
		$TargetType_Name=$_GET['TargetType_Name'];
		include 'components/database.php';
		$sql = "INSERT INTO Target_Types (Name,STATUS_ID) VALUES ('$TargetType_Name',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=target_types.php");		
	}
	elseif (!empty($_GET['UpdateTargetType'])) {
		$ID=$_GET['ID'];
		$Name=$_GET['Name'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update target_types set Name='$Name' where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_types.php");
	}
	elseif (!empty($_GET['DisableTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update target_types set STATUS_ID=12 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_types.php");		
	}
	elseif (!empty($_GET['EnableTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update target_types set STATUS_ID=11 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_types.php");			
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
				<h3>PembrokePS Target Types</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
                            <th>Action</th>
                            <th>Status</th>
                            <th>Targets</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select tt.ID, '
									    . 'tt.Name, '
									    . 'tt.Status_ID, '
									    . 'tt.date_modified, '
									    . 's.STATUS_NAME, '
                                        . 's.HTMLCOLOR '                                    
                                    . 'from target_types tt '                                    
                                    . 'join status s on tt.Status_ID=s.ID'; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="target_types.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="Name" value="'. $row['Name'] . '"></td>';
                                echo '<td><input type="hidden" name="UpdateTargetType" value="TRUE"><input type="submit" class="btn btn-success" value="Update"</td>';
                                echo '</form>';
								if($row['STATUS_NAME'] == 'Enabled'){
									echo '<form action="target_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableTargetType" value="TRUE"><input type="submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="target_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableTargetType" value="TRUE"><input type="submit" class="btn btn-success" value="Enable"></td>';
									echo '</form>';
								}                                
								echo '<td><form action="targets.php" method="get"><input type="hidden" name="Target_Type_ID" value="' . $row['ID'] . '"><input type="submit" class="btn btn-info" value="View Targets"></form></td>';
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
                                <td><b>Add a New Target Type</b></td>
                                <td>
									<input type="text" name="TargetType_Name" value="Enter a Name">
								</td>
								<td>
									<input type="hidden" name="NewTargetType" value="TRUE"><input type="submit" class="btn btn-success" value="Add TargetType"></td>
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