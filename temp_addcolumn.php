
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
          echo "ADD disc_id worked";
      }
      else {
        echo "Error: " . $query . "<br><br>" . $mysqli->error;
      }


  } catch (Exception $e) {
      print($e->getMessage());
  }

  $mysqli->close();
  ?>
