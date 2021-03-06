<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['DeployWMFile'])) {
		$ID=$_GET['ID'];
		# TODO
		#	First the deploy wman files TASK needs to be build to ensure we get the argments and ID's correct.
		include 'components/database.php';
		#$sql = "INSERT INTO TASKS (STATUS_ID,RESULT_ID,TASK_TYPE_ID,LOG_FILE,WORKFLOW_MANAGER_ID,TARGET_ID,ARGUMENTS,HIDDEN) VALUES (5,6,'$TASK_TYPE_ID','no-log',9999,9999,'$FileID',0)";
		#$pdo = Database::connect();
        #$pdo->query($sql);
		header("Refresh:0 url=wman_files.php");		
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>WORKFLOW Manager Files</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>WORKFLOW Manager ID</th>
                <th>HOSTNAME</th>
                <th>WMan_Description</th>
                <th>FILE_NAME</th>
                <th>FILE_PATH</th>
                <th>FILE_HASH</th>
                <th>WORKFLOW_MGR_HASH</th>
                <th>RESULT_ID</th>
                <th>Action</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($_GET['WORKFLOW_MANAGER_ID'])) {
					$WORKFLOW_MANAGER_ID=$_GET['WORKFLOW_MANAGER_ID'];
				} else {
					$WORKFLOW_MANAGER_ID='%%';
				}
				if (!empty($_GET['MANAGED_FILE_ID'])) {
					$MANAGED_FILE_ID=$_GET['MANAGED_FILE_ID'];
				} else {
					$MANAGED_FILE_ID='%%';
				}
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = 'select wmf.ID, '
						    . 'wmf.WORKFLOW_MANAGER_ID, '
						    . 'wmf.MANAGED_FILE_ID, '
						    . 'wmf.WORKFLOW_MGR_HASH, '
						    . 'wmf.RESULT_ID, '
						    . 'wmf.date_modified, '
						    . 'mf.FILE_NAME, '
						    . 'mf.FILE_PATH, '
						    . 'mf.FILE_HASH, '
						    . 'wm.HOSTNAME, '
						    . 'wm.WMan_Description, '
						    . 'r.RESULT_NAME, '
                            . 'r.HTMLCOLOR '                                    
                        . 'from WORKFLOW_MANAGER_FILES wmf '                                    
                        . 'join RESULTS r on wmf.RESULT_ID=r.ID ' 
                        . 'JOIN MANAGED_FILES mf on wmf.MANAGED_FILE_ID=mf.ID '
						. 'join WORKFLOW_MANAGER wm on wmf.WORKFLOW_MANAGER_ID=wm.ID '
						. "where wmf.WORKFLOW_MANAGER_ID like '$WORKFLOW_MANAGER_ID' and wmf.MANAGED_FILE_ID like '$MANAGED_FILE_ID'";
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<form action="wman_files.php" method="get"><td><input type="hidden" name="WORKFLOW_MANAGER_ID" value="' . $row['WORKFLOW_MANAGER_ID'] . '"><input type="Submit" class="btn btn-info btn-sm" value="'. $row['WORKFLOW_MANAGER_ID'] . '"></form></td>';
					echo '<td>'. $row['HOSTNAME'] . '</td>';
					echo '<td>'. $row['WMan_Description'] . '</td>';
					echo '<form action="wman_files.php" method="get"><td><input type="hidden" name="MANAGED_FILE_ID" value="' . $row['MANAGED_FILE_ID'] . '"><input type="Submit" class="btn btn-info btn-sm" value="'. $row['FILE_NAME'] . '"></td></form>';
					echo '<td>'. $row['FILE_PATH'] . '</td>';
					echo '<td>'. $row['FILE_HASH'] . '</td>';
					echo '<td>'. $row['WORKFLOW_MGR_HASH'] . '</td>';
					if($row['RESULT_ID'] == 1){
						echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>' . $row['RESULT_NAME'] . '</b></td>';
						echo '<td></td>';
					} else {
						echo '<form action="wman_files.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>' . $row['RESULT_NAME'] . '</b></td>';
						echo '<td><input type="hidden" name="DeployWMFile" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Deploy File"></td>';
						echo '</form>';
					}                                
					echo '<td>'. $row['date_modified'] . '</td>';
					echo '</tr>';
				}
				Database::disconnect();
				?>
			</tbody>
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