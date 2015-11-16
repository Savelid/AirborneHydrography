<?php

$titel = 'System ' . $_GET['system'];
include 'res/header.inc.php';

if (!empty($_GET['system'])) {

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	$sql = " SELECT *
			     FROM system
           LEFT JOIN system_status ON system_status.serial_nr = system.serial_nr
           WHERE system.serial_nr = $_GET[system];";
	$result = $conn->query($sql);
	if (!$result) {
		die("Query 1 failed! <br>Error:" . $sql . "<br>" . $conn->error);
	}
  $system = $result->fetch_array(MYSQLI_ASSOC);

  $sql = " SELECT *, leica_cam.configuration AS leica_cam_configuration, leica_cam.breakdown AS leica_cam_breakdown, leica_cam.operating_voltage AS leica_cam_operating_voltage
           FROM sensor_unit
           LEFT JOIN leica_cam ON sensor_unit.leica_cam_sn = leica_cam.serial_nr
           WHERE sensor_unit.serial_nr = $system[sensor_unit_sn];";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query 2 failed! <br>Error:" . $sql . "<br>" . $conn->error);
  }
  $sensor_unit = $result->fetch_array(MYSQLI_ASSOC);

  $sql = " SELECT *, control_system.serial_nr AS serial_nr, cc32.firmware AS cc32_firmware
           FROM control_system
           LEFT JOIN cc32 ON cc32_sn = cc32.serial_nr
           WHERE control_system.serial_nr = $system[control_system_sn];";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query 3 failed! <br>Error:" . $sql . "<br>" . $conn->error);
  }
  $control_system = $result->fetch_array(MYSQLI_ASSOC);

  $sql = " SELECT *
           FROM scu
           WHERE serial_nr = $control_system[scu_sn];";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query 4 failed! <br>Error:" . $sql . "<br>" . $conn->error);
  }
  $scu = $result->fetch_array(MYSQLI_ASSOC);

    $sql = " SELECT *
           FROM deep_system
           WHERE serial_nr = $system[deep_system_sn];";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query 5 failed! <br>Error:" . $sql . "<br>" . $conn->error);
  }
  $deep_system = $result->fetch_array(MYSQLI_ASSOC);

  $conn->close();
} 
else {  
  header("Location: systems.php?EmptySystem");
  die();
}
?>

<section class="top_content">
  <a href="edit_system.php?system=<?php echo $_GET['system']; ?>" class="btn btn-default" role="button">Edit system</a>
</section>

