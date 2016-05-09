<?php
session_start();
include_once 'res/config.inc.php';
include_once('res/functions.inc.php');
include_once 'res/postfunctions.inc.php';

$database_columns = "";
if(!empty($_POST)){
	$database_columns = "
	serial_nr = '$_POST[serial_nr]',
	art_nr = '$_POST[art_nr]',
	client = '$_POST[client]',
	place = '$_POST[place]',
	configuration = '$_POST[configuration]',
	status = '$_POST[status]',
	comment = '$_POST[comment]',
	sensor_unit_sn = '$_POST[sensor_unit]',
	control_system_sn = '$_POST[control_system]',
	deep_system_sn = '$_POST[deep_system]',
	pd60 = '$_POST[pd60]',
	oc60_1_sn = '$_POST[oc60_1]',
	oc60_2_sn = '$_POST[oc60_2]',
	pav_sn = '$_POST[pav]',

	oc = '$_POST[oc]',
	bitfile_topo = '$_POST[bitfile_topo]',
	bitfile_shallow = '$_POST[bitfile_shallow]',
	bitfile_deep = '$_POST[bitfile_deep]',
	bitfile_digitaizer1 = '$_POST[bitfile_digitaizer1]',
	bitfile_digitaizer2 = '$_POST[bitfile_digitaizer2]',
	bitfile_sat = '$_POST[bitfile_sat]'
	";
}
$row = postFunction('system', $database_columns, 'main_systems.php');

$database_columns2 = "";
if(!empty($_POST)){
	$database_columns2 = "
	serial_nr = '$_POST[serial_nr]',
	status_potta_heat = '$_POST[status_potta_heat]',
	status_shallow_heat = '$_POST[status_shallow_heat]',
	status_scu_pdu = '$_POST[status_scu_pdu]',
	status_hv_topo = '$_POST[status_hv_topo]',
	status_hv_shallow = '$_POST[status_hv_shallow]',
	status_hv_deep = '$_POST[status_hv_deep]',
	status_cat = '$_POST[status_cat]',
	status_pwr_cable = '$_POST[status_pwr_cable]'
	";
}
$row2 = postFunction('system_status', $database_columns2, 'main_systems.php');

$titel = 'Edit System';
include 'res/header.inc.php';
// $type = 'add_system';
// if (!empty($_GET['system'])) {
// 	$type = 'update_system';

