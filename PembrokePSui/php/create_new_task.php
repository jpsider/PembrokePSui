<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (empty($_GET['TARGET_ID'])) {
		header("Refresh:0 url=targets.php");		
	}
	elseif (!empty($_GET['CreateNewTASK'])) {
		$TARGET_ID=$_GET['TARGET_ID'];
		$TASK_TYPE_ID=$_GET['TASK_TYPE_ID'];
        if(!empty($_GET['ARGUMENTS'])){
            $ARGUMENTS=$_GET['ARGUMENTS'];
        } else {
            $ARGUMENTS="null";
        }
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "INSERT INTO TASKS (STATUS_ID,RESULT_ID,TASK_TYPE_ID,LOG_FILE,WORKFLOW_MANAGER_ID,TARGET_ID,ARGUMENTS,HIDDEN) VALUES (5,6,'$TASK_TYPE_ID','no-log',9999,'$TARGET_ID','$ARGUMENTS',0)";
        $pdo->query($sql);
        echo $sql;
		header("Refresh:0 url=tasks.php?TARGET_ID=$TARGET_ID");		
	}
	else {

?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
		<?php
            $TARGET_NAME=$_GET['TARGET_NAME'];
            echo '<h3>Create New TASK for Target: <b>'. $TARGET_NAME .'</b></h3>';
        ?>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Target Type</th>
                <th>TASK NAME</th>
                <th>ARGUMENTS</th>
                <th>Submit</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
                <?php
                if (!empty($_GET['TARGET_TYPE_ID'])) {
                    $TARGET_TYPE_ID=$_GET['TARGET_TYPE_ID'];
                } else {
                    $TARGET_TYPE_ID='%%';
                }
                // Get the Target ID
                $TARGET_ID=$_GET['TARGET_ID'];
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = "select ttt.ID, "
						    . "ttt.TARGET_TYPE_ID, "
						    . "ttt.TASK_TYPE_ID, "
						    . "ttt.STATUS_ID, "
						    . "ttt.MAX_Retries, "
						    . "ttt.date_modified, "
						    . "tt.NAME, "
						    . "tts.TASK_NAME "                       
                        . "from TARGET_TASKS_TYPES ttt "                            
                        . "join TARGET_TYPES tt on ttt.TARGET_TYPE_ID=tt.ID "
                        . "join TASK_TYPES tts on ttt.TASK_TYPE_ID=tts.ID "
                        . "where ttt.TARGET_TYPE_ID like '$TARGET_TYPE_ID' and ttt.STATUS_ID=11 ";
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<td>'. $row['NAME'] . '</td>';
					echo '<td>'. $row['TASK_NAME'] . '</td>';
					echo '<td><input type="text" name="ARGUMENTS" value=""></td>';
					echo '<form action="create_new_task.php" method="get"><input type="hidden" name="TASK_TYPE_ID" value="' . $row['TASK_TYPE_ID'] . '"><input type="hidden" name="TARGET_ID" value="' . $TARGET_ID . '">';
					echo '<td><input type="hidden" name="CreateNewTASK" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Create TASK"></td>';
					echo '</form>';                              
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