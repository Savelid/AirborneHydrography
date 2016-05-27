<?php
session_start();
include_once 'res/config.inc.php';
include_once('res/functions.inc.php');
include_once 'res/postfunctions.inc.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

//Get number for Dataset ID
$sql = "SELECT dataset_id FROM datasets WHERE dataset_id LIKE 'AHAB-DATA-%' ORDER BY dataset_id DESC LIMIT 1 ;";
$result_dataset_id = $conn->query($sql);
$row_dataset_id = $result_dataset_id->fetch_array(MYSQLI_NUM);
$int_dataset_id = filter_var($row_dataset_id[0], FILTER_SANITIZE_NUMBER_INT);
$int_dataset_id = str_replace('-', '', $int_dataset_id);
$int_dataset_id = intval($int_dataset_id);
$int_dataset_id = $int_dataset_id + 1;
debug_to_console("Dataset id nummer" . $int_dataset_id);

$conn->close();

$status_msg = "";
$database_columns = "";
if(!empty($_POST)){
	//debug_to_console("POST true. flight_logs: " . $_FILES["flight_logs"]["name"]);
	if ($_FILES['flight_logs']['size'] > 0 && $_FILES['flight_logs']['error'] == 0){
		debug_to_console("flight_logs not empty");
		$target_dir = "flight_logs/";
		$target_file = $target_dir . $_POST['dataset_id'] . "__" . basename($_FILES["flight_logs"]["name"]);
		$uploadOk = 1;
		$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

		// Check if file already exists
		if (file_exists($target_file)) {
    		$status_msg .= "File already exists. file will be overwriten <br>";
    		//$uploadOk = 0;
		}

		// Check file size
		if ($_FILES["flight_logs"]["size"] > 50000000) {
			 $status_msg .= "Sorry, your file is too large.";
			 $uploadOk = 0;
		}

		// Allow certain file formats
		if($fileType != "pdf") {
		    $status_msg .= "Sorry, only PDF files are allowed.";
		    $uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    $status_msg .= "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["flight_logs"]["tmp_name"], $target_file)) {
		        $status_msg .= "The file ". basename( $_FILES["flight_logs"]["name"]). " has been uploaded.";
		    } else {
		        $status_msg .= "Sorry, there was an error uploading your file.";
		    }
		}
}
		$database_columns = "
			datetime = '$_POST[datetime]',
			disc_id = '$_POST[disc_id]',
			location = '$_POST[location]',
			system_id = '$_POST[system_id]',
			system_model = '$_POST[system_model]',
			topo_sensor_1_sn = '$_POST[topo_sensor_1_sn]',
			topo_sensor_2_sn = '$_POST[topo_sensor_2_sn]',
			shallow_sensor_sn = '$_POST[shallow_sensor_sn]',
			deep_sensor_sn = '$_POST[deep_sensor_sn]',
			scu_sn = '$_POST[scu_sn]',
			imu_1_sn = '$_POST[imu_1_sn]',
			imu_2_sn = '$_POST[imu_2_sn]',
			leica_pav_sn = '$_POST[leica_pav]',
			leica_cam_sn = '$_POST[leica_cam]',

			type_of_data = '$_POST[type_of_data]',
			purpose_of_flight = '$_POST[purpose_of_flight]',

			nav_data_processing_log = '$_POST[nav_data_processing_log]',
			calibration_file = '$_POST[calibration_file]',
			processing_settings_file = '$_POST[processing_settings_file]',
			configuration_file = '$_POST[configuration_file]',
			calibration_report = '$_POST[calibration_report]',
			acceptance_report = '$_POST[acceptance_report]',
			system_fully_functional = '$_POST[system_fully_functional]',
			raw_data_in_archive = '$_POST[raw_data_in_archive]',
			raw_data_in_back_up_archive = '$_POST[raw_data_in_back_up_archive]'
			";
		if (!empty($target_file)) {
			$database_columns .= ", flight_logs = '$target_file'";
		}
}

$row = postFunction('dataset_id', 'datasets', $database_columns, 'main_datasets.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$row2 = getDatabaseRow('datasets', 'dataset_id', $_POST['dataset_id']);
	if (!empty($_POST['flight_comments'])) {
		$flight_comments = $row2['flight_comments'] ."&|A". $_POST['user'] ."&|D". substr($row2['datetime'], 0 , 10) ."&|M". $_POST['flight_comments'];
	}else{
		$flight_comments = $row2['flight_comments'];
	}
	if (!empty($_POST['data_comments'])) {
		$data_comments = $row2['data_comments'] ."&|A". $_POST['user'] ."&|D". substr($row2['datetime'], 0 , 10) ."&|M". $_POST['data_comments'];
	}else{
		$data_comments = $row2['data_comments'];
	}
	$database_columns2 = "
		flight_comments = '$flight_comments',
		data_comments = '$data_comments'
	";
	$results2 = postToDatabase('datasets', 'dataset_id', $_POST['dataset_id'], $database_columns2);
	$_SESSION['alert'] .= "<br>" . $results2['status'];
}
$_SESSION['alert'] .= "<br>" . $status_msg;

