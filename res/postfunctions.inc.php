<?php
function postFunction($table, $database_columns, $redirect){

	include 'res/config.inc.php';
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$run_query = false;
	$changes = "";
	$id = "";
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if (isset($_POST['id'])) {
			$id = $_POST['id'];
		}
		debug_to_console("id copied from POST to GET");
		$_SESSION['showalert'] = 'true';
		$_SESSION['alert'] = "";
		$run_query = true;
	}
	if (!empty($_GET['id'])) {
		$id = $_GET['id'];
		$run_query = true;
	}

	$sql_col_names = "SHOW COLUMNS FROM $table;";
	$result_col_names = $conn->query($sql_col_names);
	if ($result_col_names->num_rows > 0) {
	    // output data of each row
			$i = 0;
	    while($row_col_names = $result_col_names->fetch_assoc()) {
				$column_names[$i] = $row_col_names['Field'];
				$i++;
			}
	}

	if ($run_query) {

		$sql = "SELECT * FROM $table WHERE id = '$id';";
		$result = $conn->query($sql);
		if ($result->num_rows < 1) {
			debug_to_console("Query for this id failed");
			if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				debug_to_console("Creating new Dataset");
				$sql_insert = "INSERT INTO $table SET id = '$id', " . $database_columns . ";";
				$type = 'Add' . $table;
				if ($conn->query($sql_insert) === TRUE) {
					$_SESSION['alert'] .= "New record created successfully <br>";
					//split string
					$tags = explode(',',$database_columns);
					//print only those that are not empty
					foreach($tags as $key) {
						$pos = strpos($key, "''");
						if ($pos === false) {
	    				$changes .= $key.'<br/>';
						}
					}
					$_SESSION['alert'] .= "<br/>" . $changes;
					postToLogFormated($type, mysqli_real_escape_string($conn, $sql_insert), mysqli_real_escape_string($conn, $changes));
				}else{
					$_SESSION['alert'] .= "New record failed <br>" . $sql . "<br>" . $conn->error;
				}
				header("Location: " . $redirect);
			}
		}else {
			$row = $result->fetch_array(MYSQLI_BOTH);
			debug_to_console("result added to row");
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$sql_insert = "UPDATE $table SET " . $database_columns . " WHERE id = '$id' ;";
				$type = 'Update ' . $table;
				if ($conn->query($sql_insert) === TRUE) {
					$_SESSION['alert'] .= "Record updated successfully <br>";
					$sql = "SELECT * FROM $table WHERE id = '$id';";
					$result2 = $conn->query($sql);
					if (!$result2) {
						$_SESSION['alert'] .= "Failed to query new data :( <br>" . $sql . "<br>" . $conn->error;
					}else {
						$new_row = $result2->fetch_array(MYSQLI_BOTH);
						for ($x = 0; $x <= count($new_row); $x++) {
							if(strcmp($new_row[$x], $row[$x]) != 0) {
								$this_col = "";
								if (isset($column_names)) {
									//$this_col = $column_names[$x];
									$this_col = sprintf("%20s", $column_names[$x]); // left-justification with spaces
								}
								$changes .=  $this_col ." | ". $row[$x] ." -> ".$new_row[$x]."<br>";
							}
						}
						$_SESSION['alert'] .= "<br/>" . $changes;
						postToLogFormated($type, mysqli_real_escape_string($conn, $sql_insert), mysqli_real_escape_string($conn, $changes));
					}
				}else{
					$_SESSION['alert'] .= "Update failed <br>" . $sql . "<br>" . $conn->error;
				}
				header("Location: " . $redirect);
			}
		}

	}
				//postToLog(mysqli_real_escape_string($conn, $sql_insert));

	$conn->close();
	if (isset($row)) {
		return $row;
	}
}
?>

<?PHP
	// Add all requests saved by this page to LOG
	function postToLogFormated($this_type, $this_sql_string, $this_changes) {
		$this_serial_nr = "";
		if (!empty($_POST['serial_nr'])) {
			$this_serial_nr = $_POST['serial_nr'];
		}
		elseif (!empty($_POST['isp_nr'])) {
			$this_serial_nr = $_POST['isp_nr'];
		}
		elseif (!empty($_POST['dataset_id'])) {
			$this_serial_nr = $_POST['dataset_id'];
		}
		elseif(!empty($_POST['id'])) {
			$this_serial_nr = $_POST['id'];
		}

		// Create connection
		include 'res/config.inc.php';
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Log: db connection failed: " . $conn->connect_error);
		}
		$sql_log = "INSERT INTO log SET type = '$this_type', user = '$_POST[user]', sql_string = '$this_sql_string', changes = '$this_changes', serial_nr = '$this_serial_nr', comment = '$_POST[log_comment]';";
		if ($conn->query($sql_log) === TRUE) {
			$_SESSION['alert'] .= "<br/>Log created successfully";

		} else {
			$_SESSION['alert'] .= "<br/>Log Error: " . $sql_log . "<br>" . $conn->error;
		}
	}
?>