// 	// Create connection
// 	$conn = new mysqli($servername, $username, $password, $dbname);
// 	// Check connection
// 	if ($conn->connect_error) {
//     	die("Connection failed: " . $conn->connect_error);
// 	}
//
// 	$sql = "SELECT *, system.serial_nr AS serial_nr
// 			FROM system
// 			LEFT JOIN system_status ON system_status.serial_nr = system.serial_nr
//             WHERE system.serial_nr = '$_GET[system]';";
// 	$result = $conn->query($sql);
// 	if (!$result) {
// 		die("Query 1 failed! " . $sql . "<br>" . $conn->error);
// 	}
//     $row = $result->fetch_array(MYSQLI_ASSOC);
//
//     $conn->close();
// }
// $path = 'post_add_update.php?type=' . $type;
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

				<div class="col-xs-8 col-xs-offset-4"><h4>System</h4></div>

				<?php
				if(!empty($_GET['id'])){
					echo '<input type="hidden" class="form-control" name="id" value="' . $_GET['id'] . '"/>';
				}
				?>

				<div class="form-group">
					<label for="serial_nr" class="col-xs-4 control-label">Serial Number</label>
					<div class="col-xs-8">
						<?php
						if (!empty($_GET['id'])) {
							echo '<input type="hidden" name="serial_nr" value="' . $row['serial_nr'] . '" />'
							. '<input type="text" class="form-control" placeholder="' . $row['serial_nr'] . '" disabled />';
						}else {
							echo '<input type="text" class="form-control" name="serial_nr" required />';
						}
						?>
					</div>
				</div>

				<div class="form-group">
					<label for="art_nr" class="col-xs-4 control-label">Art. Number</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="art_nr" <?= !empty($row['art_nr']) ?  'value="' . $row['art_nr'] . '"' : '' ; ?>>
					</div>
				</div>

				<div class="form-group">
					<label for="client" class="col-xs-4 control-label">Client</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="client" <?= !empty($row['client']) ?  'value="' . $row['client'] . '"' : '' ; ?>>
					</div>
				</div>

				<div class="form-group">
					<label for="place" class="col-xs-4 control-label">Place</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="place" <?= !empty($row['place']) ?  'value="' . $row['place'] . '"' : '' ; ?>>
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
					<label for="sensor_unit" class="col-xs-4 control-label">Sensor Unit</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="sensor_unit">

							<?php
							$sn = '';
							if(!empty($row['sensor_unit_sn'])){ $sn = $row['sensor_unit_sn'];}
							listUnusedSerialNr('sensor_unit', '	serial_nr NOT IN (
							SELECT system.sensor_unit_sn
							FROM system)'	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="control_system" class="col-xs-4 control-label">Control System</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="control_system">

							<?php
							$sn = '';
							if(!empty($row['control_system_sn'])){ $sn = $row['control_system_sn'];}
							listUnusedSerialNr('control_system', '	serial_nr NOT IN (
							SELECT system.control_system_sn
							FROM system)'	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="deep_system" class="col-xs-4 control-label">Deep System</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="deep_system">

							<?php
							$sn = '';
							if(!empty($row['deep_system_sn'])){ $sn = $row['deep_system_sn'];}
							listUnusedSerialNr('deep_system', '	serial_nr NOT IN (
							SELECT system.deep_system_sn
							FROM system)'	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="pd60" class="col-xs-4 control-label">PD60</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="pd60" <?= !empty($row['pd60']) ?  'value="' . $row['pd60'] . '"' : '' ; ?>>
					</div>
				</div>

				<div class="form-group">
					<label for="oc60_1" class="col-xs-4 control-label">OC60 1</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="oc60_1">

							<?php
							$sn = '';
							if(!empty($row['oc60_1_sn'])){ $sn = $row['oc60_1_sn'];}
							listUnusedSerialNr('leica', ' 	type = "OC60" AND
							serial_nr NOT IN (
							SELECT system.oc60_1_sn
							FROM system)
							AND
							serial_nr NOT IN (
							SELECT system.oc60_2_sn
							FROM system)'	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="oc60_2" class="col-xs-4 control-label">OC60 2</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="oc60_2">

							<?php
							$sn = '';
							if(!empty($row['oc60_2_sn'])){ $sn = $row['oc60_2_sn'];}
							listUnusedSerialNr('leica', ' 	type = "OC60" AND
							serial_nr NOT IN (
							SELECT system.oc60_1_sn
							FROM system)
							AND
							serial_nr NOT IN (
							SELECT system.oc60_2_sn
							FROM system)'	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="pav" class="col-xs-4 control-label">PAV</label>
					<div class="col-xs-8">
						<select class="combobox form-control" name="pav">

							<?php
							$sn = '';
							if(!empty($row['pav_sn'])){ $sn = $row['pav_sn'];}
							listUnusedSerialNr('leica', ' 	type = "PAV" AND
							serial_nr NOT IN (
							SELECT system.pav_sn
							FROM system)'	, $sn);
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="config" class="col-xs-4 control-label">Status</label>
					<div class="col-xs-8">
						<select class="form-control" name="status">
							<?php
							foreach($system_status_values as $i){
								$selected = '';
								if(!empty($row['status']) && $row['status'] == $i){$selected = 'selected';}
								$s = '<option value="%s" %s>%s</option>';
								echo sprintf($s, $i, $selected, $i);
							}
							?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="place" class="col-xs-4 control-label">Comment</label>
					<div class="col-xs-8">
						<textarea class="form-control" name="comment" rows="5"><?= !empty($row['comment']) ?  $row['comment'] : '' ; ?></textarea>
					</div>
				</div>

			</div>
			<div class="col-sm-3 col-sm-offset-1">

				<h4>Bitfile</h4>

				<div class="form-group">
					<div class="col-xs-5">
						<input type="text" class="form-control" name="oc" <?= !empty($row['oc']) ?  'value="' . $row['oc'] . '"' : '' ; ?>/>
					</div>
					<label for="oc" class="col-xs-7">OC</label>
				</div>

				<div class="form-group">
					<div class="col-xs-5">
						<input type="number" class="form-control" name="bitfile_topo" <?= !empty($row['bitfile_topo']) ?  'value="' . $row['bitfile_topo'] . '"' : '' ; ?>/>
					</div>
					<label for="bitfile_topo" class="col-xs-7">Topo</label>
				</div>

				<div class="form-group">
					<div class="col-xs-5">
						<input type="number" class="form-control" name="bitfile_shallow" <?= !empty($row['bitfile_shallow']) ?  'value="' . $row['bitfile_shallow'] . '"' : '' ; ?>/>
					</div>
					<label for="bitfile_shallow" class="col-xs-7">Shallow</label>
				</div>

				<div class="form-group">
					<div class="col-xs-5">
						<input type="number" class="form-control" name="bitfile_deep" <?= !empty($row['bitfile_deep']) ?  'value="' . $row['bitfile_deep'] . '"' : '' ; ?>/>
					</div>
					<label for="bitfile_deep" class="col-xs-7">Deep</label>
				</div>

				<div class="form-group">
					<div class="col-xs-5">
						<input type="number" class="form-control" name="bitfile_digitaizer1" <?= !empty($row['bitfile_digitaizer1']) ?  'value="' . $row['bitfile_digitaizer1'] . '"' : '' ; ?>/>
					</div>
					<label for="bitfile_digitaizer1" class="col-xs-7">Digitaizer 1</label>
				</div>

				<div class="form-group">
					<div class="col-xs-5">
						<input type="number" class="form-control" name="bitfile_digitaizer2" <?= !empty($row['bitfile_digitaizer2']) ?  'value="' . $row['bitfile_digitaizer2'] . '"' : '' ; ?>/>
					</div>
					<label for="bitfile_digitaizer2" class="col-xs-7">Digitaizer 2</label>
				</div>

				<div class="form-group">
					<div class="col-xs-5">
						<input type="number" class="form-control" name="bitfile_sat" <?= !empty($row['bitfile_sat']) ?  'value="' . $row['bitfile_sat'] . '"' : '' ; ?>/>
					</div>
					<label for="bitfile_sat" class="col-xs-7">SAT</label>
				</div>

				<h4>System status</h4>

				<div class="checkbox"><label>
					<input type="hidden" name="status_potta_heat" value=0 />
					<input type="checkbox" name="status_potta_heat" value=1 <?= !empty($row2['status_potta_heat']) && $row2['status_potta_heat'] ? 'checked' : ''; ?>/> Potta Heat
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="status_shallow_heat" value=0 />
					<input type="checkbox" name="status_shallow_heat" value=1 <?= !empty($row2['status_shallow_heat']) && $row2['status_shallow_heat'] ? 'checked' : ''; ?>/> Shallow Heat
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="status_scu_pdu" value=0 />
					<input type="checkbox" name="status_scu_pdu" value=1 <?= !empty($row2['status_scu_pdu']) && $row2['status_scu_pdu'] ? 'checked' : ''; ?>/> SCU & PDU
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="status_hv_topo" value=0 />
					<input type="checkbox" name="status_hv_topo" value=1 <?= !empty($row2['status_hv_topo']) && $row2['status_hv_topo'] ? 'checked' : ''; ?>/> HV Card Topo
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="status_hv_shallow" value=0 />
					<input type="checkbox" name="status_hv_shallow" value=1 <?= !empty($row2['status_hv_shallow']) && $row2['status_hv_shallow'] ? 'checked' : ''; ?>/> HV Card Shallow
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="status_hv_deep" value=0 />
					<input type="checkbox" name="status_hv_deep" value=1 <?= !empty($row2['status_hv_deep']) && $row2['status_hv_deep'] ? 'checked' : ''; ?>/> HV Card Deep
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="status_cat" value=0 />
					<input type="checkbox" name="status_cat" value=1 <?= !empty($row2['status_cat']) && $row2['status_cat'] ? 'checked' : ''; ?>/> Cat DC/DC
				</label></div>

				<div class="checkbox"><label>
					<input type="hidden" name="status_pwr_cable" value=0 />
					<input type="checkbox" name="status_pwr_cable" value=1 <?= !empty($row2['status_pwr_cable']) && $row2['status_pwr_cable'] ? 'checked' : ''; ?>/> Ground Power Cable
				</label></div>

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
			<div class="col-sm-12">
				<button type="submit" class="btn btn-default">Apply</button>
				<a href="main_systems.php" class="btn btn-default">Cancel</a>
				<a href="view_system.php?id=<?php echo $row['id']; ?>" class="btn btn-default">Go to System</a>
			</div>
		</div>
	</form>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>
