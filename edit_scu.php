<?php
session_start();
include_once 'res/config.inc.php';
include_once('res/functions.inc.php');
include_once 'res/postfunctions.inc.php';

$database_columns = "";
if(!empty($_POST)){
	$database_columns = "
	configuration = '$_POST[configuration]',
	digitaizer1 = '$_POST[digitaizer1]',
	digitaizer2 = '$_POST[digitaizer2]',
	sat = '$_POST[sat]',
	cpu = '$_POST[cpu]',
	status = '$_POST[status]',
	comment = '$_POST[comment]'
	";
}
$row = postFunction('serial_nr', 'scu', $database_columns, 'main_parts.php');

$titel = 'Edit SCU';
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

	<div class="col-xs-8 col-xs-offset-4"><h4>Sensor Control Unit</h4></div>

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
		<label for="digitaizer1" class="col-xs-4 control-label">Digitaizer 1</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="digitaizer1" <?= !empty($row['digitaizer1']) ?  'value="' . $row['digitaizer1'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="digitaizer2" class="col-xs-4 control-label">Digitaizer 2</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="digitaizer2" <?= !empty($row['digitaizer2']) ?  'value="' . $row['digitaizer2'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="sat" class="col-xs-4 control-label">Sat</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="sat" <?= !empty($row['sat']) ?  'value="' . $row['sat'] . '"' : '' ; ?>>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="cpu" class="col-xs-4 control-label">CPU</label>
	  <div class="col-xs-8">
	  	<input type="text" class="form-control" name="cpu" <?= !empty($row['cpu']) ?  'value="' . $row['cpu'] . '"' : '' ; ?>>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="comment" class="col-xs-4 control-label">Comment</label>
	  <div class="col-xs-8">
  		<textarea class="form-control" name="comment" rows="3"><?= !empty($row['comment']) ? $row['comment'] : ''; ?></textarea>
	  	</div>
	  </div>

	  <div class="form-group">
		<label for="config" class="col-xs-4 control-label">Status</label>
	  <div class="col-xs-8">
		<select class="form-control" name="status">
<?php
foreach($scu_status_values as $i){
	$selected = '';
	if(!empty($row['status']) && $row['status'] == $i){$selected = 'selected';}
	$s = '<option value="%s" %s>%s</option>';
	echo sprintf($s, $i, $selected, $i);
}
?>
		</select>
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
