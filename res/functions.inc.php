<?php
/*
* @description List all unreferenced serial_nr as options for a combobox.
* @param {String} from
* @param {String} where
* @param {String} id
* @returns {String} a list of results formated as HTML.
*/
function listUnusedSerialNr($from, $where, $id){
	$str = "";
	$list = listAll("SELECT serial_nr FROM " .$from. " WHERE " .$where);
	if ($list == NULL) {
		debug_to_console("listUnusedSerialNr: Receved a NULL list");
		$str = formatForEmptySelect($id);
	}else {
		$str = formatForSelect($list, $id);
	}
	echo $str;
}

/*
* @description List all items from one column as options for a combobox.
* @param {String} select
* @param {String} from
* @param {String} where
* @param {String} id
* @returns {String} a list of results formated as HTML.
*/
function listAllX($select, $from, $where, $id){

	$list = listAll('SELECT ' .$select. ' FROM ' .$from. ' ' .$where);
	if ($list == NULL) {
		debug_to_console("listAllX: Receved a NULL list");
	}else {
		$str = formatForSelect($list, $id);
		echo $str;
	}
}

/*
* @description Run a database query and return the resulting items as an array.
* @param {String} query
* @returns {Array} a list of results corresponding to the values of the queried columns.
*/
function listAll($qurey){
	// open db
	include 'res/config.inc.php';
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$result = $conn->query($qurey);
	if (!$result) {
		debug_to_console("Query failed!" . $qurey . "<br>" . $conn->error);
		die("Query failed!" . $qurey . "<br>" . $conn->error);
	}
	while($row =$result->fetch_array(MYSQLI_NUM)) {
		$list[] = $row[0];
	}
	$conn->close();

	if (isset($list)) {
		return $list;
	}else {
		return NULL;
	}
}

/*
* @description Formats a list if items into options for a combobox
* @param {Array} listOfItems
* @param {String} currentId
* @returns {String} a list of results formated in HTML looking something like:
*
* <option value="currentId">currentId</option>
* <option>-</option>
* <option value="item1">item1</option>
* <option value="item1">item2</option>
* <option value="item1">item3</option>
*/
function formatForSelect($listOfItems, $currentId){
	if($currentId != NULL && $currentId != ''){
		$return_string = '<option value="' . $currentId . '">' . $currentId . '</option>';
	}
	else {
		$return_string = '<option></option>';
	}
	$return_string .= '<option>-</option>';
	foreach ($listOfItems as $key => $value) {
		$return_string .= '<option value="' .$value. '">' .$value. '</option>';
	}

	return $return_string;
}

function formatForEmptySelect($currentId){
	if($currentId != NULL && $currentId != ''){
		$return_string = '<option value="' . $currentId . '">' . $currentId . '</option>';
	}
	else {
		$return_string = '<option></option>';
	}
	$return_string .= '<option>-</option>';
	return $return_string;
}

/*
* @description Send debug code to the Javascript console
* @param {String} data
*/
function debug_to_console($data) {
	if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
		echo("<script>console.log('PHP: ".$data."');</script>");
	}
}

/*
* @description
* Query for a database row, if there is an 'id_name' in GET.
* Add or update data in the database row, if there are POSTs.
* log the changes.
* Write feedback in to the alert box.
* Rederect to a given page.
* @param {String} id_name
* @param {String} table
* @param {String} database_columns
* @param {String} redirect
* @returns {Array} If this row is not empty. Key is column name, Value is value of column.
* @returns NULL If this row is empty.
*/
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
		header("Location: " . $redirect);
	}

	if (isset($row)) {
		return $row;
	}else {
		debug_to_console("postFunction: Return null");
		return NULL;
	}
}

/*
* @description
* Add or update data in the database row.
* Write changes as a string
* @param {String} table
* @param {String} id_name
* @param {String} id
* @param {String} database_columns
* @returns {Array} The keys are:
* updates {string} changes made to the database.
* query {string} A copy of the query used.
* type {string} Add/Update table name.
* status {string} Feedback text.
*/
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

		$sql = "SELECT * FROM $table WHERE $id_name = '$id';";
		$result = $conn->query($sql);
		if ($result->num_rows < 1) {
			$new = true;
		}else {
			$new = false;
		}

		if ($new) {
			$query = "INSERT INTO $table SET $id_name = '$id', " . $database_columns . ";";
			$type = 'Add';
			if ($conn->query($query) === TRUE) {
				$status = "New record created successfully";
				//split string
				$tags = explode(',',$database_columns);
				//print only those that are not empty
				foreach($tags as $key) {
					$empty_result = strpos($key, "''");
					$zero_result = strpos($key, "'0'");
					if ($empty_result === false && $zero_result === false) {
						$changes .= $key.'<br/>';
					}
				}
			}else{
				$status = "New record failed <br>" . $sql . "<br>" . $conn->error;
			}
		}

		if (!$new) {
			$row_assoc = $result->fetch_array(MYSQLI_ASSOC);
			$query = "UPDATE $table SET " . $database_columns . " WHERE $id_name = '$id' ;";
			$type = 'Update';
			if ($conn->query($query) === TRUE) {
				$status= "Record updated successfully";
				$sql = "SELECT * FROM $table WHERE $id_name = '$id';";
				$result2 = $conn->query($sql);
				if (!$result2) {
					$status .= "Failed to query new data :( <br>" . $sql . "<br>" . $conn->error;
				}else {
					$new_row_assoc = $result2->fetch_array(MYSQLI_ASSOC);
					if (sizeof($new_row_assoc) != sizeof($row_assoc)) {
						$changes .= "WARNING! The table might have been altered! Different amount of columns.<br>";
					}
					foreach ($new_row_assoc as $key => $value) {
						if (isset($row_assoc[$key])) {
							if (strcmp($row_assoc[$key], $value) !== 0) {
								$changes .= $key. " | " .$row_assoc[$key]. " -> " .$value. "<br>";
							}
						}else{
							$changes .= $key. " | " . "NEW VALUE". " -> " .$value. "<br>";
						}
					}
				}
			}else{
				$status= "Update failed <br>" . $sql . "<br>" . $conn->error;
			}
		}
		$conn->close();
		//debug_to_console("column_names: " .sizeof($column_names));
		//debug_to_console("Test!: " . $test_new_loop);
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

