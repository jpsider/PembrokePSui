<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewSUBTASK'])) {
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
		$PASS_SUBTASK_ID=$_GET['PASS_SUBTASK_ID'];
		$FAIL_SUBTASK_ID=$_GET['FAIL_SUBTASK_ID'];
		include 'components/database.php';
		$sql = "INSERT INTO SUBTASK_GENERATOR (TASK_TYPE_ID,PASS_SUBTASK_ID,FAIL_SUBTASK_ID,STATUS_ID) VALUES ('$TASK_TYPE_ID','$PASS_SUBTASK_ID','$FAIL_SUBTASK_ID',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=subtask_generator.php");		
	}
	elseif (!empty($_GET['DisableSUBTASK'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE SUBTASK_GENERATOR SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=subtask_generator.php");		
	}
	elseif (!empty($_GET['EnableSUBTASK'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE SUBTASK_GENERATOR SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=subtask_generator.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>SUBTASK Generator</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Primary task</th>
                <th>PASS task</th>
                <th>FAIL task</th>
                <th>Status</th>
                <th>Action</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = 'select sg.ID, '
						    . 'sg.TASK_TYPE_ID, '
						    . 'sg.PASS_SUBTASK_ID, '
						    . 'sg.FAIL_SUBTASK_ID, '
						    . 'sg.STATUS_ID, '
						    . 'sg.date_modified, '
						    . 'tt.TASK_NAME as Primary_TASK, '
						    . 'pt.TASK_NAME as PASS_TASK, '
						    . 'ft.TASK_NAME as FAIL_TASK, '
						    . 's.STATUS_NAME, '
                            . 's.HTMLCOLOR '                                    
                        . 'from SUBTASK_GENERATOR sg '                                    
                        . 'join STATUS s on sg.STATUS_ID=s.ID ' 
                        . 'join TASK_TYPES tt on sg.TASK_TYPE_ID=tt.ID '
                        . 'join TASK_TYPES pt on sg.PASS_SUBTASK_ID=pt.ID ' 
                        . 'join TASK_TYPES ft on sg.FAIL_SUBTASK_ID=ft.ID ';
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<td>'. $row['Primary_TASK'] . '</td>';
					echo '<td>'. $row['PASS_TASK'] . '</td>';
					echo '<td>'. $row['FAIL_TASK'] . '</td>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '>'. $row['STATUS_NAME'] . '</td>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="subtask_generator.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableSUBTASK" value="TRUE"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="subtask_generator.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableSUBTASK" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Enable"></td>';
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
                <td><b>Add a New subtask</b></td>
                <td><b>Primary Task</b></td>
                <td><b>Passed subtask</b></td>
                <td><b>Failed subtask</b></td>
                <td><b>Submit</b></td>
            </tr>
            <tr>
                <form>
                    <td><b>Select:</b></td>
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
                        <?php
                            echo "<select name='PASS_SUBTASK_ID'>";
                            $sql = "SELECT * FROM TASK_TYPES";
                            foreach ($pdo->query($sql) as $row) {
                                echo "<option value=". $row['ID'] .">". $row['TASK_NAME'] ."</option>";
                            }
                            echo "</select>"
                        ?>
                    </td>
					<td>
                        <?php
                            echo "<select name='FAIL_SUBTASK_ID'>";
                            $sql = "SELECT * FROM TASK_TYPES";
                            foreach ($pdo->query($sql) as $row) {
                                echo "<option value=". $row['ID'] .">". $row['TASK_NAME'] ."</option>";
                            }
                            echo "</select>"
                        ?>
                    </td>
					<td>
						<input type="hidden" name="NewSUBTASK" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Add SubTask"></td>
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