<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['DisableWelcomeMessage'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE PROPERTIES SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=index.php");		
	} 
	else {
?>
	<div class="content-area"><!-- Start content-area -->

		<h3>Welcome to PembrokePS!</h3>
		<?php
		include 'components/database.php';
			$pdo = Database::connect();
			$sql = "SELECT * FROM PROPERTIES WHERE PROP_NAME like 'WelcomeMessage'"; 
			foreach ($pdo->query($sql) as $row) {
				if($row['STATUS_ID'] == 11){
					$ShowWelcomeMessage = 'true';
				} else {
					$ShowWelcomeMessage = 'false';
				}
			}
			Database::disconnect();
		?>
		<div class="row">
			<?php
				if($ShowWelcomeMessage == 'true'){
				echo '<table class="table table-striped table-bordered">';
					echo '<thead>';
					echo '<tr>';
					echo '<th>Welcome Message</th>';
					echo '<th>Hide Message</th>';
					echo '</tr>';
					echo '</thead>';
					echo '<tbody>';
					echo '<tr><td>Yes, post the welcome message!</td>';
					echo '<td><form action="index.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
					echo '<input type="hidden" name="DisableWelcomeMessage" value="TRUE"><input type="Submit" class="btn btn-danger" value="Hide Message">';
					echo '</form></td></tr></br>';
				} else {
				}
			?>
						</tbody>
					</table>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Quick Links</th>
						<th>Other Info</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>Post welcome Message area.</td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div> <!-- End of Row -->
		
	</div><!-- End content-area -->
    <nav class="sidenav">
		<?php
			require_once 'components/Side_Bar.html';
		?>
	</nav>
</div><!-- End content-container (From Header) -->
</body>
<?php
  	}
?>
</html>