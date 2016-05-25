<?php
session_start();
include_once 'res/config.inc.php';
include_once('res/functions.inc.php');
include_once 'res/postfunctions.inc.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

//Get number for calibration ID
$sql = "SELECT calibration_id FROM calibration WHERE calibration_id LIKE 'AHAB-CALIB-%' ORDER BY calibration_id DESC LIMIT 1 ;";
$result_calibration_id = $conn->query($sql);
$row_calibration_id = $result_calibration_id->fetch_array(MYSQLI_NUM);
$int_calibration_id = filter_var($row_calibration_id[0], FILTER_SANITIZE_NUMBER_INT);
$int_calibration_id = str_replace('-', '', $int_calibration_id);
$int_calibration_id = intval($int_calibration_id);
$int_calibration_id = $int_calibration_id + 1;
debug_to_console("Calibration id nummer" . $int_calibration_id);

$conn->close();

$database_columns = "";
if(!empty($_POST)){

		$database_columns = "
			dataset_id = '$_POST[dataset_id]',
			comment = '$_POST[comment]'
			";
}

$row = postFunction('calibration_id', 'calibration', $database_columns, 'main_calibration.php');

$dataset = '';
if (!empty($row['dataset_id'])){
	$dataset = 'value="' . $row['dataset_id'] . '"';
}elseif (!empty($_GET['dataset_id'])) {
	$dataset = 'value="' . $_GET['dataset_id'] . '"';
}

$titel = 'Edit dataset';
include 'res/header.inc.php';
?>
<section class="content">

	<form action= <?php echo htmlspecialchars($_SERVER['PHP_SELF'] ); ?> method="post" class="form-horizontal" enctype="multipart/form-data">

		<div class="row">
			<div class="col-sm-6 col-sm-offset-1">

				<div class="col-xs-8 col-xs-offset-4"><h4>Flight</h4></div>

				<div class="form-group">
					<label for="calibration_id" class="col-xs-4 control-label">
						Dataset ID
						<div class="comments">AHAB-CALIB-xxxx</div>
					</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="calibration_id" <?= !empty($row['calibration_id']) ?  'value="' . $row['calibration_id'] . '"' : 'value="AHAB-CALIB-' . sprintf("%04d", $int_calibration_id) .'"' ; ?> required />
					</div>
				</div>

				<div class="form-group">
					<label for="dataset_id" class="col-xs-4 control-label">
						Disc ID
						<div class="comments">AHAB-DATA-xxxx</div>
					</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="dataset_id" <?php echo $dataset; ?> required/>
					</div>
				</div>

			</div>
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
		</div>
		<div class="row">
			<div class="col-xs-12">

				<div class="form-group">
					<label for="comment" class="col-sm-3 col-xs-12 control-label">
						Calibration comments
						<div class ="comments">Calibration comments</div>
					</label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="comment" rows="5"><?= !empty($row['comment']) ?  $row['comment'] : '' ; ?></textarea>
					</div>
				</div>

			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">

				<button type="submit" class="btn btn-default">Apply</button>
				<a href="main_systems.php" class="btn btn-default">Cancel</a>
			</div>
		</div>
	</form>
</section>
<footer>

</footer>
<script type="text/javascript">
$(document).ready(function(){
	$('.combobox').combobox();
});
</script>

<?php include 'res/footer.inc.php'; ?>
