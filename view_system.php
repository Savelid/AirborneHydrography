<?php

$titel = 'System ' . $_GET['system'];
include 'res/header.inc.php';

if (!empty($_GET['system'])) {

	include 'res/config.inc.php';
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
	}

	$sql = " SELECT serial_nr, art_nr, client, configuration, sensor_unit_sn, control_unit_sn, deep_system_sn, cooling_system, comment,
          status_potta_heat, status_shallow_heat, status_scu_pdu, status_hv_topo, status_hv_shallow, status_hv_deep, status_cat, status_pwr_cable
			     FROM system 
           WHERE serial_nr = $_GET[system]";
	$result = $conn->query($sql);
	if (!$result) {
		die("Query failed!");
	}

    $row = $result->fetch_array(MYSQLI_ASSOC);
} else {  
  header("Location: systems.php?EmptySystem");
  die();
}

$conn->close();
?>
<section class="content">
<div class="row">
<div class="col-sm-7">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Info</h3>
    </div>
    <div class="panel-body">

    <div class="row">
      <div class="col-xs-6"><strong>Serial Number</strong></div>
      <div class="col-xs-6"><?php echo $row['serial_nr']?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Article Number</strong></div>
      <div class="col-xs-6"><?php echo $row['art_nr']?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Client</strong></div>
      <div class="col-xs-6"><?php echo $row['client']?></div>
    </div>

    <div class="row">
      <div class="col-xs-6"><strong>Configuration</strong></div>
      <div class="col-xs-6"><?php echo $row['configuration']?></div>
    </div>

    </div>
  </div><!-- end panel -->

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Comment</h3>
    </div>
    <div class="panel-body">
      <?php echo $row['comment']?>
    </div>
  </div><!-- end panel -->

</div><!-- end col -->

<div class="col-sm-3">
  <ul class="list-group">
    <li class="list-group-item <?=$row['status_potta_heat'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      Potta Heat Upgrade
    </li>
    <li class="list-group-item <?=$row['status_shallow_heat'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      Shallow Heat Upgrade
    </li>
    <li class="list-group-item <?=$row['status_scu_pdu'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      SCU & PDU Upgrade
    </li>
    <li class="list-group-item <?=$row['status_hv_topo'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      HV Card Topo Upgrade
    </li>
    <li class="list-group-item <?=$row['status_hv_shallow'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      HV Card Shallow Upgrade
    </li>
    <li class="list-group-item <?=$row['status_hv_deep'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      HV Card Deep Upgrade
    </li>
    <li class="list-group-item <?=$row['status_cat'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      CAT DC/DC
    </li>
    <li class="list-group-item <?=$row['status_pwr_cable'] ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
      Ground PWR Pupply Cable
    </li>
  </ul>

</div><!-- end col -->

</div><!-- end row -->
</section>

<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>