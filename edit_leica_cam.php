<?php

$titel = 'Edit Leica Camera';
include 'res/header.inc.php';
$type = 'add_leica_cam';
if (!empty($_GET['serial_nr'])) {
	$type = 'update_leica_cam';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT *
			FROM leica_cam
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
<?php require_once('res/functions.inc.php'); ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('.combobox').combobox();
  });
</script>
<section class="content">
	
<form action= <?php echo $path ?> method="post" class="form-horizontal">
  <div class="row">
	<div class="col-sm-6 col-sm-offset-1">

	<div class="col-xs-8 col-xs-offset-4"><h4>Leica Camera</h4></div>

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
		<label for="config" class="col-xs-4 control-label">Configuration</label>
	  <div class="col-xs-8">
		<select class="form-control" name="configuration">
<?php
foreach($configuration_values as $i){
	$selected = '';
	if(!empty($row['configuration']) && $row['configuration'] == $i){$selected = 'selected';}
	$s = '<option value="%s" %s>%s</option>';
	echo sprintf($s, $i, $selected, $i);
}
?>
		</select>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="breakdown" class="col-xs-4 control-label">Breakdown</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="breakdown" <?= !empty($row['breakdown']) ?  'value="' . $row['breakdown'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="operating_voltage" class="col-xs-4 control-label">Operating Voltage</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="operating_voltage" <?= !empty($row['operating_voltage']) ?  'value="' . $row['operating_voltage'] . '"' : '' ; ?>>
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