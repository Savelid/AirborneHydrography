
<?php
  include 'res/config.inc.php';

  $host = $servername;
  $user = $username;
  $database = $dbname;


  try {
      // open the connection to the database - $host, $user, $password, $database should already be set
      $mysqli = new mysqli($host, $user, $password, $database);

      // did it work?
      if ($mysqli->connect_errno) {
          throw new Exception("Failed to connect to MySQL: " . $mysqli->connect_error);
      }
      ////////////////////////////////////


      $query = 'ALTER TABLE datasets ADD disc_id VARCHAR(100) AFTER datetime';

      if($mysqli->query($query)) {
          echo "<br>ADD disc_id worked";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE datasets ADD leica_pav_sn VARCHAR(30) AFTER imu_2_sn';

      if($mysqli->query($query)) {
          echo "<br>ADD leica_pav_sn worked";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE datasets ADD leica_cam_sn VARCHAR(30) AFTER leica_pav_sn';

      if($mysqli->query($query)) {
          echo "<br>ADD leica_cam_sn worked";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'CREATE TABLE calibration (

      	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
      	calibration_id VARCHAR(50) NOT NULL UNIQUE,
      	datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      	dataset_id VARCHAR(50),
      	comment TEXT,

      	PRIMARY KEY (id)
      );';

      if($mysqli->query($query)) {
          echo "<br>CREATE calibration TABLE";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE datasets CHANGE evaluation_of_flight flight_comments TEXT';

      if($mysqli->query($query)) {
          echo "<br>CHANGE name of evaluation_of_flight";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE datasets CHANGE data_evaluation data_comments TEXT';

      if($mysqli->query($query)) {
          echo "<br>CHANGE name of data_evaluation";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE datasets CHANGE system_fully_functional system_not_working BOOLEAN';

      if($mysqli->query($query)) {
          echo "<br>CHANGE name of system_fully_functional to system_not_working";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE datasets ADD delivered_data_in_archive BOOLEAN AFTER system_not_working';

      if($mysqli->query($query)) {
          echo "<br>Add delivered_data_in_archive";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE datasets ADD camera_calibration BOOLEAN AFTER delivered_data_in_archive';

      if($mysqli->query($query)) {
          echo "<br>Add camera_calibration";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE calibration ADD calibration_file TEXT AFTER dataset_id';

      if($mysqli->query($query)) {
          echo "<br>Add calibration_file";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE datasets ADD calibration_id VARCHAR(100) AFTER type_of_data';

      if($mysqli->query($query)) {
          echo "<br>Add calibration_id to datasets";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

  } catch (Exception $e) {
      print($e->getMessage());
  }

  $mysqli->close();
  ?>
