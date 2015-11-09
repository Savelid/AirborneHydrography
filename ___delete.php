<?php
// Check that type is deffined
if(isset($_GET['type']) && isset($_GET['serial_nr'])){

// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// DELETE system
if($_GET['type'] == 'system') {
	$sql_insert = "	DELETE FROM system
					WHERE system.serial_nr = $_GET[serial_nr]";
	if ($conn->query($sql_insert) === TRUE) {
		echo "Record deleted successfully";
		header("Location: systems.php?deleted");
		die();
	} else {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
	}
} 

$conn->close();  // close db
} // end if isset
?>