/*
* @description Querries the database and return exactly one row, or NULL
* @param {String} table - What table will be queried.
* @param {String} id_name - Set what column will be the key when picking one row
* @param {String} id - Set the id coresponding to the id_name
* @returns {Array} If this row is not empty. Key is column name, Value is value of column.
* @returns NULL If this row is empty.
*/
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

/*
* @description Add a row to the LOG
* @param {String} id
* @param {String} type
* @param {String} query
* @param {String} changes
* @param {String} user
* @param {String} comment
* @returns {String} Status feeldback.
*/
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

/*
* @description Upload a file from a form.
* @param {Array or String} input_file_type
* @param {String} name
* @param {String} name_prefix
* @param {String} target_dir
* @param {String} max_size (bytes)
* @returns {Array} The keys are:
* status_msg {string} Feedback text.
* upload_ok {boolean} Did the file upload.
* file_name {string} Just the name of the file.
* file_path {string} The full path for linking to the file.
*/
function uploadFile($input_file_type, $name, $name_prefix, $target_dir, $max_size){
	//debug_to_console("POST true. flight_logs: " . $_FILES["flight_logs"]["name"]);
	if ($_FILES[$name]['size'] > 0 && $_FILES[$name]['error'] == 0){
		debug_to_console($name. " not empty");
		$target_file = $target_dir . $name_prefix . basename($_FILES[$name]["name"]);
		$uploadOk = 1;

		// Check if file already exists
		if (file_exists($target_file)) {
    		$status_msg .= "File already exists. file will be overwriten <br>";
    		//$uploadOk = 0;
		}

		$isRightFileType = _uploadFile_test_fileType($target_file, $input_file_type);
		if(!$isRightFileType){
			$status_msg .= "Sorry, this file format is not allowed.";
			$uploadOk = 0;
		}
		$isRightSize = _uploadFile_test_size($name, $max_size);
		if(!$isRightSize){
			$status_msg .= "Sorry, this file has an invalid size.";
			$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    $status_msg .= " Your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES[$name]["tmp_name"], $target_file)) {
		        $status_msg .= "The file ". basename( $_FILES[$name]["name"]). " has been uploaded.";
		    } else {
		        $status_msg .= "Sorry, there was an error uploading your file.";
		    }
		}
		return array(	"status_msg" => $status_msg,
									"upload_ok" => $uploadOk,
									"file_name" => basename($_FILES[$name]["name"]),
									"file_path" => $target_file);
	}
	return NULL;
}

function _uploadFile_test_size($name, $max_size){
	// Check file size
	if ($_FILES[$name]["size"] > $max_size) {
		 return false;
	}else {
		return true;
	}
}

function _uploadFile_test_fileType($target_file, $input_file_type){
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Allow certain file formats
	if (is_array($input_file_type)) {
		foreach ($input_file_type as $key => $value) {
			if($fileType == $value) {
					return true;
			}
		}
	}else{
		if($fileType == $input_file_type) {
				return true;
		}
	}
	return false;
}

/*
* @description Format the specialy formated strings for comments as HTML.
* @param {String} string
* @returns {String} HTML.
*/
function formatComment($string){
	$comments = explode("&|", $string);
	$return_string = "";
	foreach ($comments as $key => $value) {
		if (!empty($value[0])) {
			if ($value[0] == "A") {
				$return_string .= '<b>' .substr($value, 1). '</b>';
			}elseif ($value[0] == "D") {
				$return_string .= ' - <i>' .substr($value, 1). '</i>';
			}elseif ($value[0] == "M") {
				$return_string .= '<p>' .nl2br(substr($value, 1)). '</p>';
			}else{
				$return_string .= $value;
			}
		}
	}
	return $return_string;
}
?>
