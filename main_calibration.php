<?php
session_start();
$titel = 'Datasets';
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

  <a href="edit_calibration.php" class="btn btn-default navbar-btn" role="button">New calibration</a>
  <form class="navbar-form navbar-right" action= <?php echo htmlspecialchars($_SERVER['PHP_SELF'] ); ?> method="GET">
    <div class="form-group">
          <input type="text" class="form-control" placeholder="Search" name="search">
        </div>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
  </form>
</section>

<section>

  <div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Date</th>
        <th>Calibration</th>
        <th>Dataset</th>
        <th>Type</th>
        <th>System</th>
        <th class="hidden-print">Calib. file</th>
        <th class="hidden-print">Comment</th>
      </tr>
    </thead>
    <tbody>

<?php

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "  SELECT calibration.datetime AS datetime, calibration.dataset_id AS dataset_id, calibration.calibration_id AS calibration_id, calibration.calibration_file AS calibration_file, comment, system_id, type_of_data FROM calibration ";
$sql .= " LEFT JOIN datasets ON calibration.dataset_id=datasets.dataset_id";
if(isset($_GET['search'])){
  $sql .= " WHERE calibration.datetime LIKE '%" . $_GET['search'] . "%'
  OR calibration.dataset_id LIKE '%" . $_GET['search'] . "%'
  OR calibration.calibration_id LIKE '%" . $_GET['search'] . "%'
  OR comment LIKE '%" . $_GET['search'] . "%'
  OR system_id LIKE '%" . $_GET['search'] . "%'
  OR type_of_data LIKE '%" . $_GET['search'] . "%'
  ";
}
$sql .= " ORDER BY calibration.datetime DESC";
$result = $conn->query($sql);
if (!$result) {
  echo $sql . "<br><br>" . $conn->error;
  die("Query failed!");
}
$conn->close();

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $commentButton = '
      <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-html="true" data-placement="left" data-content="'
      . $row["comment"] .
      '">View</button>';

      $calibrationFileButton = '
      <a href="'.$row["calibration_file"].'" class="btn btn-sm btn-default">PDF</a>';

      echo '<tr>';
      echo '<td>' . substr($row["datetime"], 0 , 10)  . '</td>';
      echo '<td> <a href="edit_calibration.php?calibration_id=' . $row["calibration_id"] . '">' . $row["calibration_id"] . '</a> </td>';
      echo '<td> <a href="view_datasets.php?dataset_id=' . $row["dataset_id"] . '">' . $row["dataset_id"] . '</a> </td>';
      echo '<td>' . $row["type_of_data"]      . '</td>';
      echo '<td>' . $row["system_id"] . '</td>';
      echo '<td class="hidden-print">' . $calibrationFileButton . '</td>';
      echo '<td class="hidden-print">' . $commentButton . '</td>';
      echo '</tr>';
    }
} else {
    echo "Empty table or no search results";
}
?>

    </tbody>
  </table>
  </div>

</section>
<footer>

</footer>
<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>
<?php
include 'res/footer.inc.php';

?>
