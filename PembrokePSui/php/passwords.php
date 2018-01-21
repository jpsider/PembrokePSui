<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewPassword'])) {
		$Username=$_GET['Username'];
		$Password=$_GET['Password'];
		include 'components/database.php';
		$sql = "INSERT INTO PASSWORDS (Username,Password) VALUES ('$Username','$Password')";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=passwords.php");		
	}
	elseif (!empty($_GET['UpdatePassword'])) {
		$ID=$_GET['ID'];
		$Username=$_GET['Username'];
		$Password=$_GET['Password'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update PASSWORDS set Username='$Username',Password='$Password' where ID=$ID";
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
				<h3>PembrokePS Passwords</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Username</th>
                            <th>Password</th>
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
									. 'p.Username, '
									. 'p.Password, '
									. 'p.date_modified '
									. 'from PASSWORDS p '; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="passwords.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="Username" value="'. $row['Username'] . '"></td>';
								echo '<td><input type="text" name="Password" value="'. $row['Password'] . '"></td>';
								echo '<td><input type="hidden" name="UpdatePassword" value="TRUE"><input type="submit" class="btn btn-warning" value="Update"></form></td>';
								echo '<td><form action="targets.php" method="get"><input type="hidden" name="Password_ID" value="' . $row['ID'] . '"><input type="submit" class="btn btn-info" value="View Targets"></form></td>';
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
                                <td><b>Add a New Password</b></td>
                                <td>
									<input type="text" name="Username" value="Enter a Name">
								</td>
								<td>
									<input type="text" name="Password" value="Enter a Value">
								</td>
								<td>
									<input type="hidden" name="NewPassword" value="TRUE"><input type="submit" class="btn btn-success" value="Add Password"></td>
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