<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['UpdateStatus'])) {
		$ID=$_GET['ID'];
		$STATUS_NAME=$_GET['STATUS_NAME'];
		$HTMLCOLOR=$_GET['HTMLCOLOR'];
		$HTML_Description=$_GET['HTML_Description'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE STATUS SET STATUS_NAME='$STATUS_NAME',HTMLCOLOR='$HTMLCOLOR',HTML_Description='$HTML_Description' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=status.php");
	}
	elseif (!empty($_GET['NewStatus'])){
		$HTMLCOLOR=$_GET['HTMLCOLOR'];
		$STATUS_NAME=$_GET['STATUS_NAME'];
		$HTML_Description=$_GET['HTML_Description'];
		include 'components/database.php';
		$pdo = Database::connect();

		$sql = "INSERT INTO STATUS (STATUS_NAME,HTMLCOLOR,HTML_Description) VALUES ('$STATUS_NAME','$HTMLCOLOR','$HTML_Description')";
		$pdo->query($sql);
		header("Refresh:0 url=status.php");		
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
				<h3>Available Status</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Status</th>
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
							$sql = 'SELECT * FROM STATUS'; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="status.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">' . $row['ID'] . '</td>';
								echo '<td><input type="text" name="STATUS_NAME" value="' . $row['STATUS_NAME'] . '"></td>';
								echo '<td><input type="text" name="HTMLCOLOR" value="' . $row['HTMLCOLOR'] . '"></td>';
								echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><input type="text" name="HTML_Description" value="' . $row['HTML_Description'] . '"</td>';
								echo '<td>' . $row['date_modified'] . '</td>';
							   	echo '<td><input type="hidden" name="UpdateStatus" value="TRUE"><input type="Submit" class="btn btn-warning" value="Update"></td>';
								echo '</form>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
					</table>
					<table  class="table table-striped table-bordered">
						<tr>
							<form>
								<td><b>Add a New Status</b></td>
								<td>
									<input type="text" name="STATUS_NAME" value="Enter STATUS NAME">
								</td>
								<td>
									<input type="text" name="HTMLCOLOR" value="Enter HTML Color">
								</td>
								<td>
									<input type="text" name="HTML_Description" value="Enter HTML Description">
								</td>
								<td>
									<input type="hidden" name="NewStatus" value="TRUE"><input type="Submit" class="btn btn-success" value="Add Status"></td>
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