<?php
session_start();
$titel = 'Edit Sensor';
include 'res/header.inc.php';
$type = 'add_sensor';
if (!empty($_GET['serial_nr'])) {
	$type = 'update_sensor';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	//
	$sn = $_GET['serial_nr'];
	$sql = "SELECT *
			FROM sensor
			WHERE serial_nr = $sn";
	$result = $conn->query($sql);
	if (!$result) {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
		die();
	}

    $row = $result->fetch_array(MYSQLI_ASSOC);
    $conn->close();
}
$path = 'post.php?type=' . $type; // path for form
?>
<?php require_once('res/functions.inc.php'); ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('.combobox').combobox();
  });
</script>
<section class="content">
	
<form action= <?php echo htmlspecialchars($path); ?> method="post" class="form-horizontal">
  <div class="row">
	<div class="col-sm-6 col-sm-offset-1">

	<div class="col-xs-8 col-xs-offset-4"><h4>Sensor</h4></div>

	  <div class="form-group">
		<label for="serial_nr" class="col-xs-4 control-label">Serial Number</label>
	  	<div class="col-xs-8">
<?php
if (!empty($_GET['serial_nr'])) {
	echo '<input type="hidden" name="serial_nr" value="' . $_GET['serial_nr'] . '" />'
	. '<input type="text" class="form-control" placeholder="' . $_GET['serial_nr'] . '" disabled />';
}else {
	echo '<input type="text" class="form-control" name="serial_nr" required/>';
}
?>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="sensor_type" class="col-xs-4 control-label">Configuration</label>
	  <div class="col-xs-8">
		<select class="form-control" name="sensor_type" id="sensor_type">
		  <option value="topo" <?= !empty($row['sensor_type']) && $row['sensor_type'] == 'topo' ? 'selected="selected"' : '' ; ?>>Topo</option>
		  <option value="shallow" <?= !empty($row['sensor_type']) && $row['sensor_type'] == 'shallow' ? 'selected="selected"' : '' ; ?>>Shallow</option>
		  <option value="deep" <?= !empty($row['sensor_type']) && $row['sensor_type'] == 'deep' ? 'selected="selected"' : '' ; ?>>Deep</option>
		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="cat" class="col-xs-4 control-label">CAT</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="cat" <?= !empty($row['cat']) ?  'value="' . $row['cat'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="fpga_id" class="col-xs-4 control-label">FPGA ID</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="fpga_id" <?= !empty($row['fpga_id']) ?  'value="' . $row['fpga_id'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="laser" class="col-xs-4 control-label">Laser</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="laser">
	  	  
<?php
$sn = '';
if(!empty($row['laser_sn'])){ $sn = $row['laser_sn'];}
listUnusedSerialNr('laser', 'serial_nr NOT IN
	(SELECT sensor.laser_sn FROM sensor)', $sn);
?>

		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="hv_card" class="col-xs-4 control-label">HV Card</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="hv_card">
	  	  
<?php
$sn = '';
if(!empty($row['hv_card_sn'])){ $sn = $row['hv_card_sn'];}
listUnusedSerialNr('hv_card', 'serial_nr NOT IN
	(SELECT sensor.hv_card_sn FROM sensor)
	AND serial_nr NOT IN
	(SELECT sensor.hv_card_2_sn FROM sensor)', $sn);
?>

		</select>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="receiver_unit" class="col-xs-4 control-label">Receiver Unit</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="receiver_unit">
	  	  
<?php
$sn = '';
if(!empty($row['receiver_unit_sn'])){ $sn = $row['receiver_unit_sn'];}
listUnusedSerialNr('receiver_unit', 'serial_nr NOT IN
	(SELECT sensor.receiver_unit_sn FROM sensor)
	AND serial_nr NOT IN
	(SELECT sensor.receiver_unit_2_sn FROM sensor)', $sn);
?>

		</select>
	  	</div>
	  </div>

<!-- Hide extra deep data if the sensor is not a deep sensor -->
<script> 
$(function() {
    if($('#sensor_type').val() != 'deep') {
    	$('.deepSensor').hide();
    }
    $('#sensor_type').change(function(){
        if($('#sensor_type').val() == 'deep') {
            $('.deepSensor').show(); 
        } else {
            $('.deepSensor').hide(); 
        } 
    });
});
</script>

	  <div class="form-group deepSensor">
		<label for="hv_card_2" class="col-xs-4 control-label">HV Card 2</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="hv_card_2">
	  	  
<?php
$sn = '';
if(!empty($row['hv_card_2_sn'])){ $sn = $row['hv_card_2_sn'];}
listUnusedSerialNr('hv_card', 'serial_nr NOT IN
	(SELECT sensor.hv_card_sn FROM sensor)
	AND serial_nr NOT IN
	(SELECT sensor.hv_card_2_sn FROM sensor)', $sn);
?>

		</select>
	  	</div>
	  </div>

	  <div class="form-group deepSensor">
		<label for="receiver_unit_2" class="col-xs-4 control-label">Receiver Unit 2</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="receiver_unit_2">
	  	  
<?php
$sn = '';
if(!empty($row['receiver_unit_2_sn'])){ $sn = $row['receiver_unit_2_sn'];}
listUnusedSerialNr('receiver_unit', 'serial_nr NOT IN 
	(SELECT sensor.receiver_unit_sn FROM sensor)
	AND serial_nr NOT IN
	(SELECT sensor.receiver_unit_2_sn FROM sensor)', $sn);
?>

		</select>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="config" class="col-xs-4 control-label">Status</label>
	  <div class="col-xs-8">
		<select class="form-control" name="status">
<?php
foreach($sensor_status_values as $i){
	$selected = '';
	if(!empty($row['status']) && $row['status'] == $i){$selected = 'selected';}
	$s = '<option value="%s" %s>%s</option>';
	echo sprintf($s, $i, $selected, $i);
}
?>
		</select>
	  	</div>
	  </div>
	  
	</div> <!-- end col -->

	<div class="col-sm-3 col-sm-offset-1">
	  <h4>Pulse Values</h4>

	  <div class="form-group col-xs-12">
		<label for="dps_value_input_offset_t0">Input Offset t0</label>
	  <div>
	  	<input type="text" class="form-control" name="dps_value_input_offset_t0" <?= !empty($row['dps_value_input_offset_t0']) ?  'value="' . $row['dps_value_input_offset_t0'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group col-xs-12">
		<label for="dps_value_input_offset_rec">Input Offset Rec</label>
	  <div>
	  	<input type="text" class="form-control" name="dps_value_input_offset_rec" <?= !empty($row['dps_value_input_offset_rec']) ?  'value="' . $row['dps_value_input_offset_rec'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group col-xs-12">
		<label for="dps_value_pulse_width_t0">Pulse Width t0</label>
	  <div>
	  	<input type="text" class="form-control" name="dps_value_pulse_width_t0" <?= !empty($row['dps_value_pulse_width_t0']) ?  'value="' . $row['dps_value_pulse_width_t0'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group col-xs-12">
		<label for="dps_value_pulse_width_rec">Pulse Width Rec</label>
	  <div>
	  	<input type="text" class="form-control" name="dps_value_pulse_width_rec" <?= !empty($row['dps_value_pulse_width_rec']) ?  'value="' . $row['dps_value_pulse_width_rec'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <h4>Log</h4>
		<div class="form-group col-xs-12">
			<label for="user">User</label>
			<div>
				<input type="text" class="form-control" name="user" <?= !empty($_SESSION['user']) ? 'value="' . $_SESSION['user'] . '"' : ''; ?> required />
			</div>
		</div>

		<div class="form-group col-xs-12">
			<label for="log_comment">Log Comment</label>
			<div>
				<textarea class="form-control" name="log_comment" rows="3"></textarea>
			</div>
		</div>
	</div>

  </div><!-- end row -->
  <div class="row">
	  <div class="col-sm-12">
	    <button type="submit" class="btn btn-default">Apply</button>
	    <a href="parts.php" class="btn btn-default">Cancel</a>
	    <a href="view_sensor.php?serial_nr=<?php echo $_GET['serial_nr']; ?>" class="btn btn-default">Go to Sensor</a>
	  </div>
  </div>
</form>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>