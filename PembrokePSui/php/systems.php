<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewSystem'])) {
		$SYSTEM_NAME=$_GET['SYSTEM_NAME'];
		include 'components/database.php';
		$sql = "INSERT INTO SYSTEMS (SYSTEM_NAME,STATUS_ID) VALUES ('$SYSTEM_NAME',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=systems.php");		
	}
	elseif (!empty($_GET['UpdateSystem'])) {
		$ID=$_GET['ID'];
		$SYSTEM_NAME=$_GET['SYSTEM_NAME'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE SYSTEMS SET SYSTEM_NAME='$SYSTEM_NAME' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=systems.php");
	}
	elseif (!empty($_GET['DisableSystem'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE SYSTEMS SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=systems.php");		
	}
	elseif (!empty($_GET['EnableSystem'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE SYSTEMS SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=systems.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Systems</h3>
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
				$sql = 'select ss.ID, '
						    . 'ss.SYSTEM_NAME, '
						    . 'ss.STATUS_ID, '
						    . 'ss.date_modified, '
						    . 's.STATUS_NAME, '
                            . 's.HTMLCOLOR '                                    
                        . 'from SYSTEMS ss '                                    
                        . 'join STATUS s on ss.STATUS_ID=s.ID'; 
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<form action="systems.php" method="get">';
					echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
					echo '<td><input type="text" name="SYSTEM_NAME" value="'. $row['SYSTEM_NAME'] . '"></td>';
                    echo '<td><input type="hidden" name="UpdateSystem" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Update"</td>';
					echo '</form>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="systems.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableSystem" value="TRUE"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="systems.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableSystem" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Enable"></td>';
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
                    <td><b>Add a New System</b></td>
                    <td>
						<input type="text" name="SYSTEM_NAME" value="Enter a NAME">
					</td>
					<td>
						<input type="hidden" name="NewSystem" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Add System"></td>
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