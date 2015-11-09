<?php

$titel = 'Edit HV Card';
include 'res/header.inc.php';
$type = 'add_hv_card';
if (!empty($_GET['serial_nr'])) {
	$type = 'update_hv_card';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	//
	$sn = $_GET['serial_nr'];
	$sql = "SELECT *
			FROM hv_card
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

	<div class="col-xs-8 col-xs-offset-4"><h4>HV Card</h4></div>

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
		<label for="art_nr" class="col-xs-4 control-label">Art. Nr.</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="art_nr" <?= !empty($row['art_nr']) ?  'value="' . $row['art_nr'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="k_value" class="col-xs-4 control-label">K-value</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="k_value" <?= !empty($row['k_value']) ?  'value="' . $row['k_value'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="m_value" class="col-xs-4 control-label">M-value</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="m_value" <?= !empty($row['m_value']) ?  'value="' . $row['m_value'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_0" class="col-xs-4 control-label">Value 0</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_0" <?= !empty($row['v_0']) ?  'value="' . $row['v_0'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_500" class="col-xs-4 control-label">Value 500</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_500" <?= !empty($row['v_500']) ?  'value="' . $row['v_500'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_1000" class="col-xs-4 control-label">Value 1000</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_1000" <?= !empty($row['v_1000']) ?  'value="' . $row['v_1000'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_1500" class="col-xs-4 control-label">Value 1500</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_1500" <?= !empty($row['v_1500']) ?  'value="' . $row['v_1500'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_2000" class="col-xs-4 control-label">Value 2000</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_2000" <?= !empty($row['v_2000']) ?  'value="' . $row['v_2000'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_2500" class="col-xs-4 control-label">Value 2500</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_2500" <?= !empty($row['v_2500']) ?  'value="' . $row['v_2500'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_3000" class="col-xs-4 control-label">Value 3000</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_3000" <?= !empty($row['v_3000']) ?  'value="' . $row['v_3000'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="v_3250" class="col-xs-4 control-label">Value 3250</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="v_3250" <?= !empty($row['v_3250']) ?  'value="' . $row['v_3250'] . '"' : '' ; ?>>
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