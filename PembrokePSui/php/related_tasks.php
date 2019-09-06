<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
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
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Related Tasks</h3>
		<table id="example" class="table table-compact">
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
					echo '<form action="tasks.php" method="get"><td><input type="hidden" name="PARENT_TASK_ID" value="' . $row['PARENT_TASK_ID'] . '"><input type="Submit" class="btn btn-info btn-sm" value="'. $row['PARENT_TASK_ID'] . '"></td></form>';
					echo '<form action="tasks.php" method="get"><td><input type="hidden" name="CHILD_TASK_ID" value="' . $row['CHILD_TASK_ID'] . '"><input type="Submit" class="btn btn-info btn-sm" value="'. $row['CHILD_TASK_ID'] . '"></td></form>';
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