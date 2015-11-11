<?php

$titel = 'Edit Laser';
include 'res/header.inc.php';
$type = 'add_laser';
if (!empty($_GET['serial_nr'])) {
	$type = 'update_laser';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	//
	$sn = $_GET['serial_nr'];
	$sql = "SELECT *
			FROM laser
			WHERE serial_nr = $sn";
	$result = $conn->query($sql);
	if (!$result) {
		echo "Error: " . $sql_insert . "<br>" . $conn->error;
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

	<div class="col-xs-8 col-xs-offset-4"><h4>Laser</h4></div>

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
		<label for="v_0" class="col-xs-4 control-label">Value 0</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_0" <?= !empty($row['v_0']) ?  'value="' . $row['v_0'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="v_5" class="col-xs-4 control-label">Value 5</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_5" <?= !empty($row['v_5']) ?  'value="' . $row['v_5'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_10" class="col-xs-4 control-label">Value 10</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_10" <?= !empty($row['v_10']) ?  'value="' . $row['v_10'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_15" class="col-xs-4 control-label">Value 15</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_15" <?= !empty($row['v_15']) ?  'value="' . $row['v_15'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_20" class="col-xs-4 control-label">Value 20</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_20" <?= !empty($row['v_20']) ?  'value="' . $row['v_20'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_25" class="col-xs-4 control-label">Value 25</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_25" <?= !empty($row['v_25']) ?  'value="' . $row['v_25'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_30" class="col-xs-4 control-label">Value 30</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_30" <?= !empty($row['v_30']) ?  'value="' . $row['v_30'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_40" class="col-xs-4 control-label">Value 40</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_40" <?= !empty($row['v_40']) ?  'value="' . $row['v_40'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_50" class="col-xs-4 control-label">Value 50</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_50" <?= !empty($row['v_50']) ?  'value="' . $row['v_50'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_60" class="col-xs-4 control-label">Value 60</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_60" <?= !empty($row['v_60']) ?  'value="' . $row['v_60'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_70" class="col-xs-4 control-label">Value 70</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_70" <?= !empty($row['v_70']) ?  'value="' . $row['v_70'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_80" class="col-xs-4 control-label">Value 80</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_80" <?= !empty($row['v_80']) ?  'value="' . $row['v_80'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_90" class="col-xs-4 control-label">Value 90</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_90" <?= !empty($row['v_90']) ?  'value="' . $row['v_90'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_100" class="col-xs-4 control-label">Value 100</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_100" <?= !empty($row['v_100']) ?  'value="' . $row['v_100'] . '"' : '' ; ?>>
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