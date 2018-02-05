<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['NewParentTask'])) {
		$Parent_Task_ID=$_GET['Parent_Task_ID'];
        $Child_Task_ID=$_GET['Child_Task_ID'];
        if (!empty($_GET['isRetry'])){
            $isRetry=$_GET['isRetry'];
        } else {
            $isRetry=0;
        }
		include 'components/database.php';
		$sql = "INSERT INTO PARENT_TASKS (Parent_Task_ID,Child_Task_ID,STATUS_ID) VALUES ('$Parent_Task_ID','$Child_Task_ID',$isRetry)";
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
				<h3>PembrokePS Parent Tasks</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Parent Task</th>
                            <th>Child Task</th>
                            <th>isRetry</th>
							<th>date_modified</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
                            $pdo = Database::connect();
                            if (!empty($_GET['Related_Task_ID'])){
                                $Related_Task_ID=$_GET['Related_Task_ID'];
                            } else {
                                $Related_Task_ID="%%";
                            }                           
							$sql = "select pt.ID, "
									    . "pt.Parent_Task_ID, "
									    . "pt.Child_Task_ID, "
									    . "pt.isRetry, "
									    . "pt.date_modified "
                                    . "from PARENT_TASKS pt "                                    
                                    . "join tasks pts on pt.Parent_Task_ID=pts.ID "
                                    . "join tasks ct on pt.Child_Task_ID=ct.ID "
                                    . "where pt.Parent_Task_ID like '$Related_Task_ID' or pt.Child_Task_ID like '$Related_Task_ID'";
							foreach ($pdo->query($sql) as $row) {
								echo '<tr>';
								echo '<td>'. $row['ID'] . '</td>';
								echo '<td>'. $row['Parent_Task_ID'] . '</td>';
								echo '<td>'. $row['Child_Task_ID'] . '</td>';
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