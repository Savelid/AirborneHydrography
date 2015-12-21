<?php
// TODO: Deal with exeptions (like overview) where serial_nr is not a good key
session_start();
$_SESSION['user'] = $_POST['user'];
$_SESSION['showalert'] = 'true';
// Check that type is deffined
if(isset($_POST['table']) && isset($_POST['serial_nr'])){

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

  	$sql_log = "INSERT INTO log (type, user, sql_string, serial_nr, comment)
  				VALUES ('delete', '$_POST[user]', '$sql_string', '$_POST[serial_nr]', '$_POST[log_comment]')";
  	if ($conn->query($sql_log) === TRUE) {
  		echo "Log created successfully";

  	} else {
  		echo "Error: " . $sql_log . "<br>" . $conn->error;
  	}
  }

  // DELETE

  $sql_insert = "DELETE FROM $_POST[table]
  WHERE serial_nr = '$_POST[serial_nr]'";
  if ($conn->query($sql_insert) === TRUE) {
    echo "Record deleted successfully";
    postToLog(mysqli_real_escape_string($conn, $sql_insert));
    $_SESSION['alert'] = 'Item deleted';
    header("Location: admin_delete.php");
    die();
  } else {
    echo "Error: " . $sql_insert . "<br>" . $conn->error;
  }


  $conn->close();  // close db
} // end if isset
?>
