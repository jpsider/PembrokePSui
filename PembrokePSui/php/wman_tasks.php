<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewWmanTASK'])) {
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
		$WORKFLOW_MANAGER_TYPE_ID=$_GET['WORKFLOW_MANAGER_TYPE_ID'];
		include 'components/database.php';
		$sql = "INSERT INTO WMAN_TASK_TYPES (WORKFLOW_MANAGER_TYPE_ID,TASK_TYPE_ID,STATUS_ID) VALUES ('$WORKFLOW_MANAGER_TYPE_ID','$TASK_TYPE_ID',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=wman_tasks.php");		
	}
	elseif (!empty($_GET['DisableWmanTASK'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE WMAN_TASK_TYPES SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=wman_tasks.php");		
	}
	elseif (!empty($_GET['EnableWmanTASK'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE WMAN_TASK_TYPES SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=wman_tasks.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Workflow Manager TASKs</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Workflow Manager Type</th>
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
				$sql = 'select wtt.ID, '
						    . 'wtt.WORKFLOW_MANAGER_TYPE_ID, '
						    . 'wtt.TASK_TYPE_ID, '
						    . 'wtt.STATUS_ID, '
						    . 'wtt.date_modified, '
						    . 'wt.NAME, '
						    . 'wt.TABLENAME, '
						    . 'tt.TASK_NAME, '
						    . 's.STATUS_NAME, '
                            . 's.HTMLCOLOR '                                    
                        . 'from WMAN_TASK_TYPES wtt '                                    
                        . 'join STATUS s on wtt.STATUS_ID=s.ID ' 
                        . 'join TASK_TYPES tt on wtt.TASK_TYPE_ID=tt.ID '
                        . 'join WORKFLOW_MANAGER_TYPE wt on wtt.WORKFLOW_MANAGER_TYPE_ID=wt.ID ';
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<td>'. $row['NAME'] . '</td>';
					echo '<td>'. $row['TASK_NAME'] . '</td>';
					echo '<td>'. $row['TABLENAME'] . '</td>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="wman_tasks.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableWmanTASK" value="TRUE"><input type="Submit" class="btn btn-danger" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="wman_tasks.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableWmanTASK" value="TRUE"><input type="Submit" class="btn btn-success" value="Enable"></td>';
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
                <td><b>Add a New Wman TASK</b></td>
                <td><b>Workflow Mgr Type</b></td>
                <td><b>TASK Type</b></td>
                <td><b>Submit</b></td>
            </tr>
            <tr>
                <form>
                    <td><b>Select:</b></td>
					<td>
                        <?php
                            echo "<select name='WORKFLOW_MANAGER_TYPE_ID'>";
                            $sql = "SELECT * FROM WORKFLOW_MANAGER_TYPE";
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
						<input type="hidden" name="NewWmanTASK" value="TRUE"><input type="Submit" class="btn btn-success" value="Add Workflow Mgr TASK"></td>
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