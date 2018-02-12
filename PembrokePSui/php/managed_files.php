<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewManagedFile'])) {
		$FILE_NAME=$_GET['FILE_NAME'];
		$FILE_PATH=$_GET['FILE_PATH'];
		$REBOOT_REQUIRED=$_GET['REBOOT_REQUIRED'];
		include 'components/database.php';
		$sql = "INSERT INTO managed_files (FILE_NAME,FILE_PATH,FILE_HASH,REBOOT_REQUIRED,STATUS_ID) VALUES ('$FILE_NAME','$FILE_PATH','NewFile','$REBOOT_REQUIRED',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=managed_files.php");		
	}
	elseif (!empty($_GET['UpdateManagedFile'])) {
		$ID=$_GET['ID'];
		$FILE_NAME=$_GET['FILE_NAME'];
		$FILE_PATH=$_GET['FILE_PATH'];
		$REBOOT_REQUIRED=$_GET['REBOOT_REQUIRED'];
		include 'components/database.php';
		$pdo = Database::connect();
        $sql = "update managed_files set FILE_NAME='$FILE_NAME',FILE_PATH='$FILE_PATH\',REBOOT_REQUIRED='$REBOOT_REQUIRED' where ID=$ID";
        echo $sql;
		$pdo->query($sql);
		header("Refresh:10 url=managed_files.php");
	}
	elseif (!empty($_GET['DisableManagedFile'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update managed_files set STATUS_ID=12 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=managed_files.php");		
	}
	elseif (!empty($_GET['EnableManagedFile'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update managed_files set STATUS_ID=11 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=managed_files.php");			
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
				<h3>PembrokePS Managed Files</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>File Name</th>
                            <th>File Path</th>
                            <th>File Hash</th>
                            <th>Reboot Required</th>
                            <th>Status</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select mf.ID, '
									    . 'mf.FILE_NAME, '
									    . 'mf.FILE_PATH, '
									    . 'mf.FILE_HASH, '
									    . 'mf.REBOOT_REQUIRED, '
									    . 'mf.Status_ID, '
									    . 'mf.date_modified, '
									    . 's.STATUS_NAME, '
                                        . 's.HTMLCOLOR '                                    
                                    . 'from managed_files mf '                                    
                                    . 'join status s on mf.Status_ID=s.ID'; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="managed_files.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="FILE_NAME" value="'. $row['FILE_NAME'] . '"></td>';
								echo '<td><input type="text" name="FILE_PATH" value="'. $row['FILE_PATH'] . '"></td>';
								echo '<td>'. $row['FILE_HASH'] . '</td>';
								echo '<td><input type="text" name="REBOOT_REQUIRED" value="'. $row['REBOOT_REQUIRED'] . '"></td>';
                                echo '<td><input type="hidden" name="UpdateManagedFile" value="TRUE"><input type="submit" class="btn btn-success" value="Update"</td>';
                                echo '</form>';
								if($row['STATUS_NAME'] == 'Enabled'){
									echo '<form action="managed_files.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableManagedFile" value="TRUE"><input type="submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="managed_files.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableManagedFile" value="TRUE"><input type="submit" class="btn btn-success" value="Enable"></td>';
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
                                <td><b>Add a New Managed File</b></td>
                                <td>
									<input type="text" name="FILE_NAME" value="Enter a Name">
								</td>
                                <td>
									<input type="text" name="FILE_PATH" value="Enter a Path">
								</td>
                                <td>
									<input type="text" name="REBOOT_REQUIRED" value="Enter a 0 or 1">
								</td>
								<td>
									<input type="hidden" name="NewManagedFile" value="TRUE"><input type="submit" class="btn btn-success" value="Add Managed File"></td>
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