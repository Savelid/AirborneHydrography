<?php
session_start();
$titel = 'Data sets';
include 'res/header.inc.php';
?>

<section class="content hidden-print">

  <?php
  if(isset($_SESSION['alert']) && isset($_SESSION['showalert']) && $_SESSION['showalert'] == 'true') {
    $_SESSION['showalert'] = 'false';
    echo '
    <div class="alert alert-warning alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    ';
    echo $_SESSION['alert'];
    echo '
    </div>
    ';
  }
  ?>

  <a href="edit_flight.php" class="btn btn-default navbar-btn" role="button">New data set</a>
  <form class="navbar-form navbar-right" action= <?php echo htmlspecialchars($_SERVER['PHP_SELF'] ); ?> method="GET">
    <div class="form-group">
          <input type="text" class="form-control" placeholder="Search" name="search">
        </div>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
  </form>
</section>

<section>

  <?php

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "  SELECT * FROM flight ";
  if(isset($_GET['search'])){
    $sql .= "WHERE datetime LIKE '%" . $_GET['search'] . "%'
    OR dataset_id LIKE '%" . $_GET['search'] . "%'
    OR location LIKE '%" . $_GET['search'] . "%'
    OR system_id LIKE '%" . $_GET['search'] . "%'
    OR system_model LIKE '%" . $_GET['search'] . "%'
    OR topo_sensor_1_sn LIKE '%" . $_GET['search'] . "%'
    OR topo_sensor_2_sn LIKE '%" . $_GET['search'] . "%'
    OR shallow_sensor_sn LIKE '%" . $_GET['search'] . "%'
    OR deep_sensor_sn LIKE '%" . $_GET['search'] . "%'
    OR scu_sn LIKE '%" . $_GET['search'] . "%'
    OR imu_1_sn LIKE '%" . $_GET['search'] . "%'
    OR imu_2_sn LIKE '%" . $_GET['search'] . "%'";
  }
  $sql .= "ORDER BY datetime DESC";
  $result = $conn->query($sql);
  if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
  }
  $conn->close();

  if ($result->num_rows > 0) {

    echo '
    <div class="table-responsive">
    <table class="table table-striped">
    <thead>
    <tr>
    <th>Date</th>
    <th>Dataset</th>
    <th>Location</th>
    <th>System</th>
    <th>Sys.Model</th>
    <th>Topo1</th>
    <th>Topo2</th>
    <th>Shallow</th>
    <th>Deep</th>
    <th>SCU</th>
    <th>IMU1</th>
    <th>IMU2</th>
    </tr>
    </thead>
    <tbody>';

    // %3$s will be replaced with variables later
    $table_row_formating =
    '
    <tr>

    <td> %1$s </td>
    <td><a href="view_flight.php?id=%13$s"> %2$s </a></td>
    <td> %3$s </td>
    <td><a href="view_system.php?system=%4$s"> %4$s </a></td>
    <td> %5$s </td>
    <td><a href="view_sensor.php?serial_nr=%6$s"> %6$s </a></td>
    <td><a href="view_sensor.php?serial_nr=%7$s"> %7$s </a></td>
    <td><a href="view_sensor.php?serial_nr=%8$s"> %8$s </a></td>
    <td><a href="view_sensor.php?serial_nr=%9$s"> %9$s </a></td>
    <td> %10$s </td>
    <td> %11$s </td>
    <td> %12$s </td>

    </tr>';
    // output data of each row
    while($row = $result->fetch_assoc()) {

      // // shorten too long client names
      // $client = $row["client"];
      // if (strlen ($client) >= 14) {
      //   $client = substr($client, 0, 12) . "..";
      // }
      // // shorten too long comments
      // $comment = $row["comment"];
      // if (strlen ($comment) >= 32) {
      //   $comment = substr($comment, 0, 30) . "..";
      // }

      echo sprintf($table_row_formating,
      substr($row["datetime"], 0 , 10),
      $row["dataset_id"],
      $row["location"],
      $row["system_id"],
      $row["system_model"],
      $row["topo_sensor_1_sn"],
      $row["topo_sensor_2_sn"],
      $row["shallow_sensor_sn"],
      $row["deep_sensor_sn"],
      $row["scu_sn"],
      $row["imu_1_sn"],
      $row["imu_2_sn"],

      $row["id"]);
    }
    echo '
    </tbody>
    </table>
    </div>';
  } else {
    echo "Empty table or no search results";
  }
  ?>

</section>
<footer>

</footer>

<?php
include 'res/footer.inc.php';
?>
