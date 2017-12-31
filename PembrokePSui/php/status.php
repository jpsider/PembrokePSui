<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['UpdateStatus'])) {
		$ID=$_GET['ID'];
		$Status_Name=$_GET['Status_Name'];
		$HtmlColor=$_GET['HtmlColor'];
		$HTML_Description=$_GET['HTML_Description'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update status set Status_Name='$Status_Name',HtmlColor='$HtmlColor',HTML_Description='$HTML_Description' where ID=$ID";
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
				<h3>PembrokePS Available Status</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Status</th>
							<th>HtmlColor</th>
							<th>HTML_Description</th>
							<th>date_modified</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select * from STATUS'; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="status.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">' . $row['ID'] . '</td>';
								echo '<td><input type="text" name="Status_Name" value="' . $row['Status_Name'] . '"></td>';
								echo '<td><input type="text" name="HtmlColor" value="' . $row['HtmlColor'] . '"></td>';
								echo '<td style=background-color:'. $row['HtmlColor'] . '><input type="text" name="HTML_Description" value="' . $row['HTML_Description'] . '"</td>';
								echo '<td>' . $row['date_modified'] . '</td>';
							   	echo '<td><input type="hidden" name="UpdateStatus" value="TRUE"><input type="submit" class="btn btn-warning" value="Update"></td>';
								echo '</form>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
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