<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<!-- Insert Head PHP -->
<?php
	if (!empty($_GET['NewWmModule'])) {
		$ADDL_MODULE_ID=$_GET['ADDL_MODULE_ID'];
		$WORKFLOW_MANAGER_TYPE_ID=$_GET['WORKFLOW_MANAGER_TYPE_ID'];
		include 'components/database.php';
		$sql = "INSERT INTO ADDL_MODULES_WMAN_GROUPS (ADDL_MODULE_ID,WORKFLOW_MANAGER_TYPE_ID,STATUS_ID) VALUES ('$ADDL_MODULE_ID','$WORKFLOW_MANAGER_TYPE_ID',11)";
		$pdo = Database::connect();
        $pdo->query($sql);
		header("Refresh:0 url=workflow_mgr_module_groups.php");		
	}
	elseif (!empty($_GET['DisableWmModule'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE ADDL_MODULES_WMAN_GROUPS SET STATUS_ID=12 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=workflow_mgr_module_groups.php");		
	}
	elseif (!empty($_GET['EnableWmModule'])) {
		$ID=$_GET['ID'];
		include 'components/database.php';
		$pdo = Database::connect();
		$sql = "UPDATE ADDL_MODULES_WMAN_GROUPS SET STATUS_ID=11 WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=workflow_mgr_module_groups.php");			
	}
	else {
?>
<!-- End Head PHP -->
	<div class="content-area"><!-- Start content-area -->
	<h3>Wman Module Groups</h3>
		<table id="example" class="table table-compact">
			<thead>
				<tr>
				<th>ID</th>
				<th>Module NAME</th>
                <th>Module Version</th>
                <th>Wman Type</th>
                <th>Status</th>
                <th>Action</th>
				<th>date_modified</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($_GET['WORKFLOW_MANAGER_TYPE_ID'])) {
					$WORKFLOW_MANAGER_TYPE_ID=$_GET['WORKFLOW_MANAGER_TYPE_ID'];
				} else {
					$WORKFLOW_MANAGER_TYPE_ID='%%';
				}
				if (!empty($_GET['ADDL_MODULE_ID'])) {
					$ADDL_MODULE_ID=$_GET['ADDL_MODULE_ID'];
				} else {
					$ADDL_MODULE_ID='%%';
				}
				include 'components/database.php';
				$pdo = Database::connect();
				$sql = "select wmad.ID, "
						    . "wmad.ADDL_MODULE_ID, "
						    . "wmad.WORKFLOW_MANAGER_TYPE_ID, "
						    . "wmad.STATUS_ID, "
						    . "wmad.date_modified, "
						    . "wmt.NAME, "
						    . "apm.GALLERY_NAME, "
						    . "apm.MODULE_VERSION, "
						    . "s.STATUS_NAME, "
                            . "s.HTMLCOLOR "            
                        . "from ADDL_MODULES_WMAN_GROUPS wmad "
                        . "join STATUS s on wmad.STATUS_ID=s.ID "
                        . "join WORKFLOW_MANAGER_TYPE wmt on wmad.WORKFLOW_MANAGER_TYPE_ID=wmt.ID "
						. "join ADDITIONAL_PS_MODULES apm on wmad.ADDL_MODULE_ID=apm.ID "
						. "where wmad.WORKFLOW_MANAGER_TYPE_ID like '$WORKFLOW_MANAGER_TYPE_ID' and wmad.ADDL_MODULE_ID like '$ADDL_MODULE_ID'";
				foreach ($pdo->query($sql) as $row) {
					echo '<tr>';
					echo '<td>'. $row['ID'] . '</td>';
					echo '<form action="workflow_mgr_module_groups.php" method="get"><td><input type="hidden" name="ADDL_MODULE_ID" value="' . $row['ADDL_MODULE_ID'] . '"><input type="Submit" class="btn btn-info btn-sm" value="'. $row['GALLERY_NAME'] . '"></td></form>';
					echo '<td>'. $row['MODULE_VERSION'] . '</td>';
					echo '<form action="workflow_mgr_module_groups.php" method="get"><td><input type="hidden" name="WORKFLOW_MANAGER_TYPE_ID" value="' . $row['WORKFLOW_MANAGER_TYPE_ID'] . '"><input type="Submit" class="btn btn-info btn-sm" value="'. $row['NAME'] . '"></td></form>';
					echo '<td style=background-color:'. $row['HTMLCOLOR'] . '><b>'. $row['STATUS_NAME'] . '</b></td>';
					if($row['STATUS_ID'] == 11){
						echo '<form action="workflow_mgr_module_groups.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="DisableWmModule" value="TRUE"><input type="Submit" class="btn btn-warning-outline btn-sm" value="Disable"></td>';
						echo '</form>';
					} else {
						echo '<form action="workflow_mgr_module_groups.php" method="get"><input type="hidden" name="ID" value="' . $row['ID'] . '">';
						echo '<td><input type="hidden" name="EnableWmModule" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Enable"></td>';
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
                <td><b>Add a New Wman Module</b></td>
                <td><b>Module NAME,Version</b></td>
                <td><b>Wman Type</b></td>
                <td><b>Submit</b></td>
            </tr>
            <tr>
                <form>
                    <td><b>Select:</b></td>
					<td>
                        <?php
                            echo "<select name='ADDL_MODULE_ID'>";
                            $sql = "SELECT * FROM ADDITIONAL_PS_MODULES";
                            foreach ($pdo->query($sql) as $row) {
                                echo "<option value=". $row['ID'] .">". $row['GALLERY_NAME'] .",". $row['MODULE_VERSION'] ."</option>";
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
						<input type="hidden" name="NewWmModule" value="TRUE"><input type="Submit" class="btn btn-success-outline btn-sm" value="Add Wman Module"></td>
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