<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewQMGroupFile'])) {
		$MANAGED_FILE_ID=$_GET['MANAGED_FILE_ID'];
		$QUEUE_MANAGER_TYPE_ID=$_GET['QUEUE_MANAGER_TYPE_ID'];
		include 'components/database.php';
		$sql = "INSERT INTO MANAGED_FILE_QM_GROUPS (MANAGED_FILE_ID,QUEUE_MANAGER_TYPE_ID,STATUS_ID) VALUES ('$MANAGED_FILE_ID','$QUEUE_MANAGER_TYPE_ID',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=queue_mgr_file_groups.php");		
	}
	elseif (!empty($_GET['DisableQMGroupFile'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE MANAGED_FILE_QM_GROUPS SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=queue_mgr_file_groups.php");		
	}
	elseif (!empty($_GET['EnableQMGroupFile'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE MANAGED_FILE_QM_GROUPS SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=queue_mgr_file_groups.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Qman File Groups</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>File NAME</th>
                <th>Qman Type</th>
                <th>Status</th>
                <th>Action</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = 'select qmg.ID, '
						    . 'qmg.MANAGED_FILE_ID, '
						    . 'qmg.QUEUE_MANAGER_TYPE_ID, '
						    . 'qmg.STATUS_ID, '
						    . 'qmg.date_modified, '
						    . 'mf.FILE_NAME, '
						    . 'qmt.NAME, '
						    . 's.STATUS_NAME, '
                            . 's.HTMLCOLOR '                                    
                        . 'from MANAGED_FILE_QM_GROUPS qmg '                                    
                        . 'join STATUS s on qmg.STATUS_ID=s.ID ' 
                        . 'join MANAGED_FILES mf on qmg.MANAGED_FILE_ID=mf.ID '
                        . 'join QUEUE_MANAGER_TYPE qmt on qmg.QUEUE_MANAGER_TYPE_ID=qmt.ID ';
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<td><form action="qman_files.php" method="get"><input type="hidden" name="MANAGED_FILE_ID" value="' . $row['MANAGED_FILE_ID'] . '"><input type="Submit" class="btn btn-info" value="'. $row['FILE_NAME'] . '"></form></td>';
					echo '<td>'. $row['NAME'] . '</td>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="queue_mgr_file_groups.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableQMGroupFile" value="TRUE"><input type="Submit" class="btn btn-danger" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="queue_mgr_file_groups.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableQMGroupFile" value="TRUE"><input type="Submit" class="btn btn-success" value="Enable"></td>';
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
                <td><b>Add a New QMan Managed File</b></td>
                <td><b>File NAME</b></td>
                <td><b>Queue Manager Type</b></td>
                <td><b>Submit</b></td>
            </tr>
            <tr>
                <form>
                    <td><b>Select:</b></td>
					<td>
                        <?php
                            echo "<select name='MANAGED_FILE_ID'>";
                            $sql = "SELECT * FROM MANAGED_FILES";
                            foreach ($pdo->query($sql) as $row) {
                                echo "<option value=". $row['ID'] .">". $row['FILE_NAME'] ."</option>";
                            }
                            echo "</select>"
                        ?>
                    </td>
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
						<input type="hidden" name="NewQMGroupFile" value="TRUE"><input type="Submit" class="btn btn-success" value="Add Managed File"></td>
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