<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewTarget'])) {
		$Target_Name=$_GET['Target_Name'];
		$Target_Type_ID=$_GET['Target_Type_ID'];
		$IP_Address=$_GET['IP_Address'];
		$Password_ID=$_GET['Password_ID'];
		$System_ID=$_GET['System_ID'];
		$Target_Description=$_GET['Target_Description'];
		include 'components/database.php';
		$sql = "INSERT INTO targets (Target_Name,Target_Type_ID,IP_Address,STATUS_ID,Password_ID,System_ID,Target_Description) VALUES ('$Target_Name',$Target_Type_ID,'$IP_Address',11,$Password_ID,$System_ID,'$Target_Description')";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=targets.php");		
	}
	elseif (!empty($_GET['UpdateTarget'])) {
		$ID=$_GET['ID'];
		$Target_Name=$_GET['Target_Name'];
		$Target_Type_ID=$_GET['Target_Type_ID'];
		$IP_Address=$_GET['IP_Address'];
		$Password_ID=$_GET['Password_ID'];
		$System_ID=$_GET['System_ID'];
		$Target_Description=$_GET['Target_Description'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update targets set Target_Name='$Target_Name',Target_Type_ID='$Target_Type_ID',IP_Address='$IP_Address',Password_ID='$Password_ID',System_ID='$System_ID',Target_Description='$Target_Description' where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=targets.php");
	}
	elseif (!empty($_GET['DisableTarget'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update targets set STATUS_ID=12 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=targets.php");		
	}
	elseif (!empty($_GET['EnableTarget'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "update targets set STATUS_ID=11 where ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=targets.php");			
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
				<h3>PembrokePS Targets</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
							<th>IP Address</th>
							<th>Target Type</th>
                            <th>Password Info</th>
                            <th>Target_Description</th>
                            <th>System</th>
							<th>Update</th>
							<th>Status</th>
							<th>Tasks</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							if (!empty($_GET['Target_Type_ID'])) {
								$Target_Type_ID=$_GET['Target_Type_ID'];
							} else {
								$Target_Type_ID='%%';
							}

							$sql = "select t.ID, "
									    . "t.Target_Name, "
									    . "t.Target_Type_ID, "
									    . "t.IP_Address, "
									    . "t.Status_ID, "
									    . "t.Password_ID, "
									    . "t.System_ID, "
									    . "t.Target_Description, "
									    . "t.date_modified, "
									    . "tt.NAME as Target_Type, "
									    . "ss.SYSTEM_NAME, "
									    . "p.Username, "
									    . "s.STATUS_NAME, "
                                        . "s.HTMLCOLOR "                                 
                                    . "from targets t "                                   
                                    . "join status s on t.Status_ID=s.ID "
                                    . "join PASSWORDS p on t.Password_ID=p.ID "
                                    . "join SYSTEMS ss on t.System_ID=ss.ID "
									. "join Target_Types tt on t.Target_Type_ID=tt.ID "
									. "where t.Target_Type_ID like '$Target_Type_ID'";

							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<form action="targets.php" method="get">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="Target_Name" value="'. $row['Target_Name'] . '"></td>';
								echo '<td><input type="text" name="IP_Address" value="'. $row['IP_Address'] . '"></td>';
								echo '<td><input type="hidden" name="Target_Type_ID" value="' . $row['Target_Type_ID'] . '">'. $row['Target_Type'] . '</td>';
								echo '<td><input type="hidden" name="Password_ID" value="' . $row['Password_ID'] . '">'. $row['Username'] . '</td>';
								echo '<td><input type="text" name="Target_Description" value="'. $row['Target_Description'] . '"></td>';
								echo '<td><input type="hidden" name="System_ID" value="' . $row['System_ID'] . '">'. $row['SYSTEM_NAME'] . '</td>';
                                echo '<td><input type="hidden" name="UpdateTarget" value="TRUE"><input type="submit" class="btn btn-success" value="Update"</td>';
                                echo '</form>';
								if($row['STATUS_NAME'] == 'Enabled'){
									echo '<form action="targets.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="DisableTarget" value="TRUE"><input type="submit" class="btn btn-danger" value="Disable"></td>';
									echo '</form>';
								} else {
									echo '<form action="targets.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td><input type="hidden" name="EnableTarget" value="TRUE"><input type="submit" class="btn btn-success" value="Enable"></td>';
									echo '</form>';
								}                                
								echo '<td><form action="tasks.php" method="get"><input type="hidden" name="Target_ID" value="' . $row['ID'] . '"><input type="submit" class="btn btn-info" value="Tasks"></form></td>';
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
                                <td><b>Add a New Target</b></td>
                                <td>
									<input type="text" name="Target_Name" value="Enter a Name">
								</td>
                                <td>
									<?php
										echo "<select name='Target_Type_ID'>";
										$sql = "select * from Target_Types";
										foreach ($pdo->query($sql) as $row) {
											echo "<option value=". $row['ID'] .">". $row['Name'] ."</option>";
										}
										echo "</select>"
									?>
								</td>
                                <td>
									<input type="text" name="IP_Address" value="IP Address">
								</td>
                                <td>
									<?php
										echo "<select name='Password_ID'>";
										$sql = "select * from passwords";
										foreach ($pdo->query($sql) as $row) {
											echo "<option value=". $row['ID'] .">". $row['Username'] ."</option>";
										}
										echo "</select>"
									?>									
								</td>
                                <td>
									<?php
										echo "<select name='System_ID'>";
										$sql = "select * from systems";
										foreach ($pdo->query($sql) as $row) {
											echo "<option value=". $row['ID'] .">". $row['SYSTEM_Name'] ."</option>";
										}
										echo "</select>"
									?>										
								</td>
                                <td>
									<input type="text" name="Target_Description" value="Description">
								</td>
								<td>
									<input type="hidden" name="NewTarget" value="TRUE"><input type="submit" class="btn btn-success" value="Add Target"></td>
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