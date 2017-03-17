<?php
session_start();
include_once 'res/config.inc.php';
include_once('res/functions.inc.php');
include_once 'res/postfunctions.inc.php';

$database_columns = "";
if(!empty($_POST)){
	$database_columns = "
	art_nr = '$_POST[art_nr]',
	k_value = '$_POST[k_value]',
	m_value = '$_POST[m_value]'
	";
}
$row = postFunction('serial_nr', 'hv_card', $database_columns, 'main_parts.php');

$titel = 'Edit HV Card';
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

	<div class="col-xs-8 col-xs-offset-4"><h4>HV Card</h4></div>

	  <div class="form-group">
		<label for="serial_nr" class="col-xs-4 control-label">Serial Number</label>
	  	<div class="col-xs-8">
<?php
if (!empty($_GET['serial_nr'])) {
	echo '<input type="hidden" name="serial_nr" value="' . $_GET['serial_nr'] . '" />'
	. '<input type="text" class="form-control" placeholder="' . $_GET['serial_nr'] . '" disabled />';
}else {
	echo '<input type="text" class="form-control" name="serial_nr" required/>';
}
?>
	  	</div>
	  </div>

  	  <div class="form-group">
		<label for="art_nr" class="col-xs-4 control-label">Article Nr.</label>
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
