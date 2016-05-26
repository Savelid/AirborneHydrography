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

<?php
// @param id_name - Set what column will be the key when picking one item for updates.
// @param table - What table will the data be stored in.
// @param database_columns - a string with all columns and the values.
// @param redirect - Where will the user be sent after the query is done.
// @return array with results from databse.

function postFunction($id_name, $table, $database_columns, $redirect){

	if(!empty($_GET[$id_name])){
		$row = getDatabaseRow($table, $id_name, $_GET[$id_name]);
	}
	if (isset($_POST[$id_name])) {
		$post_status = postToDatabase($table, $id_name, $_POST[$id_name], $database_columns);

		$_SESSION['showalert'] = 'true';
		$_SESSION['alert'] = $table . ": " . $post_status['status'];
		$_SESSION['alert'] .= "<br><br>";
		$_SESSION['alert'] .= $post_status['updates'];

		$log_status = postToLog($_POST[$id_name], $post_status['type'] . " " . $table, $post_status['query'], $post_status['updates'], $_POST['user'], $_POST['log_comment']);
		//header("Location: " . $redirect);
	}

	if (isset($row)) {
		return $row;
	}else {
		debug_to_console("postFunction: Return null");
		return NULL;
	}
}
?>

<?php
// @param table - What table will the data be stored in.
// @param id_name - Set what column will be the key when picking one item for updates.
// @param id - Set the id coresponding to the id_name
// @param database_columns - a string with all columns and the values.
// @return an array with strings.
// 'changes' made in the current row.
// 'query' used to post to database.
// 'type' update or add
//
// Updating a current row if awailable, otherwise posting to a new row.
// Also makes a string with changes done to the affected row.

function postToDatabase($table, $id_name, $id, $database_columns){
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$changes = "";
		$query = "";
		$type = "";
		$status = "";
		//session_start();
		include 'res/config.inc.php';
		// Make sure username is saved between pages.
		if(!empty($_POST['user'])){
			$_SESSION['user'] = $_POST['user'];
		}
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
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

		$sql = "SELECT * FROM $table WHERE $id_name = '$id';";
		$result = $conn->query($sql);
		if ($result->num_rows < 1) {

				debug_to_console("Creating new row");
				$query = "INSERT INTO $table SET $id_name = '$id', " . $database_columns . ";";
				$type = 'Add';
				if ($conn->query($query) === TRUE) {
					$status = "New record created successfully";
					//split string
					$tags = explode(',',$database_columns);
					//print only those that are not empty
					foreach($tags as $key) {
						$pos = strpos($key, "''");
						if ($pos === false) {
	    				$changes .= $key.'<br/>';
						}
					}
				}else{
					$status = "New record failed <br>" . $sql . "<br>" . $conn->error;
				}

		}else {
			$row = $result->fetch_array(MYSQLI_BOTH);
			debug_to_console("result added to row");
				$query = "UPDATE $table SET " . $database_columns . " WHERE $id_name = '$id' ;";
				$type = 'Update';
				if ($conn->query($query) === TRUE) {
					$status= "Record updated successfully";
					$sql = "SELECT * FROM $table WHERE $id_name = '$id';";
					$result2 = $conn->query($sql);
					if (!$result2) {
						$status .= "Failed to query new data :( <br>" . $sql . "<br>" . $conn->error;
					}else {
						$new_row = $result2->fetch_array(MYSQLI_NUM);
						for ($x = 0; $x <= count($new_row); $x++) {
							if(!empty($new_row[$x]) && !empty($new_row[$x])){
								if(strcmp($new_row[$x], $row[$x]) === 0) {
									debug_to_console("Skip unchanged item");
								}else {
									$this_col = "";
									if (isset($column_names)) {
										//$this_col = $column_names[$x];
										$this_col = sprintf("%20s", $column_names[$x]); // left-justification with spaces
									}
									$changes .=  $this_col ." | ". $row[$x] ." -> ".$new_row[$x]."<br>";
								}
							}else {
								debug_to_console("Minor error: Empty row");
							}
						}
					}
				}else{
					$status= "Update failed <br>" . $sql . "<br>" . $conn->error;
				}
		}
		$conn->close();
		return array(
	    'updates'  => $changes,
	    'query' => $query,
	    'type' => $type,
	    'status' => $status
		);
	}
	debug_to_console("postToDatabase: Return null");
	return NULL;
}
?>

<?php
// @param table - What table will be queried.
// @param id_name - Set what column will be the key when picking one row
// @param id - Set the id coresponding to the id_name
// @return array with results from databse.
//
// Querries the database and return exactly one row, or NULL

function getDatabaseRow($table, $id_name, $id){

	include 'res/config.inc.php';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM $table WHERE $id_name = '$id';";
	$result = $conn->query($sql);
	if ($result->num_rows < 1) {
			echo $sql;
			debug_to_console("Query for this id failed, no results");
	}
	elseif ($result->num_rows > 1) {
			echo $sql;
			debug_to_console("Query for this id failed, too many results");
	}else {
			$row = $result->fetch_array(MYSQLI_BOTH);
			debug_to_console("result added to row");
	}

	$conn->close();
	if (isset($row)) {
		return $row;
	}else {
		debug_to_console("getDatabaseRow: Return null");
		return NULL;
	}
}
?>

<?PHP
	// Add all requests saved by this page to LOG
	function postToLog($id, $type, $query, $changes, $user, $comment) {

		// Create connection
		include 'res/config.inc.php';
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		    die("Log: db connection failed: " . $conn->connect_error);
		}
		$query = $conn->real_escape_string($query);
		$changes = $conn->real_escape_string($changes);
		$sql_log = "INSERT INTO log SET type = '$type', user = '$user', sql_string = '$query', changes = '$changes', serial_nr = '$id', comment = '$comment';";
		if ($conn->query($sql_log) === TRUE) {
			$status = "Log created successfully";

		} else {
			$status = "Log Error: " . $sql_log . "<br>" . $conn->error;
		}
		$conn->close();
		return $status;
	}
?>
