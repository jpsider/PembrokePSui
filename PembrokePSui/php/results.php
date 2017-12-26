<!DOCTYPE html>
<html lang="en">
<?php
require_once 'components/header.php';
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
				<h3>PembrokePS Available Results</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
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
							$sql = 'select * from RESULTS';
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>' . $row['ID'] . '</td>';
								echo '<td>' . $row['Result_Name'] . '</td>';
								echo '<td>' . $row['HtmlColor'] . '</td>';
								echo '<td style=background-color:' . $row['HtmlColor'] . '>' . $row['HTML_Description'] . '</td>';
								echo '<td>' . $row['date_modified'] . '</td>';
							   	echo '<td><a class="btn btn-success" href="results.php?id=' . $row['ID'] . '">Update</a></td>';
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
</html>