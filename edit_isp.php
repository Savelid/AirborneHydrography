<?php
session_start();
$titel = 'Edit ISP log';
include 'res/header.inc.php';
$type = 'add_isp';
if (!empty($_GET['id'])) {
	$type = 'update_isp';

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT *
	FROM isp
	WHERE id = '$_GET[id]';";
	$result = $conn->query($sql);
	if (!$result) {
		die("Query 1 failed! " . $sql . "<br>" . $conn->error);
	}
	$row = $result->fetch_array(MYSQLI_ASSOC);

	$conn->close();
}
// *** Prefill the form with this data *** //
if (!empty($row['isp_nr'])) $isp_nr = $row['isp_nr'];
else $isp_nr = '';

if (!empty($row['datetime'])) $datetime = substr($row['datetime'], 0, 10);
else $datetime = '';

if (!empty($row['code'])) $code = $row['code'];
else $code = '6A008j';

if (isset($_GET['receiver'])){$receiver = $_GET['receiver'];}
elseif (!empty($row['receiver'])){$receiver = $row['receiver'];}
else $receiver = '';

if (!empty($row['country'])) $country = $row['country'];
else $country = '';

if (!empty($row['amount'])) $amount = $row['amount'];
else $amount = 1;

if (!empty($row['value'])) $value = $row['value'];
else $value = '';

if (!empty($row['comment'])) $comment = $row['comment'];
else $comment = '';

$path = 'post_add_update.php?type=' . $type;
?>
<?php require_once('res/functions.inc.php'); ?>
<script type="text/javascript">
$(document).ready(function(){
	$('.combobox').combobox();
});
</script>
<section class="content">

	<form action= <?php echo htmlspecialchars($path); ?> method="post" class="form-horizontal">

		<?php
		if(isset($_GET['id'])){
			echo '<input type="hidden" class="form-control" name="id" value="' . $_GET['id'] . '"/>';
		}
		?>
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
