<?php
include 'res/config.inc.php';
// Check that type is deffined
$success = 'false';
if(isset($_GET['serial_nr']) && isset($_GET['table'])){

		// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	$sql_update = "UPDATE $_GET[table] SET $_GET[column] = '' WHERE $_GET[column] = '$_GET[serial_nr]'";

	if ($conn->query($sql_update) === TRUE) {
		$success = 'true';
		echo "Success: " . $sql_update . "<br><br>" . $conn->error;
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}

	if(isset($_GET['column2']) && $_GET['column2'] != ''){

		$sql_update = "UPDATE $_GET[table] SET $_GET[column2] = '' WHERE $_GET[column2] = '$_GET[serial_nr]'";

		if ($conn->query($sql_update) === TRUE) {
			$success = 'true';
			echo "Success: " . $sql_update . "<br><br>" . $conn->error;
		} 
		else {
			echo "Error: " . $sql_update . "<br><br>" . $conn->error;
		}
	}
	if($success){
		$sql_string = mysqli_real_escape_string($conn, $sql_update);
		$sql_log = "INSERT INTO log (type, user, sql_string, serial_nr, comment) 
					VALUES ('Eject', 'Unknown', '$sql_string', '$_GET[serial_nr]', 'Ejected from the parts page')";
		if ($conn->query($sql_log) === TRUE) {
			echo "Log created successfully";
			
		} else {
			echo "Error: " . $sql_log . "<br>" . $conn->error;
		}
		header("Location: parts.php?alert=removed");
		die();
	}
	$conn->close(); // close connection
}
?>
	<br>
  	<a href="parts.php?alert=removed">Back</a>
