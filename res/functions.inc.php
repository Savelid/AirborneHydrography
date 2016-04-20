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
			die("</select> Query failed!" . $sql . "<br>" . $conn->error);
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

<?php
function listAllX($select, $from, $where, $id){

	if($id != NULL && $id != ''){
		echo '<option value="' . $id . '">' . $id . '</option>';
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
	$sql_all = "  SELECT %s
	          FROM %s
						%s";
	$result_all = $conn->query(sprintf($sql_all, $select, $from, $where));
		if (!$result_all) {
			die("</select> Query failed!" . $sql . "<br>" . $conn->error);
		}
	while($row_all = $result_all->fetch_assoc()) {
		echo '<option value="' . $row_all[$select] . '">' . $row_all[$select] . '</option>';
	}
	$conn->close();
}
?>

<?PHP
	// Add all requests saved by this page to LOG
	function postToLogFormated($sql_string) {

		// Create connection
		include 'res/config.inc.php';
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		}

		$sql = " SELECT *
		FROM isp
		WHERE id = '$_POST[id]';";
		$result = $conn->query($sql);
		if (!$result) {
			die("Query failed! <br>Error:" . $sql . "<br>" . $conn->error ."<br><br> This is normal if adding a new id");
		}
		$current = $result->fetch_array(MYSQLI_ASSOC);

		$sql_log = "INSERT INTO log (type, user, sql_string, diff, serial_nr, comment)
					VALUES ('$_GET[type]', '$_POST[user]', '$sql_string', '$_POST[serial_nr]', '$_POST[log_comment]')";
		if ($conn->query($sql_log) === TRUE) {
			echo "Log created successfully";

		} else {
			echo "Error: " . $sql_log . "<br>" . $conn->error;
		}
	}
?>

<?php
/**
 * Send debug code to the Javascript console
 */
function debug_to_console($data) {
    if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
}
?>
