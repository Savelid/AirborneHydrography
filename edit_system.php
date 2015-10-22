<?php

$titel = 'Edit System';
include 'res/header.inc.php';
$new = 'TRUE';
if (!empty($_GET['system'])) {
	$new = 'FALSE';

	include 'res/config.inc.php';
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	//
	$sn = $_GET['system'];
	$sql = "SELECT serial_nr, art_nr, client, configuration, sensor_unit_sn, control_unit_sn, deep_system_sn, cooling_system, comment
			FROM system WHERE serial_nr = $sn";
	$result = $conn->query($sql);
	if (!$result) {
		die("Query failed!");
	}

    $row = $result->fetch_array(MYSQLI_ASSOC);
}
$path = 'post.php?type=system&new=' . $new;

?>

<section class="content">
    <form action= <?php echo $path ?> method="post" class="form-horizontal">

      <div class="form-group">
    	<label for="serial_nr" class="col-sm-3 control-label">Serial Number</label>
    	<div class="col-sm-5">
 		  <input type="text" class="form-control" name="serial_nr" <?= !empty($_GET['system']) ?  'value="' . $_GET['system'] . '"' : '' ; ?>>
 		</div>
  	  </div>

  	  <div class="form-group">
    	<label for="art_nr" class="col-sm-3 control-label">Art. Number</label>
    	<div class="col-sm-5">
 		  <input type="text" class="form-control" name="art_nr" <?= !empty($row['art_nr']) ?  'value="' . $row['art_nr'] . '"' : '' ; ?>>
 		</div>
  	  </div>

  	  <div class="form-group">
    	<label for="client" class="col-sm-3 control-label">Client</label>
    	<div class="col-sm-5">
 		  <input type="text" class="form-control" name="client" <?= !empty($row['client']) ?  'value="' . $row['client'] . '"' : '' ; ?>>
 		</div>
  	  </div>

  	  <div class="form-group">
    	<label for="config" class="col-sm-3 control-label">Configuration</label>
    	<div class="col-sm-5">
	 		<select class="form-control" name="config">
	  		  <option value="Chiroptera" <?= !empty($row['configuration']) && $row['configuration'] == 'Chiroptera' ? 'selected="selected"' : '' ; ?>>Chiroptera</option>
	  		  <option value="DualDragon" <?= !empty($row['configuration']) && $row['configuration'] == 'DualDragon' ? 'selected="selected"' : '' ; ?>>DualDragon</option>
	  		  <option value="HawkEyeIII" <?= !empty($row['configuration']) && $row['configuration'] == 'HawkEyeIII' ? 'selected="selected"' : '' ; ?>>HawkEyeIII</option>
			</select>
		</div>
  	  </div>

  	  <div class="form-group">
    	<label for="sensor_unit" class="col-sm-3 control-label">Sensor Unit</label>
    	<div class="col-sm-5">
 		  <input type="text" class="form-control" name="sensor_unit" <?= !empty($row['sensor_unit_sn']) ?  'value="' . $row['sensor_unit_sn'] . '"' : '' ; ?>>
 		</div>
  	  </div>

  	  <div class="form-group">
    	<label for="control_unit" class="col-sm-3 control-label">Control Unit</label>
    	<div class="col-sm-5">
 		  <input type="text" class="form-control" name="control_unit" <?= !empty($row['control_unit_sn']) ?  'value="' . $row['control_unit_sn'] . '"' : '' ; ?>>
 		</div>
  	  </div>

  	  <div class="form-group">
    	<label for="deep_system" class="col-sm-3 control-label">Deep System</label>
    	<div class="col-sm-5">
 		  <input type="text" class="form-control" name="deep_system" <?= !empty($row['deep_system_sn']) ?  'value="' . $row['deep_system_sn'] . '"' : '' ; ?>>
 		</div>
  	  </div>

  	  <div class="form-group">
    	<label for="cooling_system" class="col-sm-3 control-label">Cooling System</label>
    	<div class="col-sm-5">
 		  <input type="text" class="form-control" name="cooling_system" <?= !empty($row['cooling_system']) ?  'value="' . $row['cooling_system'] . '"' : '' ; ?>>
 		</div>
  	  </div>

  	  <div class="form-group">
    	<label for="comment" class="col-sm-3 control-label">Comment</label>
    	<div class="col-sm-5">
    	  <textarea class="form-control" name="comment" rows="3"><?= !empty($row['comment']) ? $row['comment'] : ''; ?></textarea>
    	</div>
  	  </div>

  	  <label class="checkbox-inline">
  		<input type="checkbox" name="potta_heat" > Potta Heat
	  </label>
	  <label class="checkbox-inline">
  		<input type="checkbox" name="shallow_heat" > Shallow Heat
	  </label>

  	  <button type="submit" class="btn btn-default">Apply</button>
	</form>
  </div><!--end column-->
</div><!--end row-->

</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>