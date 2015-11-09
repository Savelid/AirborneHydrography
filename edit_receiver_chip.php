<?php

$titel = 'Edit Receivar Chip';
include 'res/header.inc.php';
$type = 'add_receiver_chip';
if (!empty($_GET['serial_nr'])) {
	$type = 'update_receiver_chip';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT *
			FROM receiver_chip
			WHERE serial_nr = '$_GET[serial_nr]'";
	$result = $conn->query($sql);
	if (!$result) {
		echo "Error: " . $sql . "<br>" . $conn->error;
		die();
	}

    $row = $result->fetch_array(MYSQLI_ASSOC);
    $conn->close();
}
$path = 'post.php?type=' . $type; // path for form
?>
<section class="content">
	
<form action= <?php echo $path ?> method="post" class="form-horizontal">
  <div class="row">
	<div class="col-sm-6 col-sm-offset-1">

	<div class="col-xs-8 col-xs-offset-4"><h4>Receiver Chip</h4></div>

	  <div class="form-group">
		<label for="serial_nr" class="col-xs-4 control-label">Serial Number</label>
	  	<div class="col-xs-8">
<?php
if (!empty($_GET['serial_nr'])) {
	echo '<input type="hidden" name="serial_nr" value="' . $_GET['serial_nr'] . '" />'
	. '<input type="text" class="form-control" placeholder="' . $_GET['serial_nr'] . '" disabled />';
}else {
	echo '<input type="text" class="form-control" name="serial_nr" />';
}
?>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="art_nr" class="col-xs-4 control-label">Art. Nr.</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="art_nr" <?= !empty($row['art_nr']) ?  'value="' . $row['art_nr'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="unit" class="col-xs-4 control-label">Unit</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="unit" <?= !empty($row['unit']) ?  'value="' . $row['unit'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="firmware" class="col-xs-4 control-label">Firmware</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="firmware" <?= !empty($row['firmware']) ?  'value="' . $row['firmware'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	</div> <!-- end col -->
	<div class="col-sm-3 col-sm-offset-1">

	  <h4>Log</h4>
		<div class="form-group col-xs-12">
			<label for="user">User</label>
			<div>
				<input type="text" class="form-control" name="user" required />
			</div>
		</div>

		<div class="form-group col-xs-12">
			<label for="log_comment">Log Comment</label>
			<div>
				<textarea class="form-control" name="log_comment" rows="3"><?= !empty($row['log_comment']) ? $row['log_comment'] : ''; ?></textarea>
			</div>
		</div>
	</div>

  </div><!-- end row -->
  <div class="row">
	  <div class="col-sm-12">
	    <button type="submit" class="btn btn-default">Apply</button>
	    <a href="parts.php" class="btn btn-default">Cancel</a>
	  </div>
  </div>
</form>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>