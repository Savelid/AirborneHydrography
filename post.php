<?php
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

	$sql_insert = "INSERT INTO system (serial_nr, art_nr, client, place, configuration, sensor_unit_sn, control_system_sn, deep_system_sn, status, comment)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[art_nr]',
					'$_POST[client]', 
					'$_POST[place]',
					'$_POST[configuration]', 
					'$_POST[sensor_unit]', 
					'$_POST[control_system]', 
					'$_POST[deep_system]', 
					'$_POST[status]',
					'$_POST[comment]');";
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
		header("Location: systems.php?alert=SystemAdded");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
} 

// UPDATE system
if ($_GET['type'] == 'update_system') {

	$sql_update = " UPDATE system
					SET
						client = '$_POST[client]',
						place = '$_POST[place]',
						configuration = '$_POST[configuration]',
						sensor_unit_sn = '$_POST[sensor_unit]', 
						control_system_sn = '$_POST[control_system]', 
						deep_system_sn = '$_POST[deep_system]', 
						status = '$_POST[status]',
						comment = '$_POST[comment]'
					WHERE serial_nr = $_POST[serial_nr];";
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
					WHERE serial_nr = $_POST[serial_nr];";
	if ($conn->multi_query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: systems.php?alert=SystemUpdated");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT sensor
if($_GET['type'] == 'add_sensor') {

	$sql_insert = "INSERT INTO sensor (serial_nr, sensor_type, cat, fpga_id, laser_sn, hv_card_sn, receiver_unit, receiver_chip_sn, hv_card_2_sn, receiver_unit_2, receiver_chip_2_sn, dps_value_input_offset_t0, dps_value_input_offset_rec, dps_value_pulse_width_t0, dps_value_pulse_width_rec, status)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[sensor_type]',
					'$_POST[cat]', 
					'$_POST[fpga_id]', 
					'$_POST[laser]', 
					'$_POST[hv_card]', 
					'$_POST[receiver_unit]', 
					'$_POST[receiver_chip]', 
					'$_POST[hv_card_2]',
					'$_POST[receiver_unit_2]', 
					'$_POST[receiver_chip_2]', 
					'$_POST[dps_value_input_offset_t0]', 
					'$_POST[dps_value_input_offset_rec]', 
					'$_POST[dps_value_pulse_width_t0]', 
					'$_POST[dps_value_pulse_width_rec]', 
					'$_POST[status]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		header("Location: parts.php?alert=SensorAdded");
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
						laser_sn = '$_POST[laser]', 
						hv_card_sn = '$_POST[hv_card]', 
						receiver_unit = '$_POST[receiver_unit]', 
						receiver_chip_sn = '$_POST[receiver_chip]',
						hv_card_2_sn = '$_POST[hv_card_2]',

						receiver_unit_2 = '$_POST[receiver_unit_2]',
						receiver_chip_2_sn = '$_POST[receiver_chip_2]',
						dps_value_input_offset_t0 = '$_POST[dps_value_input_offset_t0]',
						dps_value_input_offset_rec = '$_POST[dps_value_input_offset_rec]',
						dps_value_pulse_width_t0 = '$_POST[dps_value_pulse_width_t0]',
						dps_value_pulse_width_rec = '$_POST[dps_value_pulse_width_rec]',
						status = '$_POST[status]'
					WHERE serial_nr = $_POST[serial_nr]";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: parts.php?alert=SensorUpdated");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT sensor_unit
if($_GET['type'] == 'add_sensor_unit') {

	$sql_insert = "INSERT INTO sensor_unit (serial_nr, imu, leica_cam_sn, leica_lens, topo_sensor_sn, shallow_sensor_sn)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[imu]',
					'$_POST[leica_cam]', 
					'$_POST[leica_lens]', 
					'$_POST[topo_sensor]', 
					'$_POST[shallow_sensor]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		header("Location: parts.php?alert=SensorUnitAdded");
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
					shallow_sensor_sn = '$_POST[shallow_sensor]'
					WHERE serial_nr = $_POST[serial_nr]";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: parts.php?alert=SensorUnitUpdated");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT control_system
if($_GET['type'] == 'add_control_system') {

	$sql_insert = "INSERT INTO control_system (serial_nr, battery, cc32, pdu, scu_sn)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[battery]',
					'$_POST[cc32]', 
					'$_POST[pdu]', 
					'$_POST[scu]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		header("Location: parts.php?alert=ControlSystemAdded");
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
					cc32 = '$_POST[cc32]', 
					pdu = '$_POST[pdu]', 
					scu_sn = '$_POST[scu]'
					WHERE serial_nr = $_POST[serial_nr]";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: parts.php?alert=ControlSystemUpdated");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT deep_system
if($_GET['type'] == 'add_deep_system') {

	$sql_insert = "INSERT INTO deep_system (serial_nr, cooling_system, imu, pro_pack, deep_sensor_sn)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[cooling_system]',
					'$_POST[imu]', 
					'$_POST[pro_pack]', 
					'$_POST[deep_sensor]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		header("Location: parts.php?alert=DeepSystemAdded");
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
					deep_sensor_sn = '$_POST[deep_sensor]'
					WHERE serial_nr = $_POST[serial_nr]";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: parts.php?alert=DeepSystemUpdated");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT scu
if($_GET['type'] == 'add_scu') {

	$sql_insert = "INSERT INTO scu (serial_nr, configuration, digitaizer1, digitaizer2, sat, cpu, version, status)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[configuration]',
					'$_POST[digitaizer1]', 
					'$_POST[digitaizer2]', 
					'$_POST[sat]', 
					'$_POST[cpu]', 
					'$_POST[version]', 
					'$_POST[status]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		header("Location: parts.php?alert=SCUAdded");
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
					version = '$_POST[version]', 
					status = '$_POST[status]'
					WHERE serial_nr = $_POST[serial_nr]";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: parts.php?SCUUpdated");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT hv_card
if($_GET['type'] == 'add_hv_card') {

	$sql_insert = "INSERT INTO hv_card (serial_nr, configuration, art_nr, k_value, m_value, v_0, v_500, v_1000, v_1500, v_2000, v_2500, v_3000, v_3250)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[configuration]',
					'$_POST[art_nr]', 
					'$_POST[k_value]', 
					'$_POST[m_value]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		header("Location: parts.php?alert=HVCardAdded");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
} 

// UPDATE hv_card
if ($_GET['type'] == 'update_hv_card') {

	$sql_update = " UPDATE hv_card
					SET
					configuration = '$_POST[configuration]',
					art_nr = '$_POST[art_nr]', 
					k_value = '$_POST[k_value]', 
					m_value = '$_POST[m_value]'
					WHERE serial_nr = $_POST[serial_nr]";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: parts.php?alert=HVCardUpdated");
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
		header("Location: parts.php?alert=LaserAdded");
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
					WHERE serial_nr = $_POST[serial_nr]";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: parts.php?alert=LaserUpdated");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT leica_cam
if($_GET['type'] == 'add_leica_cam') {

	$sql_insert = "INSERT INTO leica_cam (serial_nr, configuration, breakdown, operating_voltage)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[configuration]', 
					'$_POST[breakdown]',
					'$_POST[operating_voltage]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		header("Location: parts.php?alert=LeicaCameraAdded");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
} 

// UPDATE leica_cam
if ($_GET['type'] == 'update_leica_cam') {

	$sql_update = " UPDATE leica_cam
					SET
					configuration = '$_POST[configuration]', 
					breakdown = '$_POST[breakdown]',
					operating_voltage = '$_POST[operating_voltage]'
					WHERE serial_nr = $_POST[serial_nr]";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: parts.php?alert=LeicaCameraUpdated");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

// INSERT receiver_chip
if($_GET['type'] == 'add_receiver_chip') {

	$sql_insert = "INSERT INTO receiver_chip (serial_nr, unit, firmware, art_nr)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[unit]', 
					'$_POST[firmware]',
					'$_POST[art_nr]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		header("Location: parts.php?alert=ReceiverChipAdded");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
} 

// UPDATE receiver_chip
if ($_GET['type'] == 'update_receiver_chip') {

	$sql_update = " UPDATE receiver_chip
					SET
					unit = '$_POST[unit]', 
					firmware = '$_POST[firmware]',
					art_nr = '$_POST[art_nr]'
					WHERE serial_nr = $_POST[serial_nr]";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: parts.php?alert=ReceiverChipUpdated");
		die();
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}
}

//INSERT POST values
if($_GET["type"] == 'add_message') { 
	$sql_insert = "INSERT INTO overview (message, author)
	VALUES ('$_POST[input_message]', '$_POST[input_name]')";

	if ($conn->query($sql_insert) === TRUE) {
    	echo "New record created successfully";
    	header("Location: index.php?alert=MessageAdded");
		die();
	} else {
    	echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}
$conn->close();  // close db
} // end if !empty
?>