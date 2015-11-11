<?php

$titel = 'Edit Sensor Unit';
include 'res/header.inc.php';
$type = 'add_sensor_unit';
if (!empty($_GET['serial_nr'])) {
	$type = 'update_sensor_unit';

	// Create connection
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

	<div class="col-xs-8 col-xs-offset-4"><h4>Sensor Unit</h4></div>

	  <div class="form-group">
		<label for="serial_nr" class="col-xs-4 control-label">Serial Number</label>
	  	<div class="col-xs-8">
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
		<label for="imu" class="col-xs-4 control-label">IMU</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="imu" <?= !empty($row['imu']) ?  'value="' . $row['imu'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="leica_cam" class="col-xs-4 control-label">Leica Camera</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="leica_cam">
	  	  
<?php
$sn = '';
if(!empty($row['leica_cam_sn'])){ $sn = $row['leica_cam_sn'];}
listUnusedSerialNr('leica_cam', '	serial_nr NOT IN (
	            			SELECT sensor_unit.leica_cam_sn
	            			FROM sensor_unit)'	, $sn);
?>

		</select>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="leica_lens" class="col-xs-4 control-label">Leica Lens</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="leica_lens" <?= !empty($row['leica_lens']) ?  'value="' . $row['leica_lens'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="topo_sensor" class="col-xs-4 control-label">Topo Sensor</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="topo_sensor">
	  	  
<?php
$sn = '';
if(!empty($row['topo_sensor_sn'])){ $sn = $row['topo_sensor_sn'];}
listUnusedSerialNr('sensor', '		sensor_type = "topo" AND
							serial_nr NOT IN (
	            			SELECT sensor_unit.topo_sensor_sn
	            			FROM sensor_unit)', $sn);
?>

		</select>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="shallow_sensor" class="col-xs-4 control-label">Shallow Sensor</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="shallow_sensor">
	  	  
<?php
$sn = '';
if(!empty($row['topo_sensor_sn'])){ $sn = $row['topo_sensor_sn'];}
listUnusedSerialNr('sensor', '		sensor_type = "shallow" AND
							serial_nr NOT IN (
	            			SELECT sensor_unit.shallow_sensor_sn
	            			FROM sensor_unit)', $sn);
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

	</div> <!-- end col -->
	<div class="col-sm-3 col-sm-offset-1">

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

  </div><!-- end row -->
  <div class="row">
	  <div class="col-sm-12">
	    <button type="submit" class="btn btn-default">Apply</button>
	    <a href="parts.php" class="btn btn-default">Cancel</a>
	  </div>
  </div>
</form>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>