<?php
session_start();
include_once 'res/config.inc.php';
include_once('res/functions.inc.php');

$database_columns = "";
if(!empty($_POST)){
	$database_columns = "
		datetime = '$_POST[datetime]',
		product = '$_POST[product]',
		amount = '$_POST[amount]',
		value = '$_POST[value]',
		receiver = '$_POST[receiver]',
		lss = '$_POST[lss]',
		shallow_sensor = '$_POST[shallow_sensor]',
		deep_sensor = '$_POST[deep_sensor]',
		country = '$_POST[country]',
		code = '$_POST[code]',
		comment = '$_POST[comment]'
		";
}
$row = postFunction('isp_nr', 'isp', $database_columns, 'main_isp.php');
if ($row == NULL) {
	debug_to_console("Database request returned NULL");
}
$titel = 'Edit ISP log';
include 'res/header.inc.php';

// *** Prefill the form with this data *** //
if (!empty($row['isp_nr'])) {
	debug_to_console($row['isp_nr']);
	$isp_nr = $row['isp_nr'];
	debug_to_console("isp_nr changed");
}else {
	debug_to_console($row['isp_nr']);
	$isp_nr = '';
	debug_to_console("isp_nr changed");
}

if (!empty($row['datetime'])) $datetime = substr($row['datetime'], 0, 10);
else $datetime = '';

if (!empty($row['code'])) $code = $row['code'];
else $code = '6A008j';

if (isset($_GET['receiver'])){$receiver = $_GET['receiver'];}
elseif (!empty($row['receiver'])){$receiver = $row['receiver'];}
else $receiver = '';

if (isset($_GET['lss'])){$lss = $_GET['lss'];}
elseif (!empty($row['lss'])){$iss = $row['lss'];}
else $lss = '';

if (isset($_GET['shallow_sensor'])){$shallow_sensor = $_GET['shallow_sensor'];}
elseif (!empty($row['shallow_sensor'])){$shallow_sensor = $row['shallow_sensor'];}
else $shallow_sensor = '';

if (isset($_GET['deep_sensor'])){$deep_sensor = $_GET['deep_sensor'];}
elseif (!empty($row['deep_sensor'])){$deep_sensor = $row['deep_sensor'];}
else $deep_sensor = '';

if (!empty($row['country'])) $country = $row['country'];
else $country = '';

if (!empty($row['amount'])) $amount = $row['amount'];
else $amount = 1;

if (!empty($row['value'])) $value = $row['value'];
else $value = '';

if (!empty($row['comment'])) $comment = $row['comment'];
else $comment = '';
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

				<div class="col-xs-8 col-xs-offset-4"><h4>ISP data</h4></div>

				<div class="form-group">
					<label for="isp_nr" class="col-xs-4 control-label">ISP number</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="isp_nr" value="<?php echo $isp_nr; ?>" />
					</div>
				</div>

				<div class="form-group">
					<label for="datetime" class="col-xs-4 control-label">Date</label>
					<div class="col-xs-8">
						<input type="date" class="form-control" name="datetime" value="<?php echo $datetime; ?>" />
					</div>
				</div>

				<div class="form-group">
					<label for="product" class="col-xs-4 control-label">Product</label>
					<div class="col-xs-8">
						<select class="form-control" name="product">
							<?php
							foreach($isp_product_values as $i){
								$selected = '';
								if(!empty($row['product']) && $row['product'] == $i){$selected = 'selected';}
								$s = '<option value="%s" %s>%s</option>';
								echo sprintf($s, $i, $selected, $i);
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="code" class="col-xs-4 control-label">Code</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="code" value="<?php echo $code; ?>" />
					</div>
				</div>

				<div class="form-group">
					<label for="receiver" class="col-xs-4 control-label">Receiver</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="receiver" value="<?php echo $receiver ?>" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="lss" class="col-xs-4 control-label">LSS</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="lss" value="<?php echo $lss ?>" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="shallow_sensor" class="col-xs-4 control-label">Shallow Sensor</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="shallow_sensor" value="<?php echo $shallow_sensor ?>" />
					</div>
				</div>
				
				<div class="form-group">
					<label for="deep_sensor" class="col-xs-4 control-label">Deep Sensor</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="deep_sensor" value="<?php echo $deep_sensor ?>" />
					</div>
				</div>

				<div class="form-group">
					<label for="country" class="col-xs-4 control-label">Country</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="country" value="<?php echo $country; ?>" />
					</div>
				</div>

				<div class="form-group">
					<label for="amount" class="col-xs-4 control-label">Amount</label>
					<div class="col-xs-8">
						<input type="number" class="form-control" name="amount" value="<?php echo $amount; ?>" />
					</div>
				</div>

				<div class="form-group">
					<label for="value" class="col-xs-4 control-label">Value</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="value" value="<?php echo $value; ?>" />
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
					<label for="comment" class="col-sm-3 col-xs-12 control-label">Comment</label>
					<div class="col-sm-8 col-xs-12">
						<textarea class="form-control" name="comment" rows="5"><?php echo $comment; ?></textarea>
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

<?php include 'res/footer.inc.php'; ?>
