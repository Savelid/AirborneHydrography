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

	//
	$sn = $_GET['system'];
	$sql = "SELECT serial_nr, art_nr, client, configuration, sensor_unit_sn, control_unit_sn, deep_system_sn, cooling_system, comment,
			status_potta_heat, status_shallow_heat, status_scu_pdu, status_hv_topo, status_hv_shallow, status_hv_deep, status_cat, status_pwr_cable
			FROM system
			WHERE serial_nr = $sn";
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
		if(isset($_GET[$name_sn]) && $_GET[$name_sn] == $row_unused['serial_nr']){
			echo '<option value="' . $row_unused['serial_nr'] . '" autofocus selected="selected">' . $row_unused['serial_nr'] . '</option>';
		}else {
			echo '<option value="' . $row_unused['serial_nr'] . '">' . $row_unused['serial_nr'] . '</option>';
		}
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
	<div class="col-sm-8">

	  <div class="form-group">
		<label for="serial_nr" class="col-xs-5 control-label">Serial Number</label>
	  	<div class="col-xs-7">
<?php
if (!empty($_GET['system'])) {
	echo '<input type="hidden" name="serial_nr" value="' . $_GET['system'] . '" />'
	. '<input type="text" class="form-control" placeholder="' . $_GET['system'] . '" disabled />';
}else {
	echo '<input type="text" class="form-control" name="serial_nr" />';
}
?>
	  	</div>
	  </div>

  	  <div class="form-group">
	  	<label for="art_nr" class="col-xs-5 control-label">Art. Number</label>
	  <div class="col-xs-7">
	    <input type="text" class="form-control" name="art_nr" <?= !empty($row['art_nr']) ?  'value="' . $row['art_nr'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="client" class="col-xs-5 control-label">Client</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="client" <?= !empty($row['client']) ?  'value="' . $row['client'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="place" class="col-xs-5 control-label">Place</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="place" <?= !empty($row['place']) ?  'value="' . $row['place'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="config" class="col-xs-5 control-label">Configuration</label>
	  <div class="col-xs-7">
		<select class="form-control" name="config">
		  <option value="Chiroptera" <?= !empty($row['configuration']) && $row['configuration'] == 'Chiroptera' ? 'selected="selected"' : '' ; ?>>Chiroptera</option>
		  <option value="DualDragon" <?= !empty($row['configuration']) && $row['configuration'] == 'DualDragon' ? 'selected="selected"' : '' ; ?>>DualDragon</option>
		  <option value="HawkEyeIII" <?= !empty($row['configuration']) && $row['configuration'] == 'HawkEyeIII' ? 'selected="selected"' : '' ; ?>>HawkEyeIII</option>
		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="sensor_unit" class="col-xs-5 control-label">Sensor Unit</label>
	  <div class="col-xs-7">
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
		<label for="control_unit" class="col-xs-5 control-label">Control Unit</label>
	  <div class="col-xs-7">
	  	<select class="combobox form-control" name="control_unit">
	  	  
<?php
$sn = '';
if(!empty($row['control_unit_sn'])){ $sn = $row['control_unit_sn'];}
listUnused('control_unit', $sn);
?>

		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="deep_system" class="col-xs-5 control-label">Deep System</label>
	  <div class="col-xs-7">
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
		<label for="cooling_system" class="col-xs-5 control-label">Cooling System</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="cooling_system" <?= !empty($row['cooling_system']) ?  'value="' . $row['cooling_system'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="comment" class="col-xs-5 control-label">Comment</label>
	  <div class="col-xs-7">
  		<textarea class="form-control" name="comment" rows="3"><?= !empty($row['comment']) ? $row['comment'] : ''; ?></textarea>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="user" class="col-xs-5 control-label">User</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="user" required />
	  	</div>
	  </div>

	</div>
	<div class="col-sm-4">

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

	</div>
  </div>
  <div class="row">
	  <div class="col-sm-offset-7 col-sm-5">
	    <button type="submit" class="btn btn-default">Apply</button>
	  </div>
  </div>
</form>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>