<section class="content">
<div class="row">
<div class="col-sm-6 col-xs-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Info</h3>
    </div>
    <div class="panel-body">

    <div class="row">
      <div class="col-xs-6"><strong>Serial Number</strong></div>
      <div class="col-xs-6"><?php echo $system['serial_nr'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Article Number</strong></div>
      <div class="col-xs-6"><?php echo $system['art_nr'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Client</strong></div>
      <div class="col-xs-6"><?php echo $system['client'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Place</strong></div>
      <div class="col-xs-6"><?php echo $system['place'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Configuration</strong></div>
      <div class="col-xs-6"><?php echo $system['configuration'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Status</strong></div>
      <div class="col-xs-6"><?php echo $system['status'];?></div>
    </div>

    </div>
  </div><!-- end panel -->

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Comment</h3>
    </div>
    <div class="panel-body">
      <?php echo nl2br($system['comment']); ?>
    </div>
  </div><!-- end panel -->

</div><!-- end col -->

<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
  <ul class="list-group">
    <li class="list-group-item <?=$system['status_potta_heat'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      Potta Heat Upgrade
    </li>
    <li class="list-group-item <?=$system['status_shallow_heat'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      Shallow Heat Upgrade
    </li>
    <li class="list-group-item <?=$system['status_scu_pdu'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      SCU & PDU Upgrade
    </li>
    <li class="list-group-item <?=$system['status_hv_topo'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      HV Card Topo Upgrade
    </li>
    <li class="list-group-item <?=$system['status_hv_shallow'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      HV Card Shallow Upgrade
    </li>
    <li class="list-group-item <?=$system['status_hv_deep'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      HV Card Deep Upgrade
    </li>
    <li class="list-group-item <?=$system['status_cat'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      CAT DC/DC
    </li>
    <li class="list-group-item <?=$system['status_pwr_cable'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      Ground PWR Pupply Cable
    </li>
  </ul>

</div><!-- end col -->
<div class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
<div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Bitfile</h3>
    </div>
    <div class="panel-body">

    <div class="row">
      <div class="col-xs-6"><strong>OC</strong></div>
      <div class="col-xs-6"><?php echo $system['oc'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Topo</strong></div>
      <div class="col-xs-6"><?php echo $system['bitfile_topo'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Shallow</strong></div>
      <div class="col-xs-6"><?php echo $system['bitfile_shallow'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Deep</strong></div>
      <div class="col-xs-6"><?php echo $system['bitfile_deep'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Digitaizer 1</strong></div>
      <div class="col-xs-6"><?php echo $system['bitfile_digitaizer1'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Digitaizer 2</strong></div>
      <div class="col-xs-6"><?php echo $system['bitfile_digitaizer2'];?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>SAT</strong></div>
      <div class="col-xs-6"><?php echo $system['bitfile_sat'];?></div>
    </div>

    </div>
  </div><!-- end panel -->

</div><!-- end col -->

</div><!-- end row -->


<!-- ###### Component lists ###### -->


<div class="row">

    <div class="col-sm-12 col-md-12 col-lg-6">

      <!--###### Sensor Unit List ######-->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" href="#sensor_unit">Sensor Unit: <?php echo $sensor_unit['serial_nr'];?></a>
          </h4>
        </div>
        <div id="sensor_unit" class="panel-collapse collapse in">
          <ul class="list-group">
            <li class="list-group-item">IMU: <?php echo $sensor_unit['imu'];?></li>
            <li class="list-group-item"><a href="#leica_cam" data-toggle="collapse" data-target="#leica_cam">Leica camera: <?php echo $sensor_unit['leica_cam_sn'];?></a></li>
            <div id="leica_cam" class="panel-collapse collapse">
              <li class="list-group-item sub_item">Config: <?php echo $sensor_unit['leica_cam_configuration'];?></li>
              <li class="list-group-item sub_item">Breakdown: <?php echo $sensor_unit['leica_cam_breakdown'];?></li>
              <li class="list-group-item sub_item">Operating Voltage: <?php echo $sensor_unit['leica_cam_operating_voltage'];?></li>
              <li class="list-group-item sub_item">Firmware: <?php echo $sensor_unit['firmware'];?></li>
            <div class="panel-footer sub_item"><a href="#"><span class="glyphicon glyphicon-pencil"></span>Edit leica camera</a></div>
            </div>
            <li class="list-group-item">Leica lens: <?php echo $sensor_unit['leica_lens'];?></li>
            <li class="list-group-item"><a href="#topo" data-toggle="collapse" class="active" data-target="#topo">Topo sensor: <?php echo $sensor_unit['topo_sensor_sn'];?></a></li>
            <div id="topo" class="panel-collapse collapse">
              <div class="panel-footer sub_item">
                <a href="edit_sensor.php?serial_nr=<?php echo $sensor_unit['topo_sensor_sn'];?>"><span class="glyphicon glyphicon-pencil"></span> Edit topo sensor </a> 
                <a href="view_sensor.php?serial_nr=<?php echo $sensor_unit['topo_sensor_sn'];?>"><span class="glyphicon glyphicon-hand-up"></span> View topo sensor </a> 
              </div>
            </div>
            <li class="list-group-item"><a href="#topo2" data-toggle="collapse" class="active" data-target="#topo">Topo sensor 2: <?php echo $sensor_unit['topo_sensor_2_sn'];?></a></li>
            <div id="topo2" class="panel-collapse collapse">
              <div class="panel-footer sub_item">
                <a href="edit_sensor.php?serial_nr=<?php echo $sensor_unit['topo_sensor_2_sn'];?>"><span class="glyphicon glyphicon-pencil"></span> Edit topo sensor </a> 
                <a href="view_sensor.php?serial_nr=<?php echo $sensor_unit['topo_sensor_2_sn'];?>"><span class="glyphicon glyphicon-hand-up"></span> View topo sensor </a> 
              </div>
            </div>
            <li class="list-group-item"><a href="#shallow" data-toggle="collapse" class="active" data-target="#shallow">Shallow sensor: <?php echo $sensor_unit['shallow_sensor_sn'];?></a></li>
            <div id="shallow" class="panel-collapse collapse">
              <div class="panel-footer sub_item">
                <a href="edit_sensor.php?serial_nr=<?php echo $sensor_unit['shallow_sensor_sn'];?>"><span class="glyphicon glyphicon-pencil"></span> Edit shallow sensor </a> 
                <a href="view_sensor.php?serial_nr=<?php echo $sensor_unit['shallow_sensor_sn'];?>"><span class="glyphicon glyphicon-hand-up"></span> View shallow sensor </a> 
              </div>
            </div>
            <li class="list-group-item">Comment: <?php echo $sensor_unit['comment'];?></li>
          </ul>
          <div class="panel-footer"><a href="edit_sensor_unit.php?serial_nr=<?php echo $sensor_unit['serial_nr'];?>"><span class="glyphicon glyphicon-pencil"></span> Edit sensor unit </a></div>
        </div>
      </div>

    </div><!-- end col -->
    <div class="col-sm-12 col-md-12 col-lg-6">

      <!--###### Control System List ######-->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" href="#control_system">Control System: <?php echo $control_system['serial_nr'];?></a>
          </h4>
        </div>
        <div id="control_system" class="panel-collapse collapse in">
          <ul class="list-group">
            <li class="list-group-item">Battery: <?php echo $control_system['battery'];?></li>
            <li class="list-group-item">CC32: <?php echo $control_system['cc32_sn'] . " Firmware: " . $control_system['cc32_firmware'];?></li>
            <li class="list-group-item">PDU: <?php echo $control_system['pdu'];?></li>
            <li class="list-group-item"><a href="#scu" data-toggle="collapse" data-target="#scu">SCU: <?php echo $control_system['scu_sn'];?></a></li>
            <div id="scu" class="panel-collapse collapse">
              <li class="list-group-item sub_item">Config: <?php echo $scu['configuration'];?></li>
              <li class="list-group-item sub_item">Digitaizer1: <?php echo $scu['digitaizer1'];?></li>
              <li class="list-group-item sub_item">Digitaizer2: <?php echo $scu['digitaizer2'];?></li>
              <li class="list-group-item sub_item">SAT: <?php echo $scu['sat'];?></li>
              <li class="list-group-item sub_item">CPU: <?php echo $scu['cpu'];?></li>
              <li class="list-group-item sub_item">Status: <?php echo $scu['status'];?></li>
              <li class="list-group-item sub_item">Comment: <?php echo $scu['comment'];?></li>
            <div class="panel-footer sub_item"><a href="edit_scu.php?serial_nr=<?php echo $control_system['scu_sn'];?>"><span class="glyphicon glyphicon-pencil"></span> Edit SCU </a></div>
            </div>
            <li class="list-group-item">Comment: <?php echo $control_system['comment'];?></li>
          </ul>
          <div class="panel-footer"><a href="edit_control_system.php?serial_nr=<?php echo $control_system['serial_nr'];?>"><span class="glyphicon glyphicon-pencil"></span> Edit control system </a></div>
        </div>
      </div>

    </div><!-- end col -->
    <div class="col-sm-12 col-md-12 col-lg-6">

      <!--###### Deep System List ######-->
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" href="#deep_system">Deep System: <?php echo $deep_system['serial_nr'];?></a>
          </h4>
        </div>
        <div id="deep_system" class="panel-collapse collapse in">
          <ul class="list-group">
            <li class="list-group-item">Cooling System: <?php echo $deep_system['cooling_system'];?></li>
            <li class="list-group-item">IMU: <?php echo $deep_system['imu'];?></li>
            <li class="list-group-item">Pro-pack: <?php echo $deep_system['pro_pack'];?></li>
            <li class="list-group-item"><a href="#deep" data-toggle="collapse" data-target="#deep">Deep sensor: <?php echo $deep_system['deep_sensor_sn'];?></a></li>
            <div id="deep" class="panel-collapse collapse">
              <div class="panel-footer sub_item">
                <a href="edit_sensor.php?serial_nr=<?php echo $deep_system['deep_sensor_sn'];?>"><span class="glyphicon glyphicon-pencil"></span> Edit deep sensor </a> 
                <a href="view_sensor.php?serial_nr=<?php echo $deep_system['deep_sensor_sn'];?>"><span class="glyphicon glyphicon-hand-up"></span> View deep sensor </a> 
              </div>
            </div>
            <li class="list-group-item">Comment: <?php echo $deep_system['comment'];?></li>
          </ul>
          <div class="panel-footer"><a href="edit_deep_system.php?serial_nr=<?php echo $deep_system['serial_nr'];?>"><span class="glyphicon glyphicon-pencil"></span> Edit deep system </a> </div>
        </div>
      </div>

    </div><!-- end col -->

</div><!-- end row -->


</section>

<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>