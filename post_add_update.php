<?php
session_start();
$_SESSION['user'] = $_POST['user'];
$_SESSION['showalert'] = 'true';
include 'res/config.inc.php';
// Check that type is deffined
if(isset($_GET['type'])){

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add all requests saved by this page to LOG
function postToLog($sql_string) {

	// Create connection
	include 'res/config.inc.php';
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	$sql_log = "INSERT INTO log (type, user, sql_string, serial_nr, comment)
				VALUES ('$_GET[type]', '$_POST[user]', '$sql_string', '$_POST[serial_nr]', '$_POST[log_comment]')";
	if ($conn->query($sql_log) === TRUE) {
		echo "Log created successfully";

	} else {
		echo "Error: " . $sql_log . "<br>" . $conn->error;
	}
}

// INSERT system
if($_GET['type'] == 'add_system') {

	$sql_insert = "INSERT INTO system (serial_nr, art_nr, client, place, configuration, sensor_unit_sn, control_system_sn, deep_system_sn, pd60, oc60_1_sn, oc60_2_sn, pav_sn, status, comment, oc, bitfile_topo, bitfile_shallow, bitfile_deep, bitfile_digitaizer1, bitfile_digitaizer2, bitfile_sat)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[art_nr]',
					'$_POST[client]',
					'$_POST[place]',
					'$_POST[configuration]',
					'$_POST[sensor_unit]',
					'$_POST[control_system]',
					'$_POST[deep_system]',
          '$_POST[pd60]',
					'$_POST[oc60_1]',
					'$_POST[oc60_2]',
					'$_POST[pav]',
					'$_POST[status]',
					'$_POST[comment]',
					'$_POST[oc]',
					'$_POST[bitfile_topo]',
					'$_POST[bitfile_shallow]',
					'$_POST[bitfile_deep]',
					'$_POST[bitfile_digitaizer1]',
					'$_POST[bitfile_digitaizer2]',
					'$_POST[bitfile_sat]');";
	$sql_insert .= "INSERT INTO system_status (serial_nr, status_potta_heat, status_shallow_heat, status_scu_pdu, status_hv_topo, status_hv_shallow, status_hv_deep, status_cat, status_pwr_cable)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[status_potta_heat]',
					'$_POST[status_shallow_heat]',
					'$_POST[status_scu_pdu]',
					'$_POST[status_hv_topo]',
					'$_POST[status_hv_shallow]',
					'$_POST[status_hv_deep]',
					'$_POST[status_cat]',
					'$_POST[status_pwr_cable]');";
	if ($conn->multi_query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'New system added';
		header("Location: main_systems.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

// UPDATE system
if ($_GET['type'] == 'update_system') {

	$sql_update = " UPDATE system
					SET
						art_nr = '$_POST[art_nr]',
						client = '$_POST[client]',
						place = '$_POST[place]',
						configuration = '$_POST[configuration]',
						sensor_unit_sn = '$_POST[sensor_unit]',
						control_system_sn = '$_POST[control_system]',
						deep_system_sn = '$_POST[deep_system]',
            pd60 = '$_POST[pd60]',
						oc60_1_sn = '$_POST[oc60_1]',
						oc60_2_sn = '$_POST[oc60_2]',
						pav_sn = '$_POST[pav]',
						status = '$_POST[status]',
						comment = '$_POST[comment]',
						oc = '$_POST[oc]',
						bitfile_topo = '$_POST[bitfile_topo]',
						bitfile_shallow = '$_POST[bitfile_shallow]',
						bitfile_deep = '$_POST[bitfile_deep]',
						bitfile_digitaizer1 = '$_POST[bitfile_digitaizer1]',
						bitfile_digitaizer2 = '$_POST[bitfile_digitaizer2]',
						bitfile_sat = '$_POST[bitfile_sat]'
					WHERE serial_nr = '$_POST[serial_nr]';";
	$sql_update .= "UPDATE system_status
					SET
						status_potta_heat = '$_POST[status_potta_heat]',
						status_shallow_heat = '$_POST[status_shallow_heat]',
						status_scu_pdu = '$_POST[status_scu_pdu]',
						status_hv_topo = '$_POST[status_hv_topo]',
						status_hv_shallow = '$_POST[status_hv_shallow]',
						status_hv_deep = '$_POST[status_hv_deep]',
						status_cat = '$_POST[status_cat]',
						status_pwr_cable = '$_POST[status_pwr_cable]'
					WHERE serial_nr = '$_POST[serial_nr]';";
	if ($conn->multi_query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		$_SESSION['alert'] = 'System updated';
		header("Location: main_systems.php");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT sensor
if($_GET['type'] == 'add_sensor') {

	$sql_insert = "INSERT INTO sensor (serial_nr, sensor_type, cat, fpga_id, mirror, laser_sn, hv_card_sn, receiver_unit_sn, hv_card_2_sn, receiver_unit_2_sn, dps_value_input_offset_t0, dps_value_input_offset_rec, dps_value_input_offset_rec_wide, dps_value_pulse_width_t0, dps_value_pulse_width_rec, status)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[sensor_type]',
					'$_POST[cat]',
					'$_POST[fpga_id]',
          '$_POST[mirror]',
					'$_POST[laser]',
					'$_POST[hv_card]',
					'$_POST[receiver_unit]',
					'$_POST[hv_card_2]',
					'$_POST[receiver_unit_2]',
					'$_POST[dps_value_input_offset_t0]',
					'$_POST[dps_value_input_offset_rec]',
          '$_POST[dps_value_input_offset_rec_wide]',
					'$_POST[dps_value_pulse_width_t0]',
					'$_POST[dps_value_pulse_width_rec]',
					'$_POST[status]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'New sensor added';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

// UPDATE sensor
if ($_GET['type'] == 'update_sensor') {

	$sql_update = " UPDATE sensor
					SET
						sensor_type = '$_POST[sensor_type]',
						cat = '$_POST[cat]',
						fpga_id = '$_POST[fpga_id]',
            mirror = '$_POST[mirror]',
						laser_sn = '$_POST[laser]',
						hv_card_sn = '$_POST[hv_card]',
						receiver_unit_sn = '$_POST[receiver_unit]',
						hv_card_2_sn = '$_POST[hv_card_2]',
						receiver_unit_2_sn = '$_POST[receiver_unit_2]',

						dps_value_input_offset_t0 = '$_POST[dps_value_input_offset_t0]',
						dps_value_input_offset_rec = '$_POST[dps_value_input_offset_rec]',
            dps_value_input_offset_rec_wide = '$_POST[dps_value_input_offset_rec_wide]',
						dps_value_pulse_width_t0 = '$_POST[dps_value_pulse_width_t0]',
						dps_value_pulse_width_rec = '$_POST[dps_value_pulse_width_rec]',
						status = '$_POST[status]'
					WHERE serial_nr = '$_POST[serial_nr]'";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		$_SESSION['alert'] = 'Sensor updated';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT sensor_unit
if($_GET['type'] == 'add_sensor_unit') {

	$sql_insert = "INSERT INTO sensor_unit (serial_nr, imu, leica_cam_sn, leica_lens, topo_sensor_sn, topo_sensor_2_sn, shallow_sensor_sn, comment)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[imu]',
					'$_POST[leica_cam]',
					'$_POST[leica_lens]',
					'$_POST[topo_sensor]',
					'$_POST[topo_sensor_2]',
					'$_POST[shallow_sensor]',
					'$_POST[comment]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'New sensor unit added';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

// UPDATE sensor_unit
if ($_GET['type'] == 'update_sensor_unit') {

	$sql_update = " UPDATE sensor_unit
					SET
					imu = '$_POST[imu]',
					leica_cam_sn = '$_POST[leica_cam]',
					leica_lens = '$_POST[leica_lens]',
					topo_sensor_sn = '$_POST[topo_sensor]',
					topo_sensor_2_sn = '$_POST[topo_sensor_2]',
					shallow_sensor_sn = '$_POST[shallow_sensor]',
					comment = '$_POST[comment]'
					WHERE serial_nr = '$_POST[serial_nr]'";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		$_SESSION['alert'] = 'Sensor unit updated';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT control_system
if($_GET['type'] == 'add_control_system') {

	$sql_insert = "INSERT INTO control_system (serial_nr, battery, cc32_sn, pdu, scu_sn, comment)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[battery]',
					'$_POST[cc32]',
					'$_POST[pdu]',
					'$_POST[scu]',
					'$_POST[comment]');";
	$sql_insert .= "INSERT INTO cc32
					SET
					serial_nr ='$_POST[cc32]',
					firmware = '$_POST[firmware]'
					ON DUPLICATE KEY UPDATE
					serial_nr ='$_POST[cc32]',
					firmware = '$_POST[firmware_cc32]';";
	if ($conn->multi_query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'New control system added';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

// UPDATE control_system
if ($_GET['type'] == 'update_control_system') {

	$sql_update = " UPDATE control_system
					SET
					battery = '$_POST[battery]',
					cc32_sn = '$_POST[cc32]',
					pdu = '$_POST[pdu]',
					scu_sn = '$_POST[scu]',
					comment = '$_POST[comment]'
					WHERE serial_nr = '$_POST[serial_nr]';";
	$sql_update .= "INSERT INTO cc32
					SET
					serial_nr ='$_POST[cc32]',
					firmware = '$_POST[firmware]'
					ON DUPLICATE KEY UPDATE
					serial_nr ='$_POST[cc32]',
					firmware = '$_POST[firmware_cc32]';";
	if ($conn->multi_query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		$_SESSION['alert'] = 'Control system updated';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT deep_system
if($_GET['type'] == 'add_deep_system') {

	$sql_insert = "INSERT INTO deep_system (serial_nr, cooling_system, imu, pro_pack, deep_sensor_sn, comment)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[cooling_system]',
					'$_POST[imu]',
					'$_POST[pro_pack]',
					'$_POST[deep_sensor]',
					'$_POST[comment]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'New deep system added';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

// UPDATE deep_system
if ($_GET['type'] == 'update_deep_system') {

	$sql_update = " UPDATE deep_system
					SET
					cooling_system = '$_POST[cooling_system]',
					imu = '$_POST[imu]',
					pro_pack = '$_POST[pro_pack]',
					deep_sensor_sn = '$_POST[deep_sensor]',
					comment = '$_POST[comment]'
					WHERE serial_nr = '$_POST[serial_nr]'";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		$_SESSION['alert'] = 'Deep system updated';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT scu
if($_GET['type'] == 'add_scu') {

	$sql_insert = "INSERT INTO scu (serial_nr, configuration, digitaizer1, digitaizer2, sat, cpu, comment, status)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[configuration]',
					'$_POST[digitaizer1]',
					'$_POST[digitaizer2]',
					'$_POST[sat]',
					'$_POST[cpu]',
					'$_POST[comment]',
					'$_POST[status]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'New SCU added';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

// UPDATE scu
if ($_GET['type'] == 'update_scu') {

	$sql_update = " UPDATE scu
					SET
					configuration = '$_POST[configuration]',
					digitaizer1 = '$_POST[digitaizer1]',
					digitaizer2 = '$_POST[digitaizer2]',
					sat = '$_POST[sat]',
					cpu = '$_POST[cpu]',
					comment = '$_POST[comment]',
					status = '$_POST[status]'
					WHERE serial_nr = '$_POST[serial_nr]'";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		$_SESSION['alert'] = 'SCU updated';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT hv_card
if($_GET['type'] == 'add_hv_card') {

	$sql_insert = "INSERT INTO hv_card (serial_nr, art_nr, k_value, m_value)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[art_nr]',
					'$_POST[k_value]',
					'$_POST[m_value]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'New HV card added';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

// UPDATE hv_card
if ($_GET['type'] == 'update_hv_card') {

	$sql_update = " UPDATE hv_card
					SET
					art_nr = '$_POST[art_nr]',
					k_value = '$_POST[k_value]',
					m_value = '$_POST[m_value]'
					WHERE serial_nr = '$_POST[serial_nr]'";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		$_SESSION['alert'] = 'HV card updated';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT laser
if($_GET['type'] == 'add_laser') {

	$sql_insert = "INSERT INTO laser (serial_nr, v_0, v_5, v_10, v_15, v_20, v_25, v_30, v_40, v_50, v_60, v_70, v_80, v_90, v_100)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[v_0]',
					'$_POST[v_5]',
					'$_POST[v_10]',
					'$_POST[v_15]',
					'$_POST[v_20]',
					'$_POST[v_25]',
					'$_POST[v_30]',
					'$_POST[v_40]',
					'$_POST[v_50]',
					'$_POST[v_60]',
					'$_POST[v_70]',
					'$_POST[v_80]',
					'$_POST[v_90]',
					'$_POST[v_100]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'New laser added';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

// UPDATE laser
if ($_GET['type'] == 'update_laser') {

	$sql_update = " UPDATE laser
					SET
					v_0 = '$_POST[v_0]',
					v_5 = '$_POST[v_5]',
					v_10 = '$_POST[v_10]',
					v_15 = '$_POST[v_15]',
					v_20 = '$_POST[v_20]',
					v_25 = '$_POST[v_25]',
					v_30 = '$_POST[v_30]',
					v_40 = '$_POST[v_40]',
					v_50 = '$_POST[v_50]',
					v_60 = '$_POST[v_60]',
					v_70 = '$_POST[v_70]',
					v_80 = '$_POST[v_80]',
					v_90 = '$_POST[v_90]',
					v_100 = '$_POST[v_100]'
					WHERE serial_nr = '$_POST[serial_nr]'";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		$_SESSION['alert'] = 'Laser updated';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT leica
if($_GET['type'] == 'add_leica') {

	$sql_insert = "INSERT INTO leica (serial_nr, type, firmware)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[type]',
					'$_POST[firmware]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'New leica unit of type ' . $_POST['type'] . ' added';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

// UPDATE leica
if ($_GET['type'] == 'update_leica') {

	$sql_update = " UPDATE leica
					SET
					type = '$_POST[type]',
					firmware = '$_POST[firmware]'
					WHERE serial_nr = '$_POST[serial_nr]'";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		$_SESSION['alert'] = 'Leica unit of type ' . $_POST['type'] . ' updated';
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT or UPDATE receiver
if($_GET['type'] == 'add_receiver' || $_GET['type'] == 'update_receiver') {

	$sql = "	INSERT INTO receiver_unit
				SET
				serial_nr = '$_POST[serial_nr]',
				art_nr = '$_POST[art_nr]',
				receiver_chip_sn = '$_POST[receiver_chip]'
				ON DUPLICATE KEY UPDATE
				art_nr = '$_POST[art_nr]',
				receiver_chip_sn = '$_POST[receiver_chip]';";
	$sql .= "	INSERT INTO receiver_chip
				SET
				serial_nr = '$_POST[receiver_chip]',
				breakdown_voltage = '$_POST[breakdown_voltage]',
				operating_voltage = '$_POST[operating_voltage]'
				ON DUPLICATE KEY UPDATE
				breakdown_voltage = '$_POST[breakdown_voltage]',
				operating_voltage = '$_POST[operating_voltage]'";

	if ($conn->multi_query($sql) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql));
		$_SESSION['alert'] = $_GET['type'];
		header("Location: main_parts.php");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT flight log
if($_GET['type'] == 'add_flight' || $_GET['type'] == 'update_flight') {

	$sql_insert = "INSERT INTO flight
					SET
          id = '$_POST[id]',
          datetime = '$_POST[datetime]',
        	dataset_id = '$_POST[dataset_id]',
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

        	ranging = '$_POST[ranging]',
        	type_of_data = '$_POST[type_of_data]',
        	purpose_of_flight = '$_POST[purpose_of_flight]',
        	evaluation_of_flight = '$_POST[evaluation_of_flight]',
        	flight_logs = '$_POST[flight_logs]',
        	data_evaluation = '$_POST[data_evaluation]',

        	nav_data_processing_log = '$_POST[nav_data_processing_log]',
        	calibration_file = '$_POST[calibration_file]',
        	processing_settings_file = '$_POST[processing_settings_file]',
        	configuration_file = '$_POST[configuration_file]',
        	calibration_report = '$_POST[calibration_report]',
        	acceptance_report = '$_POST[acceptance_report]',
        	system_fully_functional = '$_POST[system_fully_functional]',
        	raw_data_in_archive = '$_POST[raw_data_in_archive]',
        	raw_data_in_back_up_archive = '$_POST[raw_data_in_back_up_archive]'

					ON DUPLICATE KEY UPDATE

          datetime = '$_POST[datetime]',
        	dataset_id = '$_POST[dataset_id]',
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

        	ranging = '$_POST[ranging]',
        	type_of_data = '$_POST[type_of_data]',
        	purpose_of_flight = '$_POST[purpose_of_flight]',
        	evaluation_of_flight = '$_POST[evaluation_of_flight]',
        	flight_logs = '$_POST[flight_logs]',
        	data_evaluation = '$_POST[data_evaluation]',

        	nav_data_processing_log = '$_POST[nav_data_processing_log]',
        	calibration_file = '$_POST[calibration_file]',
        	processing_settings_file = '$_POST[processing_settings_file]',
        	configuration_file = '$_POST[configuration_file]',
        	calibration_report = '$_POST[calibration_report]',
        	acceptance_report = '$_POST[acceptance_report]',
        	system_fully_functional = '$_POST[system_fully_functional]',
        	raw_data_in_archive = '$_POST[raw_data_in_archive]',
        	raw_data_in_back_up_archive = '$_POST[raw_data_in_back_up_archive]'";

	if ($conn->multi_query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'Flight log updated';
		header("Location: main_flights.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

// INSERT ISP log
if($_GET['type'] == 'add_isp' || $_GET['type'] == 'update_isp') {

	$sql_insert = "INSERT INTO isp
					SET
          id = '$_POST[id]',
          datetime = '$_POST[datetime]',
        	isp_nr = '$_POST[isp_nr]',
        	product = '$_POST[product]',
        	amount = '$_POST[amount]',
        	value = '$_POST[value]',
        	receiver = '$_POST[receiver]',
        	country = '$_POST[country]',
        	code = '$_POST[code]',
        	comment = '$_POST[comment]'

					ON DUPLICATE KEY UPDATE

          datetime = '$_POST[datetime]',
        	isp_nr = '$_POST[isp_nr]',
        	product = '$_POST[product]',
        	amount = '$_POST[amount]',
        	value = '$_POST[value]',
        	receiver = '$_POST[receiver]',
        	country = '$_POST[country]',
        	code = '$_POST[code]',
        	comment = '$_POST[comment]'";

	if ($conn->multi_query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		$_SESSION['alert'] = 'ISP log updated';
		header("Location: main_isp.php");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}

//INSERT POST values
if($_GET["type"] == 'add_message') {
	$sql_insert = "INSERT INTO overview (message, author)
	VALUES ('$_POST[message]', '$_POST[user]')";

	if ($conn->query($sql_insert) === TRUE) {
    	echo "New record created successfully";
    	$_SESSION['alert'] = 'New message';
    	header("Location: main_overview.php");
		die();
	} else {
    	echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}
$conn->close();  // close db
} // end if !empty
?>
