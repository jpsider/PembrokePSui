<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewModule'])) {
		$NAME=$_GET['NAME'];
		$GALLERY_name=$_GET['GALLERY_NAME'];
		$MODULE_VERSION=$_GET['MODULE_VERSION'];
		include 'components/database.php';
		$sql = "INSERT INTO ADDITIONAL_PS_MODULES (NAME,GALLERY_NAME,MODULE_VERSION,STATUS_ID) VALUES ('$NAME','$GALLERY_NAME','$MODULE_VERSION',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=additional_ps_modules.php");		
	}
	elseif (!empty($_GET['DisableModule'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE ADDITIONAL_PS_MODULES SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=additional_ps_modules.php");		
	}
	elseif (!empty($_GET['EnableModule'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE ADDITIONAL_PS_MODULES SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=additional_ps_modules.php");			
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
				<h3>Additional PS Modules</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>NAME</th>
                            <th>Gallery NAME</th>
                            <th>Version</th>
                            <th>Status</th>
                            <th>Action</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select apm.ID, '
									    . 'apm.NAME, '
									    . 'apm.GALLERY_NAME, '
									    . 'apm.STATUS_ID, '
									    . 'apm.MODULE_VERSION, '
									    . 'apm.date_modified, '
									    . 's.STATUS_NAME, '
                                        . 's.HTMLCOLOR '                                    
                                    . 'from ADDITIONAL_PS_MODULES apm '                                    
                                    . 'join STATUS s on apm.STATUS_ID=s.ID'; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['NAME'] . '</td>';
								echo '<td>'. $row['GALLERY_NAME'] . '</td>';
								echo '<td>'. $row['MODULE_VERSION'] . '</td>';
								echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
								if($row['STATUS_ID'] == 11){
									echo '<form action="additional_ps_modules.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableModule" value="TRUE"><input type="Submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="additional_ps_modules.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableModule" value="TRUE"><input type="Submit" class="btn btn-success" value="Enable"></td>';
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
                                <td><b>Add a New Module</b></td>
                                <td>
									<input type="text" name="NAME" value="Enter a NAME">
								</td>
                                <td>
									<input type="text" name="GALLERY_NAME" value="Enter The PS Gallery NAME">
								</td>
                                <td>
									<input type="text" name="MODULE_VERSION" value="Enter a Version">
								</td>
								<td>
									<input type="hidden" name="NewModule" value="TRUE"><input type="Submit" class="btn btn-success" value="Add Module"></td>
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