<?php
session_start();
include_once 'res/config.inc.php';
include_once('res/functions.inc.php');

$database_columns = "";
if(!empty($_POST)){
	$database_columns = "
	type = '$_POST[type]',
	firmware = '$_POST[firmware]'
	";
}
$row = postFunction('serial_nr', 'leica', $database_columns, 'main_parts.php');

$titel = 'Edit Leica';
include 'res/header.inc.php';
?>
<script type="text/javascript">
$(document).ready(function(){
	$('.combobox').combobox();
});
</script>
<section class="content">
	<form action= <?php echo htmlspecialchars($_SERVER['PHP_SELF'] ); ?> method="post" class="form-horizontal">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-1">

				<div class="col-xs-8 col-xs-offset-4"><h4>Leica Unit</h4></div>

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

				<!-- preselect the stored value for Type in the drop box meny -->
				<div class="form-group">
					<label for="config" class="col-xs-4 control-label">Type</label>
					<div class="col-xs-8">
						<select class="form-control" name="type">
							<option value="OC60" <?= !empty($row['type']) && $row['type'] == 'OC60' ? 'selected="selected"' : '' ; ?>>OC60</option>
							<option value="CC32" <?= !empty($row['type']) && $row['type'] == 'CC32' ? 'selected="selected"' : '' ; ?>>CC32</option>
							<option value="PAV" <?= !empty($row['type']) && $row['type'] == 'PAV' ? 'selected="selected"' : '' ; ?>>PAV</option>
							<option value="Camera" <?= !empty($row['type']) && $row['type'] == 'Camera' ? 'selected="selected"' : '' ; ?>>Camera</option>
							<option value="IMU" <?= !empty($row['type']) && $row['type'] == 'IMU' ? 'selected="selected"' : '' ; ?>>IMU</option>
							<option value="Pilot Monitor" <?= !empty($row['type']) && $row['type'] == 'Pilot Monitor' ? 'selected="selected"' : '' ; ?>>Pilot Monitor</option>
							<option value="Leica Lens" <?= !empty($row['type']) && $row['type'] == 'Leica Lens' ? 'selected="selected"' : '' ; ?>>Leica Lens</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="firmware" class="col-xs-4 control-label">
						Firmware
						<div class ="comments">
							The IMU, Pilote Monitor and the Leica Lens does not have a firmware
						</div>
					</label>
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