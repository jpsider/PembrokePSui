<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewTargetType'])) {
		$TARGETTYPE_NAME=$_GET['TARGETTYPE_NAME'];
		include 'components/database.php';
		$sql = "INSERT INTO TARGET_TYPES (NAME,STATUS_ID) VALUES ('$TARGETTYPE_NAME',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=target_types.php");		
	}
	elseif (!empty($_GET['UpdateTargetType'])) {
		$ID=$_GET['ID'];
		$NAME=$_GET['NAME'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGET_TYPES SET NAME='$NAME' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_types.php");
	}
	elseif (!empty($_GET['DisableTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGET_TYPES SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_types.php");		
	}
	elseif (!empty($_GET['EnableTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGET_TYPES SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_types.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Target Types</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>NAME</th>
                    <th>Update</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Targets</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = 'select tt.ID, '
						    . 'tt.NAME, '
						    . 'tt.STATUS_ID, '
						    . 'tt.date_modified, '
						    . 's.STATUS_NAME, '
                                . 's.HTMLCOLOR '                                    
                            . 'from TARGET_TYPES tt '                                    
                            . 'join STATUS s on tt.STATUS_ID=s.ID'; 
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<form action="target_types.php" method="get">';
					echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
					echo '<td><input type="text" name="NAME" value="'. $row['NAME'] . '"></td>';
					echo '<td><input type="hidden" name="UpdateTargetType" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Update"</td>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
                        echo '</form>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="target_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableTargetType" value="TRUE"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="target_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableTargetType" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Enable"></td>';
						echo '</form>';
					}                                
					echo '<form action="targets.php" method="get"><td><input type="hidden" name="TARGET_TYPE_ID" value="' . $row['ID'] . '"><input type="Submit" class="btn btn-info btn-sm" value="View Targets"></td></form>';
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
                        <td><b>Add a New Target Type</b></td>
                        <td>
						<input type="text" name="TARGETTYPE_NAME" value="Enter a NAME">
					</td>
					<td>
						<input type="hidden" name="NewTargetType" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Add TargetType"></td>
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