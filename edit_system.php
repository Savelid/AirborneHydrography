<?php

$titel = 'Edit System';
include 'res/header.inc.php';
$type = 'add_system';
if (!empty($_GET['system'])) {
	$type = 'update_system';

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
<?php require_once('res/functions.inc.php'); ?>
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
		<select class="form-control" name="configuration">
<?php
foreach($configuration_values as $i){
	$selected = '';
	if(!empty($row['configuration']) && $row['configuration'] == $i){$selected = 'selected';}
	$s = '<option value="%s" %s>%s</option>';
	echo sprintf($s, $i, $selected, $i);
}
?>
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
listUnusedSerialNr('sensor_unit', '	serial_nr NOT IN (
	            			SELECT system.sensor_unit_sn
	            			FROM system)'	, $sn);
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
listUnusedSerialNr('control_system', '	serial_nr NOT IN (
	            			SELECT system.control_system_sn
	            			FROM system)'	, $sn);
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
listUnusedSerialNr('deep_system', '	serial_nr NOT IN (
	            			SELECT system.deep_system_sn
	            			FROM system)'	, $sn);
?>
		</select>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="oc60_1" class="col-xs-4 control-label">OC60 1</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="oc60_1" <?= !empty($row['oc60_1']) ?  'value="' . $row['oc60_1'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="firmware_oc60_1" class="col-xs-4 control-label">OC60 1 firmware</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="firmware_oc60_1" <?= !empty($row['firmware_oc60_1']) ?  'value="' . $row['firmware_oc60_1'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="oc60_2" class="col-xs-4 control-label">OC60 2</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="oc60_2" <?= !empty($row['oc60_2']) ?  'value="' . $row['oc60_2'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="firmware_oc60_2" class="col-xs-4 control-label">OC60 2 firmware</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="firmware_oc60_2" <?= !empty($row['firmware_oc60_2']) ?  'value="' . $row['firmware_oc60_2'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="pav" class="col-xs-4 control-label">PAV</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="pav" <?= !empty($row['pav']) ?  'value="' . $row['pav'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="firmware_pav" class="col-xs-4 control-label">PAV firmware</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="firmware_pav" <?= !empty($row['firmware_pav']) ?  'value="' . $row['firmware_pav'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="config" class="col-xs-4 control-label">Status</label>
	  <div class="col-xs-8">
		<select class="form-control" name="status">
<?php
foreach($system_status_values as $i){
	$selected = '';
	if(!empty($row['status']) && $row['status'] == $i){$selected = 'selected';}
	$s = '<option value="%s" %s>%s</option>';
	echo sprintf($s, $i, $selected, $i);
}
?>
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