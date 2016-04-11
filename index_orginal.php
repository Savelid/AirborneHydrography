<?php

if(!$dbConfig = parse_ini_file("/home/ahab/.dbconfig.ini", TRUE)) throw new exception ('Unable to open DB config file.');

$dbHost = $dbConfig['database']['host'];
$dbName = $dbConfig['database']['dbname'];
$dbUser = $dbConfig['database']['username'];
$dbPass = $dbConfig['database']['password'];

$pdo = new PDO("mysql:host=$dbHost; dbname=$dbName", $dbUser, $dbPass );

if($pdo) {
	echo "DB Connection is up.";
} else {
	echo "Could not open DB connection.";
}
