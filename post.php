<?php
// Check that type is deffined
if(isset($_GET['type'])){

// Create connection
include 'res/config.inc.php';
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

	$sql_log = "INSERT INTO log (type, user, sql_string, serial_nr) 
				VALUES ('$_GET[type]', '$_POST[user]', '$sql_string', '$_POST[serial_nr]')";
	if ($conn->query($sql_log) === TRUE) {
		echo "Log created successfully";
		
	} else {
		echo "Error: " . $sql_log . "<br>" . $conn->error;
	}
}

// INSERT system
if($_GET['type'] == 'add_system') {

	$sql_insert = "INSERT INTO system (serial_nr, art_nr, client, place, configuration, sensor_unit_sn, control_unit_sn, deep_system_sn, cooling_system, comment,
				status_potta_heat, status_shallow_heat, status_scu_pdu, status_hv_topo, status_hv_shallow, status_hv_deep, status_cat, status_pwr_cable)
	VALUES (		'$_POST[serial_nr]',
					'$_POST[art_nr]',
					'$_POST[client]', 
					'$_POST[place]',
					'$_POST[config]', 
					'$_POST[sensor_unit]', 
					'$_POST[control_unit]', 
					'$_POST[deep_system]', 
					'$_POST[cooling_system]', 
					'$_POST[comment]',

					'$_POST[status_potta_heat]', 
					'$_POST[status_shallow_heat]', 
					'$_POST[status_scu_pdu]', 
					'$_POST[status_hv_topo]', 
					'$_POST[status_hv_shallow]', 
					'$_POST[status_hv_deep]', 
					'$_POST[status_cat]', 
					'$_POST[status_pwr_cable]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_insert));
		header("Location: systems.php?new_system");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
} 

// UPDATE system
if ($_GET['type'] == 'update_system') {

	$sql_update = " UPDATE system
					SET serial_nr = '$_POST[serial_nr]',
						art_nr = '$_POST[art_nr]',
						client = '$_POST[client]',
						place = '$_POST[place]',
						configuration = '$_POST[config]',
						sensor_unit_sn = '$_POST[sensor_unit]', 
						control_unit_sn = '$_POST[control_unit]', 
						deep_system_sn = '$_POST[deep_system]', 
						cooling_system = '$_POST[cooling_system]',
						comment = '$_POST[comment]',

						status_potta_heat = '$_POST[status_potta_heat]',
						status_shallow_heat = '$_POST[status_shallow_heat]',
						status_scu_pdu = '$_POST[status_scu_pdu]',
						status_hv_topo = '$_POST[status_hv_topo]',
						status_hv_shallow = '$_POST[status_hv_shallow]',
						status_hv_deep = '$_POST[status_hv_deep]',
						status_cat = '$_POST[status_cat]',
						status_pwr_cable = '$_POST[status_pwr_cable]'
					WHERE serial_nr = $_POST[serial_nr]";
	if ($conn->query($sql_update) === TRUE) {
		echo "Record updated successfully";
		postToLog(mysqli_real_escape_string($conn, $sql_update));
		header("Location: systems.php?updated_system");
		die();
	} else {
		echo "Error2: " . $sql_update . "<br><br>" . $conn->error;
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
		header("Location: parts.php?added_sensor");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
} 

// UPDATE sensor
if ($_GET['type'] == 'update_sensor') {

	$sql_update = " UPDATE sensor
					SET serial_nr = '$_POST[serial_nr]',
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
		header("Location: parts.php?updated_sensor");
		die();
	} else {
		echo "Error2: " . $sql_update . "<br><br>" . $conn->error;
	}
}

//INSERT POST values
if($_GET["type"] == 'add_message') { 
	$sql_insert = "INSERT INTO overview (message, author)
	VALUES ('$_POST[input_message]', '$_POST[input_name]')";

	if ($conn->query($sql_insert) === TRUE) {
    	echo "New record created successfully";
    	header("Location: index.php?new_post");
		die();
	} else {
    	echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
}
$conn->close();  // close db
} // end if !empty
?>