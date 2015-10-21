<?php
include 'res/config.inc.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
}
$conn->close();

header("Location: index.php");
die();
?>