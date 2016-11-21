
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
    
      $query = 'ALTER TABLE isp ADD deep_sensor VARCHAR(100) AFTER receiver';

      if($mysqli->query($query)) {
          echo "<br>Add deep_sensor";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }
    
      $query = 'ALTER TABLE isp ADD shallow_sensor VARCHAR(100) AFTER receiver';

      if($mysqli->query($query)) {
          echo "<br>Add shallow_sensor";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

      $query = 'ALTER TABLE isp ADD lss VARCHAR(100) AFTER receiver';

      if($mysqli->query($query)) {
          echo "<br>Add lss";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }

  } catch (Exception $e) {
      print($e->getMessage());
  }

  $mysqli->close();
  ?>
