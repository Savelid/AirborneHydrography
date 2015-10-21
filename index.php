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
$conn->close();
?>
	</dl>
    <form action="post.php" method="post">
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
</div><!--end row-->

</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>