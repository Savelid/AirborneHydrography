<?php
function listUnusedSerialNr($from, $where, $serial_nr){

	if($serial_nr != NULL && $serial_nr != ''){
		echo '<option value="' . $serial_nr . '">' . $serial_nr . '</option>';
	}
	else {
		echo '<option></option>';
	}
	echo '<option>-----</option>';

	// open db
	include 'res/config.inc.php';
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	// Add all unused sensor units to the list
	$sql_unused = "  SELECT serial_nr
	          FROM %s
	          WHERE %s";
	$result_unused = $conn->query(sprintf($sql_unused, $from, $where));
		if (!$result_unused) {
			die("Query failed!");
		}
	while($row_unused = $result_unused->fetch_assoc()) {
		if(isset($_GET[$name_sn]) && $_GET[$name_sn] == $row_unused['serial_nr']){
			echo '<option value="' . $row_unused['serial_nr'] . '" autofocus selected="selected">' . $row_unused['serial_nr'] . '</option>';
		}else {
			echo '<option value="' . $row_unused['serial_nr'] . '">' . $row_unused['serial_nr'] . '</option>';
		}
	}
	$conn->close();
}
?>
