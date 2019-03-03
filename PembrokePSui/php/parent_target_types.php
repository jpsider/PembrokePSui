<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewParentTargetType'])) {
		$PARENT_TARGET_TYPE_ID=$_GET['PARENT_TARGET_TYPE_ID'];
		$CHILD_TARGET_TYPE_ID=$_GET['CHILD_TARGET_TYPE_ID'];
		include 'components/database.php';
		$sql = "INSERT INTO PARENT_TARGET_TYPES (PARENT_TARGET_TYPE_ID,CHILD_TARGET_TYPE_ID,STATUS_ID) VALUES ('$PARENT_TARGET_TYPE_ID','$CHILD_TARGET_TYPE_ID',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=parent_target_types.php");		
	}
	elseif (!empty($_GET['DisableParentTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE PARENT_TARGET_TYPES SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=parent_target_types.php");		
	}
	elseif (!empty($_GET['EnableParentTargetType'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE PARENT_TARGET_TYPES SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=parent_target_types.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Parent Target Type</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Parent Target Type</th>
                <th>Child Target Type</th>
                <th>Status</th>
                <th>Action</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = 'select ptt.ID, '
						    . 'ptt.PARENT_TARGET_TYPE_ID, '
						    . 'ptt.CHILD_TARGET_TYPE_ID, '
						    . 'ptt.STATUS_ID, '
						    . 'ptt.date_modified, '
						    . 'pt.NAME as PARENT_NAME, '
						    . 'ct.NAME as CHILD_NAME, '
						    . 's.STATUS_NAME, '
                            . 's.HTMLCOLOR '                                    
                        . 'from PARENT_TARGET_TYPES ptt '                                    
                        . 'join STATUS s on ptt.STATUS_ID=s.ID ' 
                        . 'join TARGET_TYPES pt on ptt.PARENT_TARGET_TYPE_ID=pt.ID '
                        . 'join TARGET_TYPES ct on ptt.CHILD_TARGET_TYPE_ID=ct.ID ';
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<td>'. $row['PARENT_NAME'] . '</td>';
					echo '<td>'. $row['CHILD_NAME'] . '</td>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="parent_target_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableParentTargetType" value="TRUE"><input type="Submit" class="btn btn-danger" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="parent_target_types.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableParentTargetType" value="TRUE"><input type="Submit" class="btn btn-success" value="Enable"></td>';
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
                <td><b>Add a New Parent Target</b></td>
                <td><b>Parent Type</b></td>
                <td><b>Child Type</b></td>
                <td><b>Submit</b></td>
            </tr>
            <tr>
                <form>
                    <td><b>Select:</b></td>
					<td>
                        <?php
                            echo "<select name='PARENT_TARGET_TYPE_ID'>";
                            $sql = "SELECT * FROM TARGET_TYPES";
                            foreach ($pdo->query($sql) as $row) {
                                echo "<option value=". $row['ID'] .">". $row['NAME'] ."</option>";
                            }
                            echo "</select>"
                        ?>
                    </td>
					<td>
                        <?php
                            echo "<select name='CHILD_TARGET_TYPE_ID'>";
                            $sql = "SELECT * FROM TARGET_TYPES";
                            foreach ($pdo->query($sql) as $row) {
                                echo "<option value=". $row['ID'] .">". $row['NAME'] ."</option>";
                            }
                            echo "</select>"
                        ?>
                    </td>
					<td>
						<input type="hidden" name="NewParentTargetType" value="TRUE"><input type="Submit" class="btn btn-success" value="Add Parent Target Type"></td>
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