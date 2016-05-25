<?php
session_start();

include_once 'res/config.inc.php';

if (!empty($_GET['dataset_id'])) {

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = " SELECT *
	FROM datasets
	WHERE dataset_id = '$_GET[dataset_id]';";
	$result = $conn->query($sql);
	if (!$result) {
		die("Query 1 failed! <br>Error:" . $sql . "<br>" . $conn->error);
	}
	$query = $result->fetch_array(MYSQLI_ASSOC);

	$conn->close();
}
else {
	$_SESSION['showalert'] = 'true';
	$_SESSION['alert'] = 'No dataset with this id';
	header("Location: main_datasets.php");
	die();
}

$titel = 'Dataset ' . $query['dataset_id'];
include 'res/header.inc.php';
?>

<section class="top_content hidden-print">
	<a href="edit_datasets.php?dataset_id=<?php echo $_GET['dataset_id']; ?>" class="btn btn-default" role="button">Edit Dataset</a>
	<a href="edit_calibration.php?dataset_id=<?php echo $_GET['dataset_id']; ?>" class="btn btn-default" role="button">Make/Edit Calibration</a>
</section>

<section class="content">
	<div class="row">
		<div class="col-sm-7 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Info</h3>
				</div>
				<div class="panel-body">

					<div class="row">
						<div class="col-xs-6"><strong>Date</strong></div>
						<div class="col-xs-6"><?php echo $query['datetime'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>Dataset id</strong></div>
						<div class="col-xs-6"><?php echo $query['dataset_id'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>Disc id</strong></div>
						<div class="col-xs-6"><?php echo $query['disc_id'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>Location</strong></div>
						<div class="col-xs-6"><?php echo $query['location'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>System id</strong></div>
						<div class="col-xs-6"><?php echo $query['system_id'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>System model</strong></div>
						<div class="col-xs-6"><?php echo $query['system_model'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>Topo sensor 1</strong></div>
						<div class="col-xs-6"><?php echo $query['topo_sensor_1_sn'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>Topo sensor 2</strong></div>
						<div class="col-xs-6"><?php echo $query['topo_sensor_2_sn'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>Shallow sensor</strong></div>
						<div class="col-xs-6"><?php echo $query['shallow_sensor_sn'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>Deep sensor</strong></div>
						<div class="col-xs-6"><?php echo $query['deep_sensor_sn'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>SCU</strong></div>
						<div class="col-xs-6"><?php echo $query['scu_sn'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>IMU 1</strong></div>
						<div class="col-xs-6"><?php echo $query['imu_1_sn'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>IMU 2</strong></div>
						<div class="col-xs-6"><?php echo $query['imu_2_sn'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>PAV</strong></div>
						<div class="col-xs-6"><?php echo $query['leica_pav_sn'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>Camera</strong></div>
						<div class="col-xs-6"><?php echo $query['leica_cam_sn'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>Type of data</strong></div>
						<div class="col-xs-6"><?php echo $query['type_of_data'];?></div>
					</div>

					<div class="row">
						<div class="col-xs-6"><strong>Flight logs</strong></div>
						<div class="col-xs-6"><a href="<?php echo $query['flight_logs'];?>"><?php echo $query['flight_logs'];?></a></div>
					</div>

				</div>
			</div><!-- end panel -->

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Purpose of flight</h3>
				</div>
				<div class="panel-body">
					<?php echo nl2br($query['purpose_of_flight']); ?>
				</div>
			</div><!-- end panel -->

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Evaluation of flight</h3>
				</div>
				<div class="panel-body">
					<?php echo nl2br($query['evaluation_of_flight']); ?>
				</div>
			</div><!-- end panel -->

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Evaluation of data</h3>
				</div>
				<div class="panel-body">
					<?php echo nl2br($query['data_evaluation']); ?>
				</div>
			</div><!-- end panel -->

		</div><!-- end col -->

		<div class="col-lg-3 col-md-3 col-sm-5 col-xs-12">
			<ul class="list-group">
				<li class="list-group-item <?=$query['nav_data_processing_log'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
					Nav. data processing log
				</li>
				<li class="list-group-item <?=($query['calibration_file'] == 'Final') ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
					Calibration file: <br>
  					<b style="padding-left: 30px !important;"><?php echo $query['calibration_file'] ?></b>
				</li>
				<li class="list-group-item <?=$query['processing_settings_file'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
					Processing settings file
				</li>
				<li class="list-group-item <?=$query['configuration_file'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
					Configuration file
				</li>
				<li class="list-group-item <?=$query['calibration_report'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
					Calibration report
				</li>
				<li class="list-group-item <?=$query['acceptance_report'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
					Acceptance report
				</li>
				<li class="list-group-item <?=$query['system_fully_functional'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
					System fully functional
				</li>
				<li class="list-group-item <?=$query['raw_data_in_archive'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
					Raw data in archive
				</li>
				<li class="list-group-item <?=$query['raw_data_in_back_up_archive'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
					Raw data in back up archive
				</li>
			</ul>

		</div><!-- end col -->

	</div><!-- end row -->


</section>

<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>
