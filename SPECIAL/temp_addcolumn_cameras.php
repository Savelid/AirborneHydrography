
<?php
  include 'res/config.inc.php';

  $host = $servername;
  $user = $username;
  $database = $dbname;

// CONNECT TO DATABASE ************************************
  try {
      // open the connection to the database - $host, $user, $password, $database should already be set
      $mysqli = new mysqli($host, $user, $password, $database);

      // did it work?
      if ($mysqli->connect_errno) {
          throw new Exception("Failed to connect to MySQL: " . $mysqli->connect_error);
      }
// ************************************************************

// ADD NEW COLUMN TO A TABLE IN THE DATABASE ******************

      $query = 'ALTER TABLE sensor_unit ADD leica_config VARCHAR(30) AFTER leica_cam_sn';

      if($mysqli->query($query)) {
          echo "<br>ADD disc_id worked";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }


      $query = 'ALTER TABLE sensor_unit ADD microeye_config VARCHAR(30) AFTER shallow_sensor_sn';

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
