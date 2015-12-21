<?php
// TODO: Make serial_nr data update automatically when table is changed using ajax
// TODO: Deal with exceptions (like overview) where serial_nr is not a good key
session_start();
$titel = 'Delete from database';
include 'res/header.inc.php';
?>
<?php
// Set $table and $serial_nr if they have been selected, or go to default
$table = 'system';
if(isset($_GET['table']) && $_GET['table'] != '') $table = $_GET['table'];
$serial_nr = 'none';
if(isset($_GET['serial_nr']) && $_GET['serial_nr'] != '') $serial_nr = $_GET['serial_nr'];
$tables = Array();
$rows = Array();
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 ///// Database query ///// for table /////
$sql = "SELECT * FROM $table";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
if ($result->num_rows > 0) {
    // output data of each row
    while($mrow = $result->fetch_assoc()) {
      $rows[] = $mrow;
    }
} else {
    echo "No row";
}

 ///// Database query ///// for serial nr /////
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

<script type="text/javascript">
// helper script for combobox
  $(document).ready(function(){
    $('.combobox').combobox();
  });
</script>

<section class="content">

  <?php
  // Shows status messages if $_SESSION['alert'] has a value and this has not been shown allready
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

  <div class="row">

    <!-- select a database table -->
    <form action="admin_delete.php" method="get">
      <div class="form-group">
        <label for="table" class="col-sm-3 col-xs-12 control-label">Table</label>
        <div class="col-sm-6 col-xs-7">
          <select class="combobox form-control" name="table" id="table_selector">
            <option></option>
            <?php
            $value_index = 'Tables_in_' . $dbname;
            foreach ($tables as $value) {
              if ($value[$value_index] == $table){
                echo '<option value="' . $value[$value_index] . '" autofocus selected="selected">' . $value[$value_index] . '</option>';
              }
              else {
                echo '<option value="' . $value[$value_index] . '">' . $value[$value_index] . '</option>';
              }
            }
            ?>
          </select>
        </div>
        <div class="col-sm-3 col-xs-5">
          <button type="submit" class="btn btn-default">UPDATE</button>
        </div>
      </div>
    </form>

  </div>
  <div class="row">

    <!-- select a database column based on 'serial_nr' (or 'id' when that function is done) -->
    <form action="admin_delete.php" method="get">
      <input type="text" name="table" value="<?php echo $table ?>" hidden="hidden" />
      <div class="form-group">
        <label for="serial_nr" class="col-sm-3 col-xs-12 control-label">Serial number</label>
        <div class="col-sm-6 col-xs-7">
          <select class="combobox form-control" name="serial_nr">
            <?php
            if (array_key_exists('serial_nr', $rows[0])){
              $wanted_column = 'serial_nr';
            }
            else {
              $wanted_column = 'id';
            }
            foreach ($rows as $value) {
              if ($value[$wanted_column] == $serial_nr){
                echo '<option value="' . $value[$wanted_column] . '" autofocus selected="selected">' . $value[$wanted_column] . '</option>';
              }
              else {
                echo '<option value="' . $value[$wanted_column] . '">' . $value[$wanted_column] . '</option>';
              }
            }
            ?>
          </select>
        </div>
        <div class="col-sm-3 col-xs-5">
          <button type="submit" class="btn btn-default">UPDATE</button>
        </div>
      </div>
    </form>
  </div>
</section>

<section class="content">

  <!-- send a delete request to delete.php -->
  <form action="delete.php" method="POST">

    <input type="text" name="table" value="<?php echo $table ?>" hidden="hidden" />
    <input type="text" name="serial_nr" value="<?php echo $serial_nr ?>" hidden="hidden" />

    <h4>Log</h4>
    <div class="form-group col-xs-12">
      <label for="user">User</label>
      <div>
        <input type="text" class="form-control" name="user" <?= !empty($_SESSION['user']) ? 'value="' . $_SESSION['user'] . '"' : ''; ?> required />
      </div>
    </div>

    <div class="form-group col-xs-12">
      <label for="log_comment">Log Comment</label>
      <div>
        <textarea class="form-control" name="log_comment" rows="3"></textarea>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <button type="submit" class="btn btn-default">DELETE</button>
        <?php echo  " FROM " . $table . " WHERE serial_nr = " . $serial_nr;?>
      </div>
    </div>
  </form>
</section>

<footer>

</footer>

<?php
$conn->close(); // close connection
include 'res/footer.inc.php';
?>
