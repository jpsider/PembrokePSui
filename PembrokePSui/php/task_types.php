<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewTASKType'])) {
		$TASK_NAME=$_GET['TASK_NAME'];
		$TASK_PATH=$_GET['TASK_PATH'];
		$PRIORITY=$_GET['PRIORITY'];
		include 'components/database.php';
		$sql = "INSERT INTO TASK_TYPES (TASK_NAME,TASK_PATH,PRIORITY,STATUS_ID) VALUES ('$TASK_NAME','$TASK_PATH','$PRIORITY',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=task_types.php");		
	}
	elseif (!empty($_GET['UpdateTASKType'])) {
		$ID=$_GET['ID'];
		$TASK_NAME=$_GET['TASK_NAME'];
        $TASK_PATH=$_GET['TASK_PATH'];
		$PRIORITY=$_GET['PRIORITY'];        
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TASK_TYPES SET TASK_NAME='$TASK_NAME',TASK_PATH='$TASK_PATH',PRIORITY='$PRIORITY' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=task_types.php");
	}
	elseif (!empty($_GET['DisableTASKType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TASK_TYPES SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=task_types.php");		
	}
	elseif (!empty($_GET['EnableTASKType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TASK_TYPES SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=task_types.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Task Types</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>NAME</th>
                <th>Path</th>
                <th>Priority</th>
                <th>Update</th>
                <th>Status</th>
                <th>Action</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = 'select tt.ID, '
						    . 'tt.TASK_NAME, '
						    . 'tt.TASK_PATH, '
						    . 'tt.STATUS_ID, '
						    . 'tt.PRIORITY, '
						    . 'tt.date_modified, '
						    . 's.STATUS_NAME, '
                            . 's.HTMLCOLOR '                                    
                        . 'from TASK_TYPES tt '                                    
                        . 'join STATUS s on tt.STATUS_ID=s.ID'; 
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<form action="tasks.php" method="get">';
					echo '<td><input type="hidden" name="TASK_TYPE_ID" value="' . $row['ID'] . '"><input type="Submit" class="btn btn-info" value="'. $row['ID'] . '"</td></form>';
					echo '<form action="task_types.php" method="get">';
					echo '<td><input type="text" name="TASK_NAME" value="'. $row['TASK_NAME'] . '"></td>';
					echo '<td><input type="text" name="TASK_PATH" value="'. $row['TASK_PATH'] . '"></td>';
					echo '<td><input type="text" name="PRIORITY" value="'. $row['PRIORITY'] . '"></td>';
                    echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '"><input type="hidden" name="UpdateTASKType" value="TRUE"><input type="Submit" class="btn btn-success" value="Update"</td>';
					echo '</form>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="task_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableTASKType" value="TRUE"><input type="Submit" class="btn btn-danger" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="task_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableTASKType" value="TRUE"><input type="Submit" class="btn btn-success" value="Enable"></td>';
						echo '</form>';
					}                                
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
				<th>Path:</th>
				<th>Priority:</th>
				<th>Submit</th>
			</tr>
            <tr>
                <form>
                    <td><b>Add a New Task Type</b></td>
                    <td>
						<input type="text" name="TASK_NAME" value="Enter a NAME">
					</td>
                    <td>
						<input type="text" name="TASK_PATH" value="Enter a Path">
					</td>
                    <td>
						<input type="text" name="PRIORITY" value="Enter a Priority">
					</td>
					<td>
						<input type="hidden" name="NewTASKType" value="TRUE"><input type="Submit" class="btn btn-success" value="Add TaskType"></td>
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