<!DOCTYPE html>
<html lang="en">
<?php
	require_once 'components/header.php';
?>
<?php
	if (!empty($_GET['UpdateQMType'])) {
        include 'components/database.php';
        $ID=$_GET['ID'];
        $NAME=$_GET['NAME'];
		$TABLENAME=$_GET['TABLENAME'];
		$pdo = Database::connect();
		$sql = "UPDATE QUEUE_MANAGER_TYPE SET NAME='$NAME',TABLENAME='$TABLENAME' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=manager_types.php");
    } 
    elseif (!empty($_GET['NewQueueMgrType'])) {
        include 'components/database.php';
        $NAME=$_GET['NAME'];
		$TABLENAME=$_GET['TABLENAME'];
		$sql = "INSERT INTO QUEUE_MANAGER_TYPE (NAME,TABLENAME) VALUES ('$NAME','$TABLENAME')";
		$pdo = Database::connect();
        $pdo->query($sql);
        header("Refresh:0 url=manager_types.php");
    }
	elseif (!empty($_GET['UpdateWMType'])) {
        include 'components/database.php';
        $ID=$_GET['ID'];
        $NAME=$_GET['NAME'];
		$TABLENAME=$_GET['TABLENAME'];
		$pdo = Database::connect();
		$sql = "UPDATE WORKFLOW_MANAGER_TYPE SET NAME='$NAME',TABLENAME='$TABLENAME' WHERE ID=$ID";
		$pdo->query($sql);
		header("Refresh:0 url=manager_types.php");
    } 
    elseif (!empty($_GET['NewWorkflowMgrType'])) {
        include 'components/database.php';
        $NAME=$_GET['NAME'];
		$TABLENAME=$_GET['TABLENAME'];
		$sql = "INSERT INTO WORKFLOW_MANAGER_TYPE (NAME,TABLENAME) VALUES ('$NAME','$TABLENAME')";
		$pdo = Database::connect();
        $pdo->query($sql);
        header("Refresh:0 url=manager_types.php");
    }
	else {

?>
<script> 
	$(document).ready(function() {
  		$('#example').dataTable();
	});
</script>
<script> 
	$(document).ready(function() {
  		$('#example2').dataTable();
	});
</script>
<body>
    <div class="container" style="margin-left:10px">
    	<div class="row">
			<?php
				require_once 'components/Side_Bar.html';
			?>
			<div class="col-sm-9 col-md-10 col-lg-10 main">
				<h3>Manager Types</h3>
				<div class="row">
					<table id="example" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
							<th>TABLENAME</th>
							<th>Update Info</th>
							<th>date_modified</th>
							<th>Managers</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							include 'components/database.php';
							$pdo = Database::connect();
							$sql = 'select qmt.ID, '
									. 'qmt.NAME, '
									. 'qmt.TABLENAME, '
									. 'qmt.date_modified '
									. 'from QUEUE_MANAGER_TYPE qmt '; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr><form action="manager_types.php" method="get"><input type="hidden" name="UpdateQMType" value="TRUE">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="Name" value="'. $row['Name'] . '"></td>';
								echo '<td><input type="text" name="TABLENAME" value="'. $row['TABLENAME'] . '"></td>';
								echo '<td><input type="submit" class="btn btn-success" value="UPDATE Data"></form></td>';
								echo '<td>'. $row['date_modified'] . '</td>';
                                echo '<td><form action="queue_manager.php" method="get"><input type="hidden" name="QUEUE_MANAGER_TYPE_ID" value="' . $row['ID'] . '"><input type="submit" class="btn btn-info" value="View Qmanagers"></form></td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
                    </table>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <form>
                                <td><b>Add a New Queue Manager Type</b></td>
                                <td>
									<input type="text" name="Name" value="Enter a Name">
								</td>
								<td>
									<input type="text" name="TABLENAME" value="Enter a TABLENAME">
								</td>
								<td>
									<input type="hidden" name="NewQueueMgrType" value="TRUE"><input type="submit" class="btn btn-success" value="Add Queue Manage Type"></td>
								</td>
							</form>
						</tr>
                    </table>
                    <table id="example2" class="table table-striped table-bordered">
						<thead>
							<tr>
							<th>ID</th>
							<th>Name</th>
							<th>TABLENAME</th>
							<th>Update Info</th>
							<th>date_modified</th>
							<th>Managers</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$pdo = Database::connect();
							$sql = 'select wmt.ID, '
									. 'wmt.NAME, '
									. 'wmt.TABLENAME, '
									. 'wmt.date_modified '
									. 'from WORKFLOW_MANAGER_TYPE wmt '; 
							foreach ($pdo->query($sql) as $row) {
								echo '<tr><form action="manager_types.php" method="get"><input type="hidden" name="UpdateWMType" value="TRUE">';
								echo '<td><input type="hidden" name="ID" value="' . $row['ID'] . '">'. $row['ID'] . '</td>';
								echo '<td><input type="text" name="NAME" value="'. $row['NAME'] . '"></td>';
								echo '<td><input type="text" name="TABLENAME" value="'. $row['TABLENAME'] . '"></td>';
								echo '<td><input type="submit" class="btn btn-success" value="UPDATE Data"></form></td>';
								echo '<td>'. $row['date_modified'] . '</td>';
                                echo '<td><form action="workflow_manager.php" method="get"><input type="hidden" name="WORKFLOW_MANAGER_TYPE_ID" value="' . $row['ID'] . '"><input type="submit" class="btn btn-info" value="View WKFLmanagers"></form></td>';
								echo '</tr>';
							}
							Database::disconnect();
							?>
						</tbody>
                    </table>
                    <table class="table table-striped table-bordered">
                        <tr>
                            <form>
                                <td><b>Add a New Workflow Manager Type</b></td>
                                <td>
									<input type="text" name="NAME" value="Enter a Name">
								</td>
								<td>
									<input type="text" name="TABLENAME" value="Enter a TABLENAME">
								</td>
								<td>
									<input type="hidden" name="NewWorkflowMgrType" value="TRUE"><input type="submit" class="btn btn-success" value="Add Workflow Manage Type"></td>
								</td>
							</form>
						</tr>
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