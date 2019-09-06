<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewwmgroupFile'])) {
		$MANAGED_FILE_ID=$_GET['MANAGED_FILE_ID'];
		$WORKFLOW_MANAGER_TYPE_ID=$_GET['WORKFLOW_MANAGER_TYPE_ID'];
		include 'components/database.php';
		$sql = "INSERT INTO MANAGED_FILE_WM_GROUPS (MANAGED_FILE_ID,WORKFLOW_MANAGER_TYPE_ID,STATUS_ID) VALUES ('$MANAGED_FILE_ID','$WORKFLOW_MANAGER_TYPE_ID',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=workflow_mgr_file_groups.php");		
	}
	elseif (!empty($_GET['DisableWMgroupFile'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE MANAGED_FILE_WM_GROUPS SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=workflow_mgr_file_groups.php");		
	}
	elseif (!empty($_GET['EnableWMgroupFile'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE MANAGED_FILE_WM_GROUPS SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=workflow_mgr_file_groups.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Wman File Groups</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>File NAME</th>
                <th>Wman Type</th>
                <th>Status</th>
                <th>Action</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = 'select wmg.ID, '
						    . 'wmg.MANAGED_FILE_ID, '
						    . 'wmg.WORKFLOW_MANAGER_TYPE_ID, '
						    . 'wmg.STATUS_ID, '
						    . 'wmg.date_modified, '
						    . 'mf.FILE_NAME, '
						    . 'wmt.NAME, '
						    . 's.STATUS_NAME, '
                            . 's.HTMLCOLOR '                                    
                        . 'from MANAGED_FILE_WM_GROUPS wmg '                                    
                        . 'join STATUS s on wmg.STATUS_ID=s.ID ' 
                        . 'JOIN MANAGED_FILES mf on wmg.MANAGED_FILE_ID=mf.ID '
                        . 'join WORKFLOW_MANAGER_TYPE wmt on wmg.WORKFLOW_MANAGER_TYPE_ID=wmt.ID ';
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<form action="wman_files.php" method="get"><td><input type="hidden" name="MANAGED_FILE_ID" value="' . $row['MANAGED_FILE_ID'] . '"><input type="Submit" class="btn btn-info btn-sm" value="'. $row['FILE_NAME'] . '"></td></form>';
					echo '<td>'. $row['NAME'] . '</td>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="workflow_mgr_file_groups.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableWMgroupFile" value="TRUE"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="workflow_mgr_file_groups.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableWMgroupFile" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Enable"></td>';
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
                <td><b>Add a New WMan Managed File</b></td>
                <td><b>File NAME</b></td>
                <td><b>Worflow Manager Type</b></td>
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
                            echo "<select name='WORKFLOW_MANAGER_TYPE_ID'>";
                            $sql = "SELECT * FROM WORKFLOW_MANAGER_TYPE";
                            foreach ($pdo->query($sql) as $row) {
                                echo "<option value=". $row['ID'] .">". $row['NAME'] ."</option>";
                            }
                            echo "</select>"
                        ?>
                    </td>
					<td>
						<input type="hidden" name="NewwmgroupFile" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Add Managed File"></td>
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