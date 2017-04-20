<?php
session_start();
include_once 'res/config.inc.php';
include_once('res/functions.inc.php');

$database_columns = "";
if(!empty($_POST)){
	$database_columns = "
	imu = '$_POST[imu]',
	leica_cam_sn = '$_POST[leica_cam]',
	leica_lens = '$_POST[leica_lens]',
	topo_sensor_sn = '$_POST[topo_sensor]',
	topo_sensor_2_sn = '$_POST[topo_sensor_2]',
	shallow_sensor_sn = '$_POST[shallow_sensor]',
	comment = '$_POST[comment]'
	";
}
$row = postFunction('serial_nr', 'sensor_unit', $database_columns, 'main_parts.php');

$titel = 'Edit Sensor Unit';
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

					<select class="combobox form-control" name="imu">

							<?php
							$sn = '';
							if(!empty($row['imu'])){ $sn = $row['imu'];}
							listUnusedSerialNr('leica', '	type = "imu" AND
							serial_nr NOT IN (
							SELECT sensor_unit.imu
							FROM sensor_unit)'	, $sn);
							?>

						</select>
						
					</div>
				</div>

				<div class="form-group">
					<label for="leica_cam" class="col-xs-4 control-label">Leica Camera</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="leica_cam">

							<?php
							$sn = '';
							if(!empty($row['leica_cam_sn'])){ $sn = $row['leica_cam_sn'];}
							listUnusedSerialNr('leica', '	type = "Camera" AND
							serial_nr NOT IN (
							SELECT sensor_unit.leica_cam_sn
							FROM sensor_unit)'	, $sn);
							?>

						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="leica_lens" class="col-xs-4 control-label">Leica Lens</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="leica_lens">

							<?php
							$sn = '';
							if(!empty($row['leica_lens'])){ $sn = $row['leica_lens'];}
							listUnusedSerialNr('leica', '	type = "Leica Lens" AND
							serial_nr NOT IN (
							SELECT sensor_unit.leica_lens
							FROM sensor_unit)'	, $sn);
							?>

						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="topo_sensor" class="col-xs-4 control-label">Topo Sensor</label>
					<div class="col-xs-4">
						<select class="combobox form-control" name="topo_sensor">

							<?php
							$sn = empty($row['topo_sensor_sn']) ? "" : $row['topo_sensor_sn'];
							listUnusedSerialNr('sensor', '		sensor_type = "topo" AND
							serial_nr NOT IN (
							SELECT sensor_unit.topo_sensor_sn
							FROM sensor_unit)
							AND
							serial_nr NOT IN (
							SELECT sensor_unit.topo_sensor_2_sn
							FROM sensor_unit)', $sn);
							?>

						</select>
					</div>
					<div class="col-xs-4">
						<select class="combobox form-control" name="topo_sensor_2">

							<?php
							$sn = empty($row['topo_sensor_2_sn']) ? "" : $row['topo_sensor_2_sn'];
							listUnusedSerialNr('sensor', '		sensor_type = "topo" AND
							serial_nr NOT IN (
							SELECT sensor_unit.topo_sensor_sn
							FROM sensor_unit)
							AND
							serial_nr NOT IN (
							SELECT sensor_unit.topo_sensor_2_sn
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
							if(!empty($row['shallow_sensor_sn'])){ $sn = $row['shallow_sensor_sn'];}
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
						<input type="text" class="form-control" name="user" <?= !empty($_SESSION['user']) ? 'value="' . $_SESSION['user'] . '"' : ''; ?> required />
					</div>
				</div>

				<div class="form-group col-xs-12">
					<label for="log_comment">Comment saved in Log file</label>
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
