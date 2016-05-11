<?php
session_start();
include_once 'res/config.inc.php';
include_once('res/functions.inc.php');
include_once 'res/postfunctions.inc.php';

$database_columns = "";
if(!empty($_POST)){
	$database_columns = "
	cooling_system = '$_POST[cooling_system]',
	imu = '$_POST[imu]',
	pro_pack = '$_POST[pro_pack]',
	deep_sensor_sn = '$_POST[deep_sensor]',
	comment = '$_POST[comment]'
	";
}
$row = postFunction('serial_nr', 'deep_system', $database_columns, 'main_parts.php');

$titel = 'Edit Deep System';
include 'res/header.inc.php';
?>
<script type="text/javascript">
  $(document).ready(function(){
    $('.combobox').combobox();
  });
</script>
<section class="content">

<form action= <?php echo htmlspecialchars($_SERVER['PHP_SELF'] ); ?> method="post" class="form-horizontal">
  <div class="row">
	<div class="col-sm-6 col-sm-offset-1">

	<div class="col-xs-8 col-xs-offset-4"><h4>Deep System</h4></div>

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
		<label for="cooling_system" class="col-xs-4 control-label">Cooling System</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="cooling_system" <?= !empty($row['cooling_system']) ?  'value="' . $row['cooling_system'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="imu" class="col-xs-4 control-label">IMU</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="imu" <?= !empty($row['imu']) ?  'value="' . $row['imu'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="pro_pack" class="col-xs-4 control-label">Pro Pack</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="pro_pack" <?= !empty($row['pro_pack']) ?  'value="' . $row['pro_pack'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="deep_sensor" class="col-xs-4 control-label">Deep Sensor</label>
	  <div class="col-xs-8">
	  	<select class="combobox form-control" name="deep_sensor">

<?php
$sn = '';
if(!empty($row['deep_sensor_sn'])){ $sn = $row['deep_sensor_sn'];}
listUnusedSerialNr('sensor', '	sensor_type = "deep" AND serial_nr NOT IN (
	            			SELECT deep_system.deep_sensor_sn
	            			FROM deep_system)'	, $sn);
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
	    <a href="main_parts.php" class="btn btn-default">Cancel</a>
	  </div>
  </div>
</form>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>
