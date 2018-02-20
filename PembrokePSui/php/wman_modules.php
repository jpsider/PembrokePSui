<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['DeployWmModule'])) {
		$ID=$_GET['ID'];
		# TODO
		#	First the deploy wman files TASK needs to be build to ensure we get the argments and ID's correct.
		include 'components/database.php';
		#$sql = "INSERT INTO TASKS (STATUS_ID,RESULT_ID,TASK_TYPE_ID,LOG_FILE,WORKFLOW_MANAGER_ID,TARGET_ID,ARGUMENTS,HIDDEN) VALUES (5,6,'$TASK_TYPE_ID','no-log',9999,9999,'$FileID',0)";
		#$pdo = Database::connect();
        #$pdo->query($sql);
		header("Refresh:0 url=wman_modules.php");		
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
				<h3>WORKFLOW Manager Addl Module Status</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>WORKFLOW Manager ID</th>
                            <th>HOSTNAME</th>
                            <th>WMan_Description</th>
                            <th>Module NAME</th>
                            <th>Module Version</th>
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
							if (!empty($_GET['ADDL_MODULE_ID'])) {
								$ADDL_MODULE_ID=$_GET['ADDL_MODULE_ID'];
							} else {
								$ADDL_MODULE_ID='%%';
							}

							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select wmm.ID, '
									    . 'wmm.WORKFLOW_MANAGER_ID, '
									    . 'wmm.ADDL_MODULE_ID, '
									    . 'wmm.RESULT_ID, '
									    . 'wmm.date_modified, '
									    . 'am.GALLERY_NAME, '
									    . 'am.MODULE_VERSION, '
									    . 'wm.HOSTNAME, '
									    . 'wm.WMan_Description, '
									    . 'r.RESULT_NAME, '
                                        . 'r.HTMLCOLOR '                                    
                                    . 'from WORKFLOW_MANAGER_MODULES wmm '                                    
                                    . 'join RESULTS r on wmm.RESULT_ID=r.ID ' 
                                    . 'join ADDITIONAL_PS_MODULES am on wmm.ADDL_MODULE_ID=am.ID '
									. 'join WORKFLOW_MANAGER wm on wmm.WORKFLOW_MANAGER_ID=wm.ID '
									. "where wmm.WORKFLOW_MANAGER_ID like '$WORKFLOW_MANAGER_ID' and wmm.ADDL_MODULE_ID like '$ADDL_MODULE_ID'";
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td><form action="wman_modules.php" method="get"><input type="hidden" name="WORKFLOW_MANAGER_ID" value="' . $row['WORKFLOW_MANAGER_ID'] . '"><input type="Submit" class="btn btn-info" value="'. $row['WORKFLOW_MANAGER_ID'] . '"></form></td>';
								echo '<td>'. $row['HOSTNAME'] . '</td>';
								echo '<td>'. $row['WMan_Description'] . '</td>';
								echo '<td><form action="wman_modules.php" method="get"><input type="hidden" name="ADDL_MODULE_ID" value="' . $row['ADDL_MODULE_ID'] . '"><input type="Submit" class="btn btn-info" value="'. $row['GALLERY_NAME'] . '"></form></td>';
								echo '<td>'. $row['MODULE_VERSION'] . '</td>';
								if($row['RESULT_ID'] == 1){
									echo '<td style=background-color:'. $row['HTMLCOLOR'] . '>' . $row['RESULT_NAME'] . '</td>';
									echo '<td></td>';
								} else {
									echo '<form action="wman_modules.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
									echo '<td style=background-color:'. $row['HTMLCOLOR'] . '>' . $row['RESULT_NAME'] . '</td>';
									echo '<td><input type="hidden" name="DeployWmModule" value="TRUE"><input type="Submit" class="btn btn-success" value="Deploy File"></td>';
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