<?php
// @param id_name - Set what column will be the key when picking one item for updates.
// @param table - What table will the data be stored in.
// @param database_columns - a string with all columns and the values.
// @param redirect - Where will the user be sent after the query is done.
// @return array with results from databse.

function postFunction($id_name, $table, $database_columns, $redirect){

	include 'res/config.inc.php';
	$run_query = false;
	$changes = "";
	$id = "";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Get id when posting to the database.
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if (isset($_POST[$id_name])) {
			$id = $_POST[$id_name];
			$_SESSION['user'] = $_POST['user'];
		}
		$_SESSION['showalert'] = 'true';
		$_SESSION['alert'] = "";
		$run_query = true;
		debug_to_console("id copied from POST");
	}

	// Get id when showing data in the form.
	if (!empty($_GET[$id_name])) {
		$id = $_GET[$id_name];
		$run_query = true;
		debug_to_console("id copied from GET");
	}

	// Get the names of the columns. Will be shown toghether with the list of changes
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
		debug_to_console("before run_query: " . $id . " ID name: " . $id_name);
	if ($run_query) { // If not a new empty form.
		debug_to_console("run_query");
		$sql = "SELECT * FROM $table WHERE $id_name = '$id';";
		$result = $conn->query($sql);
		if ($result->num_rows < 1) {
			echo $sql;
			debug_to_console("Query for this id failed");
			if ($_SERVER['REQUEST_METHOD'] == 'POST'){
				debug_to_console("Creating new row");
				$sql_insert = "INSERT INTO $table SET $id_name = '$id', " . $database_columns . ";";
				$type = 'Add ' . $table;
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
					postToLogFormated($id_name, $type, mysqli_real_escape_string($conn, $sql_insert), mysqli_real_escape_string($conn, $changes));
				}else{
					$_SESSION['alert'] .= "New record failed <br>" . $sql . "<br>" . $conn->error;
				}
				header("Location: " . $redirect);
			}
		}else {
			$row = $result->fetch_array(MYSQLI_BOTH);
			debug_to_console("result added to row");
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
				$sql_insert = "UPDATE $table SET " . $database_columns . " WHERE $id_name = '$id' ;";
				$type = 'Update ' . $table;
				if ($conn->query($sql_insert) === TRUE) {
					$_SESSION['alert'] .= "Record updated successfully <br>";
					$sql = "SELECT * FROM $table WHERE $id_name = '$id';";
					$result2 = $conn->query($sql);
					if (!$result2) {
						$_SESSION['alert'] .= "Failed to query new data :( <br>" . $sql . "<br>" . $conn->error;
					}else {
						$new_row = $result2->fetch_array(MYSQLI_NUM);
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
						postToLogFormated($id_name, $type, mysqli_real_escape_string($conn, $sql_insert), mysqli_real_escape_string($conn, $changes));
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
	function postToLogFormated($this_id_name, $this_type, $this_sql_string, $this_changes) {
		$this_serial_nr = $_POST[$this_id_name];
		// if (!empty($_POST['serial_nr'])) {
		// 	$this_serial_nr = $_POST['serial_nr'];
		// }
		// elseif (!empty($_POST['isp_nr'])) {
		// 	$this_serial_nr = $_POST['isp_nr'];
		// }
		// elseif (!empty($_POST['dataset_id'])) {
		// 	$this_serial_nr = $_POST['dataset_id'];
		// }
		// elseif(!empty($_POST['id'])) {
		// 	$this_serial_nr = $_POST['id'];
		// }

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