$titel = 'Edit dataset';
include 'res/header.inc.php';
?>
<section class="content">

	<form action= <?php echo htmlspecialchars($_SERVER['PHP_SELF'] ); ?> method="post" class="form-horizontal" enctype="multipart/form-data">

		<div class="row">
			<div class="col-sm-6 col-sm-offset-1">

				<div class="col-xs-8 col-xs-offset-4"><h4>Flight</h4></div>

				<div class="form-group">
					<label for="dataset_id" class="col-xs-4 control-label">
						Dataset ID
						<div class="comments">AHAB-DATA-xxxx</div>
					</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="dataset_id" <?= !empty($row['dataset_id']) ?  'value="' . $row['dataset_id'] . '"' : 'value="AHAB-DATA-' . sprintf("%04d", $int_dataset_id) .'"' ; ?> required />
					</div>
				</div>

				<div class="form-group">
					<label for="disc_id" class="col-xs-4 control-label">
						Disc ID
						<div class="comments">Example: D012 D014</div>
					</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="disc_id" <?= !empty($row['disc_id']) ?  'value="' . $row['disc_id'] . '"' : '' ; ?> />
					</div>
				</div>

				<div class="form-group">
					<label for="datetime" class="col-xs-4 control-label">Date</label>
					<div class="col-xs-8">
						<input type="date" class="form-control" name="datetime" <?= !empty($row['datetime']) ?  'value="' . substr($row['datetime'], 0, 10) . '"' : '' ; ?>>
					</div>
				</div>

				<div class="form-group">
					<label for="type_of_data" class="col-xs-4 control-label">Type of data</label>
					<div class="col-xs-8">
						<select class="form-control" name="type_of_data">
							<?php
							foreach($dataset_type_of_data as $i){
								$selected = '';
								if(!empty($row['type_of_data']) && $row['type_of_data'] == $i){$selected = 'selected';}
								$s = '<option value="%s" %s>%s</option>';
								echo sprintf($s, $i, $selected, $i);
							}
							?>
						</select>
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
					<label for="imu_1_sn" class="col-xs-4 control-label">
						IMU 1
						<div class="comments">Sensor unit</div>
					</label>
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
					<label for="imu_2_sn" class="col-xs-4 control-label">
						IMU 2
						<div class="comments">Deep system</div>
					</label>
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

				<div class="form-group">
					<label for="leica_pav" class="col-xs-4 control-label">
						PAV
						<div class="comments">Leica</div>
					</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="leica_pav">

							<?php
							$sn = '';
							if(!empty($row['leica_pav_sn'])){ $sn = $row['leica_pav_sn'];}
							listAllX('serial_nr', 'leica', "WHERE type = 'PAV'"	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="leica_cam" class="col-xs-4 control-label">
						Camera
						<div class="comments">Leica</div>
					</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="leica_cam">

							<?php
							$sn = '';
							if(!empty($row['leica_cam_sn'])){ $sn = $row['leica_cam_sn'];}
							listAllX('serial_nr', 'leica', "WHERE type = 'Camera'"	, $sn);
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
					<label for="purpose_of_flight" class="col-sm-3 col-xs-12 control-label">
						Purpose of flight
						<div class ="comments">A not too short explanation of why the flight was done. It must be long enough to be useful three years later</div>
					</label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="purpose_of_flight" rows="5"><?= !empty($row['purpose_of_flight']) ?  $row['purpose_of_flight'] : '' ; ?></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="flight_comments" class="col-sm-3 col-xs-12 control-label">
						Flight Comments
						<div class ="comments">Describe encountered problems during flight</div>
					</label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="flight_comments" rows="5"></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="data_comments" class="col-sm-3 col-xs-12 control-label">
						Data Comments
						<div class ="comments">Describe the results regarding the purpose of the flight but also if other issues was noted. Must also be long enough to be useful after three years.</div>
					</label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="data_comments" rows="5"></textarea>
					</div>
				</div>

				<div class="form-group">
					<label for="flight_logs" class="col-sm-3 col-xs-12 control-label">
						Flight logs
						<div class ="comments">
							Describe encountered problems during flight
							<br>
							Warning: Sorry the system can not hadle some special characters like Å,Ä,Ö in the file name for now :(
						</div>
					</label>
					<div class="col-sm-8 col-xs-12">
						<input type="file" class="form-control" name="flight_logs" id="flight_logs" >
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
<script type="text/javascript">
$(document).ready(function(){
	$('.combobox').combobox();
});
</script>

<?php include 'res/footer.inc.php'; ?>
