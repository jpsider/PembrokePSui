<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
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
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Passwords</h3>
		<table id="example" class="table table-compact">
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
					echo '<td><input type="hidden" name="UpdatePASSWORD" value="TRUE"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Update"></form></td>';
					echo '<form action="targets.php" method="get"><td><input type="hidden" name="PASSWORD_ID" value="' . $row['ID'] . '"><input type="Submit" class="btn btn-info btn-sm" value="View Targets"></td></form>';
					echo '<td>'. $row['date_modified'] . '</td>';
					echo '</tr>';
				}
				Database::disconnect();
				?>
			</tbody>
		</table>
        <table class="table table-compact">
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
						<input type="hidden" name="NewPASSWORD" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Add PASSWORD"></td>
					</td>
				</form>
			</tr>
		</table> 
	</div><!-- End content-area -->
    <nav class="sidenav">
		<?php
			require_once 'components/Side_Bar.html';
		?>
	</nav>
</div><!-- End content-container (From Header) -->
</body>
<!-- Insert if there is Head PHP -->
<?php
  	}
?>
<!-- End Head PHP closing statement -->
</html>