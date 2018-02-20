<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['DeployQMFile'])) {
		$ID=$_GET['ID'];
		# TODO
		#	First the deploy files qman TASK needs to be build to ensure we get the argments and ID's correct.
		include 'components/database.php';
		#$sql = "INSERT INTO TASKS (STATUS_ID,RESULT_ID,TASK_TYPE_ID,LOG_FILE,WORKFLOW_MANAGER_ID,TARGET_ID,ARGUMENTS,HIDDEN) VALUES (5,6,'$TASK_TYPE_ID','no-log',9999,9999,'$FileID',0)";
		#$pdo = Database::connect();
        #$pdo->query($sql);
		header("Refresh:0 url=qman_files.php");		
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
				<h3>Queue Manager Files</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>QMan ID</th>
                            <th>HOSTNAME</th>
                            <th>QMan_Description</th>
                            <th>FILE_NAME</th>
                            <th>FILE_PATH</th>
                            <th>FILE_HASH</th>
                            <th>QUEUE_MGR_HASH</th>
                            <th>RESULT_ID</th>
                            <th>Action</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if (!empty($_GET['QUEUE_MANAGER_ID'])) {
								$QUEUE_MANAGER_ID=$_GET['QUEUE_MANAGER_ID'];
							} else {
								$QUEUE_MANAGER_ID='%%';
							}
							if (!empty($_GET['MANAGED_FILE_ID'])) {
								$MANAGED_FILE_ID=$_GET['MANAGED_FILE_ID'];
							} else {
								$MANAGED_FILE_ID='%%';
							}
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select qmf.ID, '
									    . 'qmf.QUEUE_MANAGER_ID, '
									    . 'qmf.MANAGED_FILE_ID, '
									    . 'qmf.QUEUE_MGR_HASH, '
									    . 'qmf.RESULT_ID, '
									    . 'qmf.date_modified, '
									    . 'mf.FILE_NAME, '
									    . 'mf.FILE_PATH, '
									    . 'mf.FILE_HASH, '
									    . 'qm.HOSTNAME, '
									    . 'qm.QMan_Description, '
									    . 'r.RESULT_NAME, '
                                        . 'r.HTMLCOLOR '                                    
                                    . 'from QUEUE_MANAGER_FILES qmf '                                    
                                    . 'join RESULTS r on qmf.RESULT_ID=r.ID ' 
                                    . 'JOIN MANAGED_FILES mf on qmf.MANAGED_FILE_ID=mf.ID '
									. 'join QUEUE_MANAGER qm on qmf.QUEUE_MANAGER_ID=qm.ID '
									. "where qmf.QUEUE_MANAGER_ID like '$QUEUE_MANAGER_ID' and qmf.MANAGED_FILE_ID like '$MANAGED_FILE_ID'";
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td><form action="qman_files.php" method="get"><input type="hidden" name="QUEUE_MANAGER_ID" value="' . $row['QUEUE_MANAGER_ID'] . '"><input type="Submit" class="btn btn-info" value="'. $row['QUEUE_MANAGER_ID'] . '"></form></td>';
								echo '<td>'. $row['HOSTNAME'] . '</td>';
								echo '<td>'. $row['QMan_Description'] . '</td>';
								echo '<td><form action="qman_files.php" method="get"><input type="hidden" name="MANAGED_FILE_ID" value="' . $row['MANAGED_FILE_ID'] . '"><input type="Submit" class="btn btn-info" value="'. $row['FILE_NAME'] . '"></form></td>';
								echo '<td>'. $row['FILE_PATH'] . '</td>';
								echo '<td>'. $row['FILE_HASH'] . '</td>';
								echo '<td>'. $row['QUEUE_MGR_HASH'] . '</td>';
								if($row['RESULT_ID'] == 1){
									echo '<td style=background-color:'. $row['HTMLCOLOR'] . '>' . $row['RESULT_NAME'] . '</td>';
									echo '<td></td>';
								} else {
									echo '<form action="qman_files.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td style=background-color:'. $row['HTMLCOLOR'] . '>' . $row['RESULT_NAME'] . '</td>';
									echo '<td><input type="hidden" name="DeployQMFile" value="TRUE"><input type="Submit" class="btn btn-success" value="Deploy File"></td>';
									echo '</form>';
								}                                
								echo '<td>'. $row['date_modified'] . '</td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
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