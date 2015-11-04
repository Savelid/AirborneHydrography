<?php

$titel = 'Edit Sensor Unit';
include 'res/header.inc.php';
$type = 'add_sensor_unit';
if (!empty($_GET['serial_nr'])) {
	$type = 'update_sensor_unit';

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
			FROM sensor_unit
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
function listUnused($from, $where, $serial_nr){

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
	          WHERE %s";
	$result_unused = $conn->query(sprintf($sql_unused, $from, $where));
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
		<label for="imu" class="col-xs-5 control-label">IMU</label>
	  <div class="col-xs-7">
	  	<input type="text" class="form-control" name="imu" <?= !empty($row['imu']) ?  'value="' . $row['imu'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="leica_cam" class="col-xs-5 control-label">Leica Camera</label>
	  <div class="col-xs-7">
	  	<select class="combobox form-control" name="leica_cam">
	  	  
<?php
$sn = '';
if(!empty($row['leica_cam'])){ $sn = $row['leica_cam'];}
listUnused('leica_cam', '	serial_nr NOT IN (
	            			SELECT sensor_unit.leica_cam_sn
	            			FROM sensor_unit)'	, $sn);
?>

		</select>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="topo_sensor" class="col-xs-5 control-label">Topo Sensor</label>
	  <div class="col-xs-7">
	  	<select class="combobox form-control" name="topo_sensor">
	  	  
<?php
$sn = '';
if(!empty($row['topo_sensor'])){ $sn = $row['topo_sensor'];}
listUnused('sensor', '		sensor_type = "topo" AND
							serial_nr NOT IN (
	            			SELECT sensor_unit.topo_sensor_sn
	            			FROM sensor_unit)', $sn);
?>

		</select>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="shallow_sensor" class="col-xs-5 control-label">Shallow Sensor</label>
	  <div class="col-xs-7">
	  	<select class="combobox form-control" name="shallow_sensor">
	  	  
<?php
$sn = '';
if(!empty($row['topo_sensor'])){ $sn = $row['topo_sensor'];}
listUnused('sensor', '		sensor_type = "shallow" AND
							serial_nr NOT IN (
	            			SELECT sensor_unit.shallow_sensor_sn
	            			FROM sensor_unit)', $sn);
?>

		</select>
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