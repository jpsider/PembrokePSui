<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewTarget'])) {
		$TARGET_NAME=$_GET['TARGET_NAME'];
		$TARGET_TYPE_ID=$_GET['TARGET_TYPE_ID'];
		$IP_ADDRESS=$_GET['IP_ADDRESS'];
		$PASSWORD_ID=$_GET['PASSWORD_ID'];
		$SYSTEM_ID=$_GET['SYSTEM_ID'];
		$Target_Description=$_GET['Target_Description'];
		include 'components/database.php';
		$sql = "INSERT INTO TARGETS (TARGET_NAME,TARGET_TYPE_ID,IP_ADDRESS,STATUS_ID,PASSWORD_ID,SYSTEM_ID,Target_Description) VALUES ('$TARGET_NAME',$TARGET_TYPE_ID,'$IP_ADDRESS',11,$PASSWORD_ID,$SYSTEM_ID,'$Target_Description')";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=targets.php");		
	}
	elseif (!empty($_GET['UpdateTarget'])) {
		$ID=$_GET['ID'];
		$TARGET_NAME=$_GET['TARGET_NAME'];
		$TARGET_TYPE_ID=$_GET['TARGET_TYPE_ID'];
		$IP_ADDRESS=$_GET['IP_ADDRESS'];
		$PASSWORD_ID=$_GET['PASSWORD_ID'];
		$SYSTEM_ID=$_GET['SYSTEM_ID'];
		$Target_Description=$_GET['Target_Description'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGETS SET TARGET_NAME='$TARGET_NAME',TARGET_TYPE_ID='$TARGET_TYPE_ID',IP_ADDRESS='$IP_ADDRESS',PASSWORD_ID='$PASSWORD_ID',SYSTEM_ID='$SYSTEM_ID',Target_Description='$Target_Description' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=targets.php");
	}
	elseif (!empty($_GET['DisableTarget'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGETS SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=targets.php");		
	}
	elseif (!empty($_GET['EnableTarget'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGETS SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=targets.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Targets</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Name</th>
				<th>IP Address</th>
				<th>Target Type</th>
                <th>PASSWORD Info</th>
                <th>Target_Description</th>
                <th>System</th>
				<th>Update</th>
				<th>Status</th>
				<th>Tasks</th>
				<th>New Task</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				if (!empty($_GET['TARGET_TYPE_ID'])) {
					$TARGET_TYPE_ID=$_GET['TARGET_TYPE_ID'];
				} else {
					$TARGET_TYPE_ID='%%';
				}
				if (!empty($_GET['TARGET_ID'])) {
					$TARGET_ID=$_GET['TARGET_ID'];
				} else {
					$TARGET_ID='%%';
				}
				$sql = "select t.ID, "
						    . "t.TARGET_NAME, "
						    . "t.TARGET_TYPE_ID, "
						    . "t.IP_ADDRESS, "
						    . "t.STATUS_ID, "
						    . "t.PASSWORD_ID, "
						    . "t.SYSTEM_ID, "
						    . "t.Target_Description, "
						    . "t.date_modified, "
						    . "tt.NAME as Target_Type, "
						    . "ss.SYSTEM_NAME, "
						    . "p.USERNAME, "
						    . "s.STATUS_NAME, "
                            . "s.HTMLCOLOR "                                 
                        . "from TARGETS t "                                   
                        . "join STATUS s on t.STATUS_ID=s.ID "
                        . "join PASSWORDS p on t.PASSWORD_ID=p.ID "
                        . "join SYSTEMS ss on t.SYSTEM_ID=ss.ID "
						. "join TARGET_TYPES tt on t.TARGET_TYPE_ID=tt.ID "
						. "where t.TARGET_TYPE_ID like '$TARGET_TYPE_ID' and t.ID like '$TARGET_ID'";
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<form action="tasks.php" method="get">';
					echo '<td><input type="hidden" name="TARGET_ID" value="' . $row['ID'] . '"><input type="Submit" class="btn btn-info" value="'. $row['ID'] . '"</td></form>';
					echo '<form action="targets.php" method="get">';
					echo '<td><input type="text" name="TARGET_NAME" value="'. $row['TARGET_NAME'] . '"></td>';
					echo '<td><input type="text" name="IP_ADDRESS" value="'. $row['IP_ADDRESS'] . '"></td>';
					echo '<td><input type="hidden" name="TARGET_TYPE_ID" value="' . $row['TARGET_TYPE_ID'] . '">'. $row['Target_Type'] . '</td>';
					echo '<td><input type="hidden" name="PASSWORD_ID" value="' . $row['PASSWORD_ID'] . '">'. $row['USERNAME'] . '</td>';
					echo '<td><input type="text" name="Target_Description" value="'. $row['Target_Description'] . '"></td>';
					echo '<td><input type="hidden" name="SYSTEM_ID" value="' . $row['SYSTEM_ID'] . '">'. $row['SYSTEM_NAME'] . '</td>';
                    echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '"><input type="hidden" name="UpdateTarget" value="TRUE"><input type="Submit" class="btn btn-success" value="Update"</td>';
                    echo '</form>';
					if($row['STATUS_NAME'] == 'Enabled'){
						echo '<form action="targets.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableTarget" value="TRUE"><input type="Submit" class="btn btn-danger" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="targets.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableTarget" value="TRUE"><input type="Submit" class="btn btn-success" value="Enable"></td>';
						echo '</form>';
					}                                
					echo '<form action="tasks.php" method="get"><input type="hidden" name="Target_ID" value="' . $row['ID'] . '">';
					echo '<td><input type="Submit" class="btn btn-info" value="Tasks"></td>';
					echo '</form>';
					echo '<form action="create_new_task.php" method="get"><input type="hidden" name="TARGET_NAME" value="' . $row['TARGET_NAME'] . '">';
					echo '<td><input type="hidden" name="TARGET_TYPE_ID" value="' . $row['TARGET_TYPE_ID'] . '"><input type="hidden" name="TARGET_ID" value="' . $row['ID'] . '"><input type="Submit" class="btn btn-warning" value="New Task"></td>';
					echo '</form>';
					echo '<td>'. $row['date_modified'] . '</td>';
					echo '</tr>';
				}
				Database::disconnect();
				?>
			</tbody>
		</table>
        <table class="table table-compact">
				<tr>
					<th></th>
					<th>Name:</th>
					<th>Target Type:</th>
					<th>IP Address</th>
					<th>User/PWD</th>
					<th>System</th>
					<th>Description</th>
					<th>Submit</th>
				</tr>
            <tr>
                <form>
                    <td><b>Enter Information:</b></td>
                    <td>
						<input type="text" name="TARGET_NAME" value="Enter a Name">
					</td>
                    <td>
						<?php
							echo "<select name='TARGET_TYPE_ID'>";
							$sql = "SELECT * FROM TARGET_TYPES";
							foreach ($pdo->query($sql) as $row) {
								echo "<option value=". $row['ID'] .">". $row['NAME'] ."</option>";
							}
							echo "</select>"
						?>
					</td>
                    <td>
						<input type="text" name="IP_ADDRESS" value="IP Address">
					</td>
                    <td>
						<?php
							echo "<select name='PASSWORD_ID'>";
							$sql = "SELECT * FROM PASSWORDS";
							foreach ($pdo->query($sql) as $row) {
								echo "<option value=". $row['ID'] .">". $row['USERNAME'] ."</option>";
							}
							echo "</select>"
						?>									
					</td>
                    <td>
						<?php
							echo "<select name='SYSTEM_ID'>";
							$sql = "SELECT * FROM SYSTEMS";
							foreach ($pdo->query($sql) as $row) {
								echo "<option value=". $row['ID'] .">". $row['SYSTEM_NAME'] ."</option>";
							}
							echo "</select>"
						?>										
					</td>
                    <td>
						<input type="text" name="Target_Description" value="Description">
					</td>
					<td>
						<input type="hidden" name="NewTarget" value="TRUE"><input type="Submit" class="btn btn-success" value="Add Target"></td>
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