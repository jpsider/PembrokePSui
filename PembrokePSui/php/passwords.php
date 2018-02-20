<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewPASSWORD'])) {
		$USERNAME=$_GET['USERNAME'];
		$PASSWORD=$_GET['PASSWORD'];
		include 'components/database.php';
		$sql = "INSERT INTO PASSWORDS (USERNAME,PASSWORD) VALUES ('$USERNAME','$PASSWORD')";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=passwords.php");		
	}
	elseif (!empty($_GET['UpdatePASSWORD'])) {
		$ID=$_GET['ID'];
		$USERNAME=$_GET['USERNAME'];
		$PASSWORD=$_GET['PASSWORD'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE PASSWORDS SET USERNAME='$USERNAME',PASSWORD='$PASSWORD' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=passwords.php");
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
				<h3>Passwords</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>USERNAME</th>
                            <th>PASSWORD</th>
							<th>Update</th>
							<th>Targets</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select p.ID, '
									. 'p.USERNAME, '
									. 'p.PASSWORD, '
									. 'p.date_modified '
									. 'from PASSWORDS p '; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="passwords.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="USERNAME" value="'. $row['USERNAME'] . '"></td>';
								echo '<td><input type="text" name="PASSWORD" value="'. $row['PASSWORD'] . '"></td>';
								echo '<td><input type="hidden" name="UpdatePASSWORD" value="TRUE"><input type="Submit" class="btn btn-warning" value="Update"></form></td>';
								echo '<td><form action="targets.php" method="get"><input type="hidden" name="PASSWORD_ID" value="' . $row['ID'] . '"><input type="Submit" class="btn btn-info" value="View Targets"></form></td>';
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
                                <td><b>Add a New PASSWORD</b></td>
                                <td>
									<input type="text" name="USERNAME" value="Enter a NAME">
								</td>
								<td>
									<input type="text" name="PASSWORD" value="Enter a Value">
								</td>
								<td>
									<input type="hidden" name="NewPASSWORD" value="TRUE"><input type="Submit" class="btn btn-success" value="Add PASSWORD"></td>
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