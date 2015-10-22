<?php
include 'res/config.inc.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(!empty($_GET['type']) && $_GET['type'] == 'system') {
	if(!empty($_GET['new']) && $_GET['new'] == 'TRUE') {
		$sql_insert = "INSERT INTO system (serial_nr, art_nr, client, configuration, sensor_unit_sn, control_unit_sn, deep_system_sn, cooling_system, comment)
		VALUES ('$_POST[serial_nr]', '$_POST[art_nr]', '$_POST[client]', '$_POST[config]', '$_POST[sensor_unit]', '$_POST[control_unit]', '$_POST[deep_system]', '$_POST[cooling_system]', '$_POST[comment]')";
		if ($conn->query($sql_insert) === TRUE) {
    		echo "New record created successfully";
		} else {
    		echo "Error: " . $sql_insert . "<br>" . $conn->error;
		}
	} else if ((!empty($_GET['new']) && $_GET['new'] == 'FALSE')) {
		$sql_insert = " UPDATE system
						SET serial_nr = $_POST[serial_nr],
							art_nr = $_POST[art_nr], 
							sensor_unit_sn = $_POST[sensor_unit], 
							control_unit_sn = $_POST[control_unit], 
							deep_system_sn = $_POST[deep_system], 
							cooling_system = $_POST[cooling_system]
						WHERE serial_nr = $_POST[serial_nr]";
		if ($conn->query($sql_insert) === TRUE) {
    		echo "Record updated successfully";
		} else {
    		echo "Error: " . $sql_insert . "<br>" . $conn->error;
		}
	}
	header("Location: systems.php");
	die();
}

//INSERT POST values
if(isset($_POST["input_name"])) { 
	$sql_insert = "INSERT INTO overview (message, author)
	VALUES ('$_POST[input_message]', '$_POST[input_name]')";

	if ($conn->query($sql_insert) === TRUE) {
    	echo "New record created successfully";
	} else {
    	echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
	header("Location: index.php");
	die();
}
$conn->close();
?>