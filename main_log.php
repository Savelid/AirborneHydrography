<?php
$titel = 'Log';
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
  <a href="admin_dump_backup.php" class="btn btn-default navbar-btn" role="button">Download Database Backup</a>
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
        <th>Datetime</th>
        <th>Type</th>
        <th>User</th>
        <th>Serial nr.</th>
        <th class="hidden-print">Comment</th>
        <th class="hidden-print">SQL</th>
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

$sql = " SELECT * FROM log";
if(isset($_GET['search'])){
  $sql .= " WHERE datetime LIKE '%" . $_GET['search'] . "%'
  OR type LIKE '%" . $_GET['search'] . "%'
  OR user LIKE '%" . $_GET['search'] . "%'
  OR sql_string LIKE '%" . $_GET['search'] . "%'
  OR serial_nr LIKE '%" . $_GET['search'] . "%'
  OR comment LIKE '%" . $_GET['search'] . "%'";
}
$sql .= " ORDER BY datetime DESC";
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
      $sqlButton = '
      <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-html="true" data-placement="left" data-content="'
      . $row["sql_string"] .
      '">Query</button>';
      echo '<tr>';
      echo '<td>' . $row["datetime"]  . '</td>';
      echo '<td>' . $row["type"]      . '</td>';
      echo '<td>' . $row["user"]      . '</td>';
      echo '<td>' . $row["serial_nr"] . '</td>';
      echo '<td class="hidden-print">' . $commentButton . '</td>';
      echo '<td class="hidden-print">' . $sqlButton . '</td>';
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

<?php include 'res/footer.inc.php'; ?>

<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>
