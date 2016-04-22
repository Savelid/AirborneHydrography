
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


      $query = 'ALTER TABLE sensor ADD dps_value_input_offset_rec_wide INTEGER AFTER dps_value_input_offset_rec';

      if($mysqli->query($query)) {
          echo "ADD dps_value_input_offset_rec_wide worked";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE sensor ADD mirror VARCHAR(30) AFTER fpga_id';

      if($mysqli->query($query)) {
          echo "<br>ADD mirror worked";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE flight DROP COLUMN ranging';

      if($mysqli->query($query)) {
          echo "<br>DROP ranging worked";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE leica
                MODIFY COLUMN type VARCHAR(30)';

      if($mysqli->query($query)) {
          echo "<br>ALTER datatype of leica|type worked";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE log
                ADD COLUMN changes TEXT';

      if($mysqli->query($query)) {
          echo "<br>ADD changes COLUMN to log worked";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'RENAME TABLE flight TO datasets;';

      if($mysqli->query($query)) {
          echo "<br>Changed neme of flight to datasets";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE datasets ADD UNIQUE (dataset_id);';

      if($mysqli->query($query)) {
          echo "<br>Add UNIQUE to dataset_id";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }


  } catch (Exception $e) {
      print($e->getMessage());
  }

  $mysqli->close();
  ?>
