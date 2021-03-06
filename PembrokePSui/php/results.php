<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['UpdateResult'])) {
		$ID=$_GET['ID'];
		$RESULT_NAME=$_GET['RESULT_NAME'];
		$HTMLCOLOR=$_GET['HTMLCOLOR'];
		$HTML_Description=$_GET['HTML_Description'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE RESULTS SET RESULT_NAME='$RESULT_NAME',HTMLCOLOR='$HTMLCOLOR',HTML_Description='$HTML_Description' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=results.php");
	}
	elseif (!empty($_GET['NewResult'])){
		$HTMLCOLOR=$_GET['HTMLCOLOR'];
		$RESULT_NAME=$_GET['RESULT_NAME'];
		$HTML_Description=$_GET['HTML_Description'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "INSERT INTO RESULTS (RESULT_NAME,HTMLCOLOR,HTML_Description) VALUES ('$RESULT_NAME','$HTMLCOLOR','$HTML_Description')";
		$pdo->query($sql);
		header("Refresh:0 url=results.php");		
	}
	else {

?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Available Results</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>NAME</th>
				<th>HTMLCOLOR</th>
				<th>HTML_Description</th>
				<th>date_modified</th>
				<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = 'SELECT * FROM RESULTS';
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<form action="results.php" method="get">';
					echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">' . $row['ID'] . '</td>';
					echo '<td><input type="text" name="RESULT_NAME" value="' . $row['RESULT_NAME'] . '"></td>';
					echo '<td><input type="text" name="HTMLCOLOR" value="' . $row['HTMLCOLOR'] . '"></td>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><input type="text" name="HTML_Description" value="' . $row['HTML_Description'] . '"</td>';
					echo '<td>' . $row['date_modified'] . '</td>';
				  echo '<td><input type="hidden" name="UpdateResult" value="TRUE"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Update"></td>';
					echo '</form>';
					echo '</tr>';
				}
				Database::disconnect();
				?>
			</tbody>
		</table>
		<table  class="table table-compact">
			<tr>
				<form>
					<td><b>Add a New Result</b></td>
					<td>
						<input type="text" name="RESULT_NAME" value="Enter Result NAME">
					</td>
					<td>
						<input type="text" name="HTMLCOLOR" value="Enter HTML Color">
					</td>
					<td>
						<input type="text" name="HTML_Description" value="Enter HTML Description">
					</td>
					<td>
						<input type="hidden" name="NewResult" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Add Result"></td>
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