<?php
session_start();
$_SESSION['user'] = $_POST['user'];
$_SESSION['showalert'] = 'true';
include 'res/config.inc.php';
// Check that type is deffined
$success = 'false';
if(isset($_POST['serial_nr']) && isset($_POST['table'])){

		// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	$sql_update = "UPDATE $_POST[table] SET $_POST[column] = '' WHERE $_POST[column] = '$_POST[serial_nr]'";

	if ($conn->query($sql_update) === TRUE) {
		$success = 'true';
		echo "Success: " . $sql_update . "<br><br>" . $conn->error;
	} else {
		echo "Error: " . $sql_update . "<br><br>" . $conn->error;
	}

	if(isset($_POST['column2']) && $_POST['column2'] != ''){

		$sql_update = "UPDATE $_POST[table] SET $_POST[column2] = '' WHERE $_POST[column2] = '$_POST[serial_nr]'";

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
		$type = 'EjectFrom_' . $_POST['table'];
		$sql_log = "INSERT INTO log (type, user, sql_string, serial_nr, comment) 
					VALUES ('$type', '$_POST[user]', '$sql_string', '$_POST[serial_nr]', '$_POST[log_comment]')";
		if ($conn->query($sql_log) === TRUE) {
			echo "Log created successfully";
			
		} else {
			echo "Error: " . $sql_log . "<br>" . $conn->error;
		}
		$_SESSION['alert'] = 'Item removed';
		header("Location: parts.php");
		die();
	}
	$conn->close(); // close connection
}
?>
	<br>
  	<a href="parts.php">Back</a>
