<?php
session_start();
include_once 'res/config.inc.php';
include_once('res/functions.inc.php');

// $database_columns = "";
// if(!empty($_POST)){
// 	$database_columns = "
// 	art_nr = '$_POST[art_nr]',
// 	receiver_chip_sn = '$_POST[receiver_chip]'
// 	";
// }
// $r_unit = postFunction('serial_nr', 'receiver_unit', $database_columns, 'main_parts.php');
//
// $database_columns2 = "";
// if(!empty($_POST)){
// 	$database_columns2 = "
// 	breakdown_voltage = '$_POST[breakdown_voltage]',
// 	operating_voltage = '$_POST[operating_voltage]'
// 	";
// }
// $r_chip = postFunction('serial_nr', 'receiver_chip', $database_columns2, 'main_parts.php');
if(!empty($_GET['serial_nr'])){
	$r_unit = getDatabaseRow('receiver_unit', 'serial_nr', $_GET['serial_nr']);
}
if(!empty($r_unit['receiver_chip_sn'])){
	$r_chip = getDatabaseRow('receiver_chip', 'serial_nr', $r_unit['receiver_chip_sn']);
}

if (isset($_POST['serial_nr'])) {

	$database_columns = "";
	if(!empty($_POST)){
		$database_columns = "
		art_nr = '$_POST[art_nr]',
		receiver_chip_sn = '$_POST[receiver_chip]'
		";
	}
	$database_columns2 = "";
	if(!empty($_POST)){
		$database_columns2 = "
		breakdown_voltage = '$_POST[breakdown_voltage]',
		operating_voltage = '$_POST[operating_voltage]'
		";
	}

	$post_status = postToDatabase('receiver_unit', 'serial_nr', $_POST['serial_nr'], $database_columns);
	$post_status2 = postToDatabase('receiver_chip', 'serial_nr', $_POST['receiver_chip'], $database_columns2);

	$_SESSION['showalert'] = 'true';
	$_SESSION['alert'] = "Receiver unit: " . $post_status['status'];
	$_SESSION['alert'] .= "<br>";
	$_SESSION['alert'] .= "Receiver chip: " . $post_status2['status'];
	$_SESSION['alert'] .= "<br><br>";
	$_SESSION['alert'] .= $post_status['updates'];
	$_SESSION['alert'] .= $post_status2['updates'];

	$log_status = postToLog($_POST['serial_nr'], $post_status['type'] . " receiver", $post_status['query'] . "<br>" . $post_status2['query'], $post_status['updates'] . "<br>" . $post_status2['updates'], $_POST['user'], $_POST['log_comment']);
	header("Location: main_parts.php");
}

$titel = 'Edit Receiver';
include 'res/header.inc.php';
?>
<section class="content">

<form action= <?php echo htmlspecialchars($_SERVER['PHP_SELF'] ); ?> method="post" class="form-horizontal">
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
			<label for="log_comment">Comment saved in Log file</label>
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
