<?php

$titel = 'Edit Sensor';
include 'res/header.inc.php';
$type = 'add_sensor';
if (!empty($_GET['serial_nr'])) {
	$type = 'update_sensor';

	// Create connection
	include 'res/config.inc.php';
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
<?php
function listUnused($tablename, $address, $serial_nr){

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
	            SELECT %s 
	            FROM sensor) ";
	$result_unused = $conn->query(sprintf($sql_unused, $tablename, $address));
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
if (!empty($_GET['serial_nr'])) {
	echo '<input type="hidden" name="serial_nr" value="' . $_GET['serial_nr'] . '" />'
	. '<input type="text" class="form-control" placeholder="' . $_GET['serial_nr'] . '" disabled />';
}else {
	echo '<input type="text" class="form-control" name="serial_nr" />';
}
?>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="sensor_type" class="col-xs-5 control-label">Configuration</label>
	  <div class="col-xs-7">
		<select class="form-control" name="sensor_type" id="sensor_type">
		  <option value="topo" <?= !empty($row['sensor_type']) && $row['sensor_type'] == 'topo' ? 'selected="selected"' : '' ; ?>>Topo</option>
		  <option value="shallow" <?= !empty($row['sensor_type']) && $row['sensor_type'] == 'shallow' ? 'selected="selected"' : '' ; ?>>Shallow</option>
		  <option value="deep" <?= !empty($row['sensor_type']) && $row['sensor_type'] == 'deep' ? 'selected="selected"' : '' ; ?>>Deep</option>
		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="cat" class="col-xs-5 control-label">CAT</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="cat" <?= !empty($row['cat']) ?  'value="' . $row['cat'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="fpga_id" class="col-xs-5 control-label">FPGA ID</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="fpga_id" <?= !empty($row['fpga_id']) ?  'value="' . $row['fpga_id'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="laser" class="col-xs-5 control-label">Laser</label>
	  <div class="col-xs-7">
	  	<select class="combobox form-control" name="laser">
	  	  
<?php
$sn = '';
if(!empty($row['laser_sn'])){ $sn = $row['laser_sn'];}
listUnused('laser', 'sensor.laser_sn', $sn);
?>

		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="hv_card" class="col-xs-5 control-label">HV Card</label>
	  <div class="col-xs-7">
	  	<select class="combobox form-control" name="hv_card">
	  	  
<?php
$sn = '';
if(!empty($row['hv_card_sn'])){ $sn = $row['hv_card_sn'];}
listUnused('hv_card', 'sensor.hv_card_sn FROM sensor)
						AND serial_nr NOT IN (
	            		SELECT sensor.hv_card_2_sn', $sn); // workaround to be able to use the same function for something used under 2 names
?>

		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="receiver_unit" class="col-xs-5 control-label">Receiver Unit</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="receiver_unit" <?= !empty($row['receiver_unit']) ?  'value="' . $row['receiver_unit'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="receiver_chip" class="col-xs-5 control-label">Receiver Chip</label>
	  <div class="col-xs-7">
	  	<select class="combobox form-control" name="receiver_chip">
	  	  
<?php
$sn = '';
if(!empty($row['receiver_chip_sn'])){ $sn = $row['receiver_chip_sn'];}
listUnused('hv_card', 'sensor.receiver_chip_sn FROM sensor)
						AND serial_nr NOT IN (
	            		SELECT sensor.receiver_chip_2_sn', $sn); // workaround to be able to use the same function for something used under 2 names
?>

		</select>
	  	</div>
	  </div>

<!-- Hide extra deep data if the sensor is not a deep sensor -->
<script> 
$(function() {
    $('.deepSensor').hide(); 
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
		<label for="hv_card_2" class="col-xs-5 control-label">HV Card 2</label>
	  <div class="col-xs-7">
	  	<select class="combobox form-control" name="hv_card_2">
	  	  
<?php
$sn = '';
if(!empty($row['hv_card_2_sn'])){ $sn = $row['hv_card_2_sn'];}
listUnused('hv_card', 'sensor.hv_card_sn FROM sensor)
						AND serial_nr NOT IN (
	            		SELECT sensor.hv_card_2_sn', $sn); // workaround to be able to use the same function for something used under 2 names
?>

		</select>
	  	</div>
	  </div>

  	  <div class="form-group deepSensor">
		<label for="receiver_unit_2" class="col-xs-5 control-label">Receiver Unit 2</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="receiver_unit_2" <?= !empty($row['receiver_unit_2']) ?  'value="' . $row['receiver_unit_2'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group deepSensor">
		<label for="receiver_chip_2" class="col-xs-5 control-label">Receiver Chip 2</label>
	  <div class="col-xs-7">
	  	<select class="combobox form-control" name="receiver_chip_2">
	  	  
<?php
$sn = '';
if(!empty($row['receiver_chip_2_sn'])){ $sn = $row['receiver_chip_2_sn'];}
listUnused('hv_card', 'sensor.receiver_chip_sn FROM sensor)
						AND serial_nr NOT IN (
	            		SELECT sensor.receiver_chip_2_sn', $sn); // workaround to be able to use the same function for something used under 2 names
?>

		</select>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="dps_value_input_offset_t0" class="col-xs-5 control-label">dps_value_input_offset_t0</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="dps_value_input_offset_t0" <?= !empty($row['dps_value_input_offset_t0']) ?  'value="' . $row['dps_value_input_offset_t0'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="dps_value_input_offset_rec" class="col-xs-5 control-label">dps_value_input_offset_rec</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="dps_value_input_offset_rec" <?= !empty($row['dps_value_input_offset_rec']) ?  'value="' . $row['dps_value_input_offset_rec'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="dps_value_pulse_width_t0" class="col-xs-5 control-label">dps_value_pulse_width_t0</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="dps_value_pulse_width_t0" <?= !empty($row['dps_value_pulse_width_t0']) ?  'value="' . $row['dps_value_pulse_width_t0'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="dps_value_pulse_width_rec" class="col-xs-5 control-label">dps_value_pulse_width_rec</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="dps_value_pulse_width_rec" <?= !empty($row['dps_value_pulse_width_rec']) ?  'value="' . $row['dps_value_pulse_width_rec'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="status" class="col-xs-5 control-label">Status</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="status" <?= !empty($row['status']) ?  'value="' . $row['status'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	</div> <!-- end col sm8 -->

  </div><!-- end row -->
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