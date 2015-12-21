<?php
session_start();
$titel = 'Edit Receiver';
include 'res/header.inc.php';
$type = 'add_receiver';
if (!empty($_GET['serial_nr'])) {
	$type = 'update_receiver';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT *
			FROM receiver_unit
			WHERE serial_nr = '$_GET[serial_nr]'";
	$result = $conn->query($sql);
	if (!$result) {
		echo "Error 1: " . $sql . "<br>" . $conn->error;
		die();
	}

    $r_unit = $result->fetch_array(MYSQLI_ASSOC);

    $sql = "SELECT *
			FROM receiver_chip
			WHERE serial_nr = '$r_unit[receiver_chip_sn]'";
	$result = $conn->query($sql);
	if (!$result) {
		echo "Error 2: " . $sql . "<br>" . $conn->error;
		die();
	}

    $r_chip = $result->fetch_array(MYSQLI_ASSOC);

    $conn->close();
}
$path = 'post_add_update.php?type=' . $type; // path for form
?>
<section class="content">

<form action= <?php echo htmlspecialchars($path); ?> method="post" class="form-horizontal">
  <div class="row">
	<div class="col-sm-6 col-sm-offset-1">

	  <div class="col-xs-8 col-xs-offset-4"><h4>Receiver Unit</h4></div>

	  <div class="form-group">
		<label for="serial_nr" class="col-xs-4 control-label">Serial Number</label>
	  	<div class="col-xs-8">
<?php
if (!empty($_GET['serial_nr'])) {
	echo '<input type="hidden" name="serial_nr" value="' . $_GET['serial_nr'] . '" />'
	. '<input type="text" class="form-control" placeholder="' . $_GET['serial_nr'] . '" disabled />';
}else {
	echo '<input type="text" class="form-control" name="serial_nr" required />';
}
?>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="art_nr" class="col-xs-4 control-label">Article Nr.</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="art_nr" <?= !empty($r_unit['art_nr']) ?  'value="' . $r_unit['art_nr'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="col-xs-8 col-xs-offset-4"><h4>Receiver Chip</h4></div>

  	  <div class="form-group">
		<label for="receiver_chip" class="col-xs-4 control-label">Serial Number</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="receiver_chip" <?= !empty($r_chip['serial_nr']) ?  'value="' . $r_chip['serial_nr'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="breakdown_voltage" class="col-xs-4 control-label">Breakdown Voltage</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="breakdown_voltage" <?= !empty($r_chip['breakdown_voltage']) ?  'value="' . $r_chip['breakdown_voltage'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="operating_voltage" class="col-xs-4 control-label">Operating Voltage</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="operating_voltage" <?= !empty($r_chip['operating_voltage']) ?  'value="' . $r_chip['operating_voltage'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	</div> <!-- end col -->
	<div class="col-sm-3 col-sm-offset-1">

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
	</div>

  </div><!-- end row -->
  <div class="row">
	  <div class="col-sm-12">
	    <button type="submit" class="btn btn-default">Apply</button>
	    <a href="main_parts.php" class="btn btn-default">Cancel</a>
	  </div>
  </div>
</form>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>
