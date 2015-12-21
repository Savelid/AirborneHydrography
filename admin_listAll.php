<?php
$titel = 'List all';
include 'res/header.inc.php';
?>
<?php
$table = 'system';
if(isset($_GET['table']) && $_GET['table'] != '') $table = $_GET['table'];
$tables = Array();
$cols = Array();
$table_info = Array();
$rows = Array();
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SHOW COLUMNS FROM $table";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $cols[] = $row['Field'];
      $table_info[] = $row;
    }
} else {
    echo "No col";
}

$sql = "SELECT * FROM $table";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
} else {
    echo "No row";
}

$sql = "SHOW TABLES";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
      $tables[] = $row;
    }
} else {
    echo "No tables";
}
?>

<section class="content">
  <form action="admin_listAll.php" method="get">
    <input list="table" name="table">
    <datalist id="table">
<?php
$value_index = 'Tables_in_' . $dbname;
foreach ($tables as $value) {
      echo '<option value="' . $value[$value_index] . '"></option>';
}
?>
    </datalist>
    <input type="submit" value="Get table">
  </form>
</section>
<section class="content">

  <div class="row">
   <div class="col-sm-12">
     <div class="panel panel-default">
       <div class="panel-heading">
         <h3 class="panel-title">Table Info</h3>
       </div>
       <div class="panel-body">
         <p>
           <?php foreach ($table_info as $info) {
             print_r($info);
             echo '<br>';
           } ?>
         </p>
       </div><!-- end panel body -->
     </div><!-- end panel -->
   </div><!-- end col -->
 </div><!-- end row -->

<table class="table table-bordered">
  <thead>
    <tr>
<?php
foreach ($cols as $col) {
  echo '<th>' . $col . '</th>';
}
?>
    </tr>
  </thead>
  <tbody>
<?php
foreach ($rows as $row) {
  echo '<tr>';
  foreach ($cols as $col) {
    echo '<td>' . $row[$col] . '</td>';
  }
  echo '</tr>';
}
?>
  </tbody>
</table>

</section>
<footer>

</footer>

<?php
$conn->close(); // close connection
include 'res/footer.inc.php';
?>
