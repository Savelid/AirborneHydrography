<?php

$titel = 'Edit System';
include 'res/header.inc.php';
$type = 'add_system';
if (!empty($_GET['system'])) {
	$type = 'update_system';

	include 'res/config.inc.php';
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT *
			FROM system
			LEFT JOIN system_status ON system_status.serial_nr = system.serial_nr
            WHERE system.serial_nr = $_GET[system]";
	$result = $conn->query($sql);
	if (!$result) {
		die("Query failed!");
	}

    $row = $result->fetch_array(MYSQLI_ASSOC);
    $conn->close();
}
$path = 'post.php?type=' . $type;
?>
<?php
function listUnused($name, $serial_nr){
	$name_sn = $name . '_sn';

	if($serial_nr != NULL && $serial_nr != ''){
		echo '<option value="' . $serial_nr . '">' . $serial_nr . '</option>';
	}
	else {
		echo '<option></option>';
	}
	echo '<option>-----</option>';

	// open db
	include 'res/config.inc.php';
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// Add all unused sensor units to the list
	$sql_unused = "  SELECT serial_nr
	          FROM %s
	          WHERE serial_nr NOT IN (
	            SELECT system.%s 
	            FROM system) ";
	$result_unused = $conn->query(sprintf($sql_unused, $name, $name_sn));
		if (!$result_unused) {
			die("Query failed!");
		}
	while($row_unused = $result_unused->fetch_assoc()) {
		echo '<option value="' . $row_unused['serial_nr'] . '">' . $row_unused['serial_nr'] . '</option>';
	}
	$conn->close();
}
?>
<script type="text/javascript">
  $(document).ready(function(){
    $('.combobox').combobox();
  });
</script>
<section class="content">
	
<form action= <?php echo $path ?> method="post" class="form-horizontal">
  <div class="row">
	<div class="col-sm-6 col-sm-offset-1">

	  <div class="col-xs-8 col-xs-offset-4"><h4>System</h4></div>

	  <div class="form-group">
		<label for="serial_nr" class="col-xs-4 control-label">Serial Number</label>
	  	<div class="col-xs-8">
