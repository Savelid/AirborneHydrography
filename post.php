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

// INSERT system
if($_GET['type'] == 'add_system') {

	$sql_insert = "INSERT INTO system (serial_nr, art_nr, client, configuration, sensor_unit_sn, control_unit_sn, deep_system_sn, cooling_system, comment,
				status_potta_heat, status_shallow_heat, status_scu_pdu, status_hv_topo, status_hv_shallow, status_hv_deep, status_cat, status_pwr_cable)
	VALUES ('$_POST[serial_nr]', '$_POST[art_nr]', '$_POST[client]', '$_POST[config]', '$_POST[sensor_unit]', '$_POST[control_unit]', '$_POST[deep_system]', '$_POST[cooling_system]', '$_POST[comment]',
				'$_POST[status_potta_heat]', '$_POST[status_shallow_heat]', '$_POST[status_scu_pdu]', '$_POST[status_hv_topo]', '$_POST[status_hv_shallow]', '$_POST[status_hv_deep]', '$_POST[status_cat]', '$_POST[status_pwr_cable]')";
	if ($conn->query($sql_insert) === TRUE) {
		echo "New record created successfully";
		header("Location: systems.php?new_system");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
} 

// UPDATE system
if ($_GET['type'] == 'update_system') {

	$sql_update2 = " UPDATE system
					SET serial_nr = '$_POST[serial_nr]',
						art_nr = '$_POST[art_nr]',
						client = '$_POST[client]',
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
	if ($conn->query($sql_update2) === TRUE) {
		echo "Record updated successfully";
		header("Location: systems.php?updated_system");
		die();
	} else {
		echo "Error2: " . $sql_update2 . "<br><br>" . $conn->error;
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