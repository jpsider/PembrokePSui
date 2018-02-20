<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewParentTASK'])) {
		$PARENT_TASK_ID=$_GET['PARENT_TASK_ID'];
        $CHILD_TASK_ID=$_GET['CHILD_TASK_ID'];
        if (!empty($_GET['isRetry'])){
            $isRetry=$_GET['isRetry'];
        } else {
            $isRetry=0;
        }
		include 'components/database.php';
		$sql = "INSERT INTO PARENT_TASKS (PARENT_TASK_ID,CHILD_TASK_ID,STATUS_ID) VALUES ('$PARENT_TASK_ID','$CHILD_TASK_ID',$isRetry)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=parent_target_types.php");		
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
				<h3>Related TASKs</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Parent TASK ID</th>
                            <th>Child TASK ID</th>
                            <th>isRetry</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
                            $pdo = Database::connect();
                            if (!empty($_GET['Related_TASK_ID'])){
                                $Related_TASK_ID=$_GET['Related_TASK_ID'];
                            } else {
                                $Related_TASK_ID="%%";
                            }                           
							$sql = "select pt.ID, "
									    . "pt.PARENT_TASK_ID, "
									    . "pt.CHILD_TASK_ID, "
									    . "pt.isRetry, "
									    . "pt.date_modified "
                                    . "from PARENT_TASKS pt "                                    
                                    . "join TASKS pts on pt.PARENT_TASK_ID=pts.ID "
                                    . "join TASKS ct on pt.CHILD_TASK_ID=ct.ID "
                                    . "where pt.PARENT_TASK_ID like '$Related_TASK_ID' or pt.CHILD_TASK_ID like '$Related_TASK_ID'";
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td><form action="tasks.php" method="get"><input type="hidden" name="PARENT_TASK_ID" value="' . $row['PARENT_TASK_ID'] . '"><input type="Submit" class="btn btn-info" value="'. $row['PARENT_TASK_ID'] . '"></form></td>';
								echo '<td><form action="tasks.php" method="get"><input type="hidden" name="CHILD_TASK_ID" value="' . $row['CHILD_TASK_ID'] . '"><input type="Submit" class="btn btn-info" value="'. $row['CHILD_TASK_ID'] . '"></form></td>';
								if($row['isRetry'] == 0){
									echo '<td>False</td>';
								} else {
									echo '<td>True</td>';
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