<?php
session_start();
$titel = 'Edit Flight';
include 'res/header.inc.php';
$type = 'add_flight';
if (!empty($_GET['id'])) {
	$type = 'update_flight';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT *
	FROM flight
	WHERE id = '$_GET[id]';";
	$result = $conn->query($sql);
	if (!$result) {
		die("Query 1 failed! " . $sql . "<br>" . $conn->error);
	}
	$row = $result->fetch_array(MYSQLI_ASSOC);

	$conn->close();
}
$path = 'post_add_update.php?type=' . $type;
?>
<?php require_once('res/functions.inc.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
	$('.combobox').combobox();
});
</script>
<section class="content">

	<form action= <?php echo htmlspecialchars($path); ?> method="post" class="form-horizontal">

		<?php
		if(isset($_GET['id'])){
			echo '<input type="hidden" class="form-control" name="id" value="' . $_GET['id'] . '"/>';
		}
		?>
		<div class="row">
			<div class="col-sm-6 col-sm-offset-1">

				<div class="col-xs-8 col-xs-offset-4"><h4>Flight</h4></div>

				<div class="form-group">
					<label for="dataset_id" class="col-xs-4 control-label">Dataset ID</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="dataset_id" <?= !empty($row['dataset_id']) ?  'value="' . $row['dataset_id'] . '"' : '' ; ?>>
					</div>
				</div>

				<div class="form-group">
					<label for="datetime" class="col-xs-4 control-label">Date</label>
					<div class="col-xs-8">
						<input type="date" class="form-control" name="datetime" <?= !empty($row['datetime']) ?  'value="' . substr($row['datetime'], 0, 10) . '"' : '' ; ?>>
					</div>
				</div>

				<div class="form-group">
					<label for="location" class="col-xs-4 control-label">Location</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="location" <?= !empty($row['location']) ?  'value="' . $row['location'] . '"' : '' ; ?>>
					</div>
				</div>

				<div class="form-group">
					<label for="system_id" class="col-xs-4 control-label">System ID</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="system_id">

							<?php
							$sn = '';
							if(!empty($row['system_id'])){ $sn = $row['system_id'];}
							listAllX('serial_nr', 'system', ''	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="system_model" class="col-xs-4 control-label">System model</label>
					<div class="col-xs-8">
						<select class="form-control" name="system_model">
							<?php
							foreach($configuration_values as $i){
								$selected = '';
								if(!empty($row['system_model']) && $row['system_model'] == $i){$selected = 'selected';}
								$s = '<option value="%s" %s>%s</option>';
								echo sprintf($s, $i, $selected, $i);
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="topo_sensor_1_sn" class="col-xs-4 control-label">Topo sensor 1</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="topo_sensor_1_sn">

							<?php
							$sn = '';
							if(!empty($row['topo_sensor_1_sn'])){ $sn = $row['topo_sensor_1_sn'];}
							listAllX('serial_nr', 'sensor', "WHERE sensor_type = 'topo'"	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="topo_sensor_2_sn" class="col-xs-4 control-label">Topo sensor 2</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="topo_sensor_2_sn">

							<?php
							$sn = '';
							if(!empty($row['topo_sensor_2_sn'])){ $sn = $row['topo_sensor_2_sn'];}
							listAllX('serial_nr', 'sensor', "WHERE sensor_type = 'topo'"	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="shallow_sensor_sn" class="col-xs-4 control-label">Shallow sensor</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="shallow_sensor_sn">

							<?php
							$sn = '';
							if(!empty($row['shallow_sensor_sn'])){ $sn = $row['shallow_sensor_sn'];}
							listAllX('serial_nr', 'sensor', "WHERE sensor_type = 'shallow'"	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="deep_sensor_sn" class="col-xs-4 control-label">Deep sensor</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="deep_sensor_sn">

							<?php
							$sn = '';
							if(!empty($row['deep_sensor_sn'])){ $sn = $row['deep_sensor_sn'];}
							listAllX('serial_nr', 'sensor', "WHERE sensor_type = 'deep'"	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="scu_sn" class="col-xs-4 control-label">SCU</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="scu_sn">

							<?php
							$sn = '';
							if(!empty($row['scu_sn'])){ $sn = $row['scu_sn'];}
							listAllX('serial_nr', 'scu', ''	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="imu_1_sn" class="col-xs-4 control-label">IMU 1 (sensor unit)</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="imu_1_sn">

							<?php
							$sn = '';
							if(!empty($row['imu_1_sn'])){ $sn = $row['imu_1_sn'];}
							listAllX('imu', 'sensor_unit', ''	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="imu_2_sn" class="col-xs-4 control-label">IMU 2 (deep system)</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="imu_2_sn">

							<?php
							$sn = '';
							if(!empty($row['imu_2_sn'])){ $sn = $row['imu_2_sn'];}
							listAllX('imu', 'deep_system', ''	, $sn);
							?>
						</select>
					</div>
				</div>

			</div>
			<div class="col-sm-3 col-sm-offset-1">

				<h4>Data processor</h4>

				<div class="form-group">
					<label for="calibration_file" class="col-xs-12">Calibration file</label>
					<div class="col-xs-12">
						<select class="form-control" name="calibration_file">
							<?php
							$calibration_file_values = array('None', 'Final', 'Temporary');
							foreach($calibration_file_values as $i){
								$selected = '';
								if(!empty($row['calibration_file']) && $row['calibration_file'] == $i){$selected = 'selected';}
								$s = '<option value="%s" %s>%s</option>';
								echo sprintf($s, $i, $selected, $i);
							}
							?>
						</select>
					</div>
				</div>

				<div class="checkbox"><label>
					<input type="hidden" name="nav_data_processing_log" value=0 />
					<input type="checkbox" name="nav_data_processing_log" value=1 <?= !empty($row['nav_data_processing_log']) && $row['nav_data_processing_log'] ? 'checked' : ''; ?>/> nav_data_processing_log
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="processing_settings_file" value=0 />
					<input type="checkbox" name="processing_settings_file" value=1 <?= !empty($row['processing_settings_file']) && $row['processing_settings_file'] ? 'checked' : ''; ?>/> processing_settings_file
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="configuration_file" value=0 />
					<input type="checkbox" name="configuration_file" value=1 <?= !empty($row['configuration_file']) && $row['configuration_file'] ? 'checked' : ''; ?>/> configuration_file
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="calibration_report" value=0 />
					<input type="checkbox" name="calibration_report" value=1 <?= !empty($row['calibration_report']) && $row['calibration_report'] ? 'checked' : ''; ?>/> calibration_report
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="acceptance_report" value=0 />
					<input type="checkbox" name="acceptance_report" value=1 <?= !empty($row['acceptance_report']) && $row['acceptance_report'] ? 'checked' : ''; ?>/> acceptance_report
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="system_fully_functional" value=0 />
					<input type="checkbox" name="system_fully_functional" value=1 <?= !empty($row['system_fully_functional']) && $row['system_fully_functional'] ? 'checked' : ''; ?>/> system_fully_functional
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="raw_data_in_archive" value=0 />
					<input type="checkbox" name="raw_data_in_archive" value=1 <?= !empty($row['raw_data_in_archive']) && $row['raw_data_in_archive'] ? 'checked' : ''; ?>/> raw_data_in_archive
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="raw_data_in_back_up_archive" value=0 />
					<input type="checkbox" name="raw_data_in_back_up_archive" value=1 <?= !empty($row['raw_data_in_back_up_archive']) && $row['raw_data_in_back_up_archive'] ? 'checked' : ''; ?>/> raw_data_in_back_up_archive
				</label></div>

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
		</div>
		<div class="row">
			<div class="col-xs-12">

				<div class="form-group">
					<label for="ranging" class="col-sm-3 col-xs-12 control-label">Ranging<div class ="comments">What ranging datasets are applicable for those sensors?</div></label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="ranging" rows="5"><?= !empty($row['ranging']) ?  $row['ranging'] : '' ; ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="type_of_data" class="col-sm-3 col-xs-12 control-label">Type of data<div class ="comments">Har inte an aning</div></label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="type_of_data" rows="2"><?= !empty($row['type_of_data']) ?  $row['type_of_data'] : '' ; ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="purpose_of_flight" class="col-sm-3 col-xs-12 control-label">Purpose of flight<div class ="comments">A not too short explanation of why the flight was done. It must be long enough to be useful three years later</div></label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="purpose_of_flight" rows="5"><?= !empty($row['purpose_of_flight']) ?  $row['purpose_of_flight'] : '' ; ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="evaluation_of_flight" class="col-sm-3 col-xs-12 control-label">Evaluation of flight<div class ="comments">Describe encountered problems during flight</div></label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="evaluation_of_flight" rows="5"><?= !empty($row['evaluation_of_flight']) ?  $row['evaluation_of_flight'] : '' ; ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="flight_logs" class="col-sm-3 col-xs-12 control-label">Flight logs<div class ="comments">Describe encountered problems during flight</div></label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="flight_logs" rows="2"><?= !empty($row['flight_logs']) ?  $row['flight_logs'] : '' ; ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="data_evaluation" class="col-sm-3 col-xs-12 control-label">Data evaluation<div class ="comments">Describe the results regarding the purpose of the flight but also if other issues was noted. Must also be long enough to be useful after three years.</div></label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="data_evaluation" rows="5"><?= !empty($row['data_evaluation']) ?  $row['data_evaluation'] : '' ; ?></textarea>
					</div>
				</div>

			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">

				<button type="submit" class="btn btn-default">Apply</button>
				<a href="main_systems.php" class="btn btn-default">Cancel</a>
			</div>
		</div>
	</form>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>
