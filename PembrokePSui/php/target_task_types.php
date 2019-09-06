<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewTargetTASKType'])) {
		$TARGET_TYPE_ID=$_GET['TARGET_TYPE_ID'];
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
		$MAX_Retries=$_GET['MAX_Retries'];
		include 'components/database.php';
		$sql = "INSERT INTO TARGET_TASKS_TYPES (TARGET_TYPE_ID,TASK_TYPE_ID,MAX_Retries,STATUS_ID) VALUES ('$TARGET_TYPE_ID','$TASK_TYPE_ID','$MAX_Retries',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=target_task_types.php");		
	}
	elseif (!empty($_GET['DisableParentTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGET_TASKS_TYPES SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_task_types.php");		
	}
	elseif (!empty($_GET['EnableParentTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE TARGET_TASKS_TYPES SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=target_task_types.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Target TASK Type</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Target Type</th>
                <th>TASK NAME</th>
                <th>Max Retries</th>
                <th>Status</th>
                <th>Action</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = 'select ttt.ID, '
						    . 'ttt.TARGET_TYPE_ID, '
						    . 'ttt.TASK_TYPE_ID, '
						    . 'ttt.STATUS_ID, '
						    . 'ttt.MAX_Retries, '
						    . 'ttt.date_modified, '
						    . 'tt.NAME, '
						    . 'tts.TASK_NAME, '
						    . 's.STATUS_NAME, '
                            . 's.HTMLCOLOR '                                    
                        . 'from TARGET_TASKS_TYPES ttt '                                    
                        . 'join STATUS s on ttt.STATUS_ID=s.ID ' 
                        . 'join TARGET_TYPES tt on ttt.TARGET_TYPE_ID=tt.ID '
                        . 'join TASK_TYPES tts on ttt.TASK_TYPE_ID=tts.ID ';
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<td>'. $row['NAME'] . '</td>';
					echo '<td>'. $row['TASK_NAME'] . '</td>';
					echo '<td>'. $row['MAX_Retries'] . '</td>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="target_task_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableParentTargetType" value="TRUE"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="target_task_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableParentTargetType" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Enable"></td>';
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
                <td><b>Add a Target TASK Combo</b></td>
                <td><b>Target Type</b></td>
                <td><b>TASK Type</b></td>
                <td><b>Max Retries</b></td>
                <td><b>Submit</b></td>
            </tr>
            <tr>
                <form>
                    <td><b>Select:</b></td>
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
                        <?php
                            echo "<select name='TASK_TYPE_ID'>";
                            $sql = "SELECT * FROM TASK_TYPES";
                            foreach ($pdo->query($sql) as $row) {
                                echo "<option value=". $row['ID'] .">". $row['TASK_NAME'] ."</option>";
                            }
                            echo "</select>"
                        ?>
                    </td>
                    <td>
						<input type="text" name="MAX_Retries" value="Enter a Max Retry">
					</td>
					<td>
						<input type="hidden" name="NewTargetTASKType" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Add Target TASK Type Combo"></td>
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