<?php
if (!empty($_GET['system'])) {
	echo '<input type="hidden" name="serial_nr" value="' . $_GET['system'] . '" />'
	. '<input type="text" class="form-control" placeholder="' . $_GET['system'] . '" disabled />';
}else {
	echo '<input type="text" class="form-control" name="serial_nr" required />';
}
?>
	  	</div>
	  </div>

  	  <div class="form-group">
	  	<label for="art_nr" class="col-xs-4 control-label">Art. Number</label>
	  <div class="col-xs-8">
	    <input type="text" class="form-control" name="art_nr" <?= !empty($row['art_nr']) ?  'value="' . $row['art_nr'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="client" class="col-xs-4 control-label">Client</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="client" <?= !empty($row['client']) ?  'value="' . $row['client'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="place" class="col-xs-4 control-label">Place</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="place" <?= !empty($row['place']) ?  'value="' . $row['place'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="config" class="col-xs-4 control-label">Configuration</label>
	  <div class="col-xs-8">
		<select class="form-control" name="config">
		  <option value="Chiroptera" <?= !empty($row['configuration']) && $row['configuration'] == 'Chiroptera' ? 'selected="selected"' : '' ; ?>>Chiroptera</option>
		  <option value="DualDragon" <?= !empty($row['configuration']) && $row['configuration'] == 'DualDragon' ? 'selected="selected"' : '' ; ?>>DualDragon</option>
		  <option value="HawkEyeIII" <?= !empty($row['configuration']) && $row['configuration'] == 'HawkEyeIII' ? 'selected="selected"' : '' ; ?>>HawkEyeIII</option>
		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="sensor_unit" class="col-xs-4 control-label">Sensor Unit</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="sensor_unit">
	  	  
<?php
$sn = '';
if(!empty($row['sensor_unit_sn'])){ $sn = $row['sensor_unit_sn'];}
listUnused('sensor_unit', $sn);
?>

		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="control_system" class="col-xs-4 control-label">Control System</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="control_system">
	  	  
<?php
$sn = '';
if(!empty($row['control_system_sn'])){ $sn = $row['control_system_sn'];}
listUnused('control_system', $sn);
?>

		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="deep_system" class="col-xs-4 control-label">Deep System</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="deep_system">
	  	  
<?php
$sn = '';
if(!empty($row['deep_system_sn'])){ $sn = $row['deep_system_sn'];}
listUnused('deep_system', $sn);
?>

		</select>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="config" class="col-xs-4 control-label">Status</label>
	  <div class="col-xs-8">
		<select class="form-control" name="status">
		  <option value="Done" <?= !empty($row['status']) && $row['status'] == 'Done' ? 'selected="selected"' : '' ; ?>>Done</option>
		  <option value="Service" <?= !empty($row['status']) && $row['status'] == 'Service' ? 'selected="selected"' : '' ; ?>>Service</option>
		  <option value="PIA" <?= !empty($row['status']) && $row['status'] == 'PIA' ? 'selected="selected"' : '' ; ?>>PIA</option>
		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="comment" class="col-xs-4 control-label">Comment</label>
	  <div class="col-xs-8">
  		<textarea class="form-control" name="comment" rows="3"><?= !empty($row['comment']) ? $row['comment'] : ''; ?></textarea>
	  	</div>
	  </div>

	</div>
	<div class="col-sm-3 col-sm-offset-1">

		<h4>System status</h4>

		<div class="checkbox"><label>
		  	<input type="hidden" name="status_potta_heat" value=0 />
			<input type="checkbox" name="status_potta_heat" value=1 <?= !empty($row['status_potta_heat']) && $row['status_potta_heat'] ? 'checked' : ''; ?>/> Potta Heat
		</label></div>

		<div class="checkbox"><label>
			<input type="hidden" name="status_shallow_heat" value=0 />
			<input type="checkbox" name="status_shallow_heat" value=1 <?= !empty($row['status_shallow_heat']) && $row['status_shallow_heat'] ? 'checked' : ''; ?>/> Shallow Heat
		</label></div>

		<div class="checkbox"><label>
			<input type="hidden" name="status_scu_pdu" value=0 />
			<input type="checkbox" name="status_scu_pdu" value=1 <?= !empty($row['status_scu_pdu']) && $row['status_scu_pdu'] ? 'checked' : ''; ?>/> SCU & PDU
		</label></div>

		<div class="checkbox"><label>
			<input type="hidden" name="status_hv_topo" value=0 />
			<input type="checkbox" name="status_hv_topo" value=1 <?= !empty($row['status_hv_topo']) && $row['status_hv_topo'] ? 'checked' : ''; ?>/> HV Card Topo
		</label></div>

		<div class="checkbox"><label>
			<input type="hidden" name="status_hv_shallow" value=0 />
			<input type="checkbox" name="status_hv_shallow" value=1 <?= !empty($row['status_hv_shallow']) && $row['status_hv_shallow'] ? 'checked' : ''; ?>/> HV Card Shallow
		</label></div>

		<div class="checkbox"><label>
			<input type="hidden" name="status_hv_deep" value=0 />
			<input type="checkbox" name="status_hv_deep" value=1 <?= !empty($row['status_hv_deep']) && $row['status_hv_deep'] ? 'checked' : ''; ?>/> HV Card Deep
		</label></div>

		<div class="checkbox"><label>
			<input type="hidden" name="status_cat" value=0 />
			<input type="checkbox" name="status_cat" value=1 <?= !empty($row['status_cat']) && $row['status_cat'] ? 'checked' : ''; ?>/> Cat DC/DC
		</label></div>

		<div class="checkbox"><label>
			<input type="hidden" name="status_pwr_cable" value=0 />
			<input type="checkbox" name="status_pwr_cable" value=1 <?= !empty($row['status_pwr_cable']) && $row['status_pwr_cable'] ? 'checked' : ''; ?>/> Ground Power Cable
		</label></div>

		<h4>Log</h4>

		<div class="form-group col-xs-12">
			<label for="user">User</label>
			<div>
				<input type="text" class="form-control" name="user" required />
			</div>
		</div>

		<div class="form-group col-xs-12">
			<label for="log_comment">Log Comment</label>
			<div>
				<textarea class="form-control" name="log_comment" rows="3"><?= !empty($row['log_comment']) ? $row['log_comment'] : ''; ?></textarea>
			</div>
		</div>
	</div>
  </div>
  <div class="row">
	  <div class="col-sm-12">
	    <button type="submit" class="btn btn-default">Apply</button>
	    <a href="systems.php" class="btn btn-default">Cancel</a>
	    <a href="view_system.php?system=<?php echo $_GET['system']; ?>" class="btn btn-default">Go to System</a>
	  </div>
  </div>
</form>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>