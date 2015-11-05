<?php
$titel = 'Log';
include 'res/header.inc.php'; 
?>
<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>

<section class="all__systems">
  <table class="large__table table table-striped table-responsive">
    <thead>
      <tr>
        <th>Datetime</th>
        <th>Type</th>
        <th>User</th>
        <th>Serial nr.</th>
        <th>Comment</th>
        <th>SQL</th>
      </tr>
    </thead>
    <tbody>

<?php

// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "  SELECT *
          FROM log
          ORDER BY datetime DESC";
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
      echo '<td>' . $commentButton . '</td>';
      echo '<td>' . $sqlButton . '</td>';
      echo '</tr>';
    }
} else {
    echo "No messages";
}
?>

    </tbody>
  </table>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>