<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewQmanTASK'])) {
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
		$QUEUE_MANAGER_TYPE_ID=$_GET['QUEUE_MANAGER_TYPE_ID'];
		include 'components/database.php';
		$sql = "INSERT INTO QMAN_TASK_TYPES (QUEUE_MANAGER_TYPE_ID,TASK_TYPE_ID,STATUS_ID) VALUES ('$QUEUE_MANAGER_TYPE_ID','$TASK_TYPE_ID',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=qman_tasks.php");		
	}
	elseif (!empty($_GET['DisableQmanTASK'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE QMAN_TASK_TYPES SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=qman_tasks.php");		
	}
	elseif (!empty($_GET['EnableQmanTASK'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE QMAN_TASK_TYPES SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=qman_tasks.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Queue Manager TASKs</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Queue Manager Type</th>
                <th>TASK NAME</th>
                <th>Table NAME</th>
                <th>Status</th>
                <th>Action</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = 'select qtt.ID, '
						    . 'qtt.QUEUE_MANAGER_TYPE_ID, '
						    . 'qtt.TASK_TYPE_ID, '
						    . 'qtt.STATUS_ID, '
						    . 'qtt.date_modified, '
						    . 'qt.NAME, '
						    . 'qt.TABLENAME, '
						    . 'tt.TASK_NAME, '
						    . 's.STATUS_NAME, '
                            . 's.HTMLCOLOR '                                    
                        . 'from QMAN_TASK_TYPES qtt '                                    
                        . 'join STATUS s on qtt.STATUS_ID=s.ID ' 
                        . 'join TASK_TYPES tt on qtt.TASK_TYPE_ID=tt.ID '
                        . 'join QUEUE_MANAGER_TYPE qt on qtt.QUEUE_MANAGER_TYPE_ID=qt.ID ';
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<td>'. $row['NAME'] . '</td>';
					echo '<td>'. $row['TASK_NAME'] . '</td>';
					echo '<td>'. $row['TABLENAME'] . '</td>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="qman_tasks.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableQmanTASK" value="TRUE"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="qman_tasks.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableQmanTASK" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Enable"></td>';
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
                <td><b>Add a New Qman TASK</b></td>
                <td><b>Queue Mgr Type</b></td>
                <td><b>TASK Type</b></td>
                <td><b>Submit</b></td>
            </tr>
            <tr>
                <form>
                    <td><b>Select:</b></td>
					<td>
                        <?php
                            echo "<select name='QUEUE_MANAGER_TYPE_ID'>";
                            $sql = "SELECT * FROM QUEUE_MANAGER_TYPE";
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
						<input type="hidden" name="NewQmanTASK" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Add Queue Mgr TASK"></td>
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