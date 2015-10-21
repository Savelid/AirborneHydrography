<?php
function db_connect() {
	$host     = "savelid.dev"; //databasvärd
	$user     = "dbuser"; //loginnamn
	$password = "123"; //mysql-lösenord
	$database = "ahab_db"; //databas
	$link = @mysql_connect($host, $user, $password) or die("Error: Could not contact the database server!");
	@mysql_select_db($database) or die("Error: There was a problem with the database!");
	return $link;
}
?> 