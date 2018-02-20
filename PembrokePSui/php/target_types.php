<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewTargetType'])) {
		$TARGETTYPE_NAME=$_GET['TARGETTYPE_NAME'];
		include 'components/database.php';
		$sql = "INSERT INTO TARGET_TYPES (NAME,STATUS_ID) VALUES ('$TARGETTYPE_NAME',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=target_types.php");		
	}
	elseif (!empty($_GET['UpdateTargetType'])) {
		$ID=$_GET['ID'];
		$NAME=$_GET['NAME'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGET_TYPES SET NAME='$NAME' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_types.php");
	}
	elseif (!empty($_GET['DisableTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGET_TYPES SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_types.php");		
	}
	elseif (!empty($_GET['EnableTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGET_TYPES SET STATUS_ID=11 WHERE ID=$ID";
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
				<h3>Target Types</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>NAME</th>
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
									    . 'tt.NAME, '
									    . 'tt.STATUS_ID, '
									    . 'tt.date_modified, '
									    . 's.STATUS_NAME, '
                                        . 's.HTMLCOLOR '                                    
                                    . 'from TARGET_TYPES tt '                                    
                                    . 'join STATUS s on tt.STATUS_ID=s.ID'; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="target_types.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="NAME" value="'. $row['NAME'] . '"></td>';
                                echo '<td><input type="hidden" name="UpdateTargetType" value="TRUE"><input type="Submit" class="btn btn-success" value="Update"</td>';
                                echo '</form>';
								if($row['STATUS_NAME'] == 'Enabled'){
									echo '<form action="target_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableTargetType" value="TRUE"><input type="Submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="target_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableTargetType" value="TRUE"><input type="Submit" class="btn btn-success" value="Enable"></td>';
									echo '</form>';
								}                                
								echo '<td><form action="targets.php" method="get"><input type="hidden" name="TARGET_TYPE_ID" value="' . $row['ID'] . '"><input type="Submit" class="btn btn-info" value="View Targets"></form></td>';
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
									<input type="text" name="TARGETTYPE_NAME" value="Enter a NAME">
								</td>
								<td>
									<input type="hidden" name="NewTargetType" value="TRUE"><input type="Submit" class="btn btn-success" value="Add TargetType"></td>
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