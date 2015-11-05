<?php
$titel = 'Overview';
include 'res/header.inc.php'; 
?>

<section class="content">

<div class="row">
  <div class="col-md-6">
  	<dl>
<?php
include 'res/config.inc.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//

$sql = "SELECT datetime, message, author FROM overview ORDER BY datetime DESC LIMIT 5";
$result = $conn->query($sql);

// %s will be replaced with variables later
$message_item_formating =
'
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">%s <small>%s</small></h3>
  </div>
  <div class="panel-body">
    %s
  </div>
</div>
';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        echo sprintf($message_item_formating, $row["datetime"], $row["author"], $row["message"]);
    }
} else {
    echo "No messages";
}

?>
	</dl>
    <form action="post.php?type=add_message" method="post">
  	  <div class="form-group">
    	<label for="input_message">Message</label>
    	<textarea class="form-control" name="input_message" rows="3"></textarea>
  	  </div>
  	  <div class="form-group">
    	<label for="input_name">Name</label>
 		<input type="text" class="form-control" name="input_name" placeholder="Name">
  	  </div>
  	  <button type="submit" class="btn btn-default">Submit</button>
	</form>
  </div><!--end column-->

  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Systems</h3>
      </div>
      <div class="panel-body panel_table">

    <table class="table table-striped table-responsive">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Client</th>
            <th>Place</th>
            <th>Config.</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>

<?php

$sql = "  SELECT *
          FROM system
          ORDER BY datetime DESC
          LIMIT 5";
$result = $conn->query($sql);
if (!$result) {
    die("Query failed!");
}

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td><a href="view_system.php?system=%s" class="btn btn-default btn-sm">%s</a></td>
  <td>%s</td>
  <td>%s</td>
  <td>%s</td>
  <td>%s</td>
</tr>
<tr>
  <td colspan=5>%s</td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      // shorten too long client names
      $client = $row["client"];
      if (strlen ($client) >= 11) {
        $client = substr($client, 0, 9) . "..";
      }
      // shorten too long place names
      $place = $row["place"];
      if (strlen ($client) >= 11) {
        $client = substr($client, 0, 9) . "..";
      }

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $row["serial_nr"],
          $client,
          $place,
          $row["configuration"],
          $row["status"],
          $row["comment"]);
    }
} else {
    echo "No messages";
}
$conn->close();
?>

      </tbody>
    </table>
    </div>
    </div>

  </div><!--end column-->

</div><!--end row-->

</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>