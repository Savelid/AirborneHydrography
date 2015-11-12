<?php
$titel = 'Sensor ' . $_GET['serial_nr'];
include 'res/header.inc.php'; 
?>
<?php
if (!empty($_GET['serial_nr'])) {
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = " SELECT system.serial_nr AS system_serial_nr, sensor_unit.serial_nr AS sensor_unit_serial_nr, deep_system.serial_nr AS deep_system_serial_nr
           FROM system, sensor_unit, deep_system
           WHERE (system.sensor_unit_sn = sensor_unit.serial_nr AND (sensor_unit.topo_sensor_sn = $_GET[serial_nr]) OR (sensor_unit.shallow_sensor_sn = $_GET[serial_nr]))
           OR (system.deep_system_sn = deep_system.serial_nr AND deep_system.deep_sensor_sn = $_GET[serial_nr])
           LIMIT 1";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query failed!" . $sql . "<br><br>" . $conn->error);
  }
  $parent = $result->fetch_array(MYSQLI_ASSOC);

  $sql = " SELECT *
           FROM sensor 
           WHERE serial_nr = '$_GET[serial_nr]'
           LIMIT 1";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query failed!" . $sql . "<br><br>" . $conn->error);
  }
  $sensor = $result->fetch_array(MYSQLI_ASSOC);

  if(empty($sensor['laser_sn']) || $sensor['laser_sn'] == ''){$sensor['laser_sn'] = '0000';}
  $sql = " SELECT *
           FROM laser
           WHERE serial_nr = $sensor[laser_sn]
           LIMIT 1";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query failed!" . $sql . "<br><br>" . $conn->error);
  }
  $laser = $result->fetch_array(MYSQLI_ASSOC);

  if(empty($sensor['hv_card_sn']) || $sensor['hv_card_sn'] == ''){$sensor['hv_card_sn'] = '0000';}
  $sql = " SELECT *
           FROM hv_card
           WHERE serial_nr = $sensor[hv_card_sn]
           LIMIT 1";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query failed!" . $sql . "<br><br>" . $conn->error);
  }
  $hv_card1 = $result->fetch_array(MYSQLI_ASSOC);
  
  if(empty($sensor['hv_card_2_sn']) || $sensor['hv_card_2_sn'] == ''){$sensor['hv_card_2_sn'] = '0000';}
  $sql = " SELECT *
           FROM hv_card
           WHERE serial_nr = $sensor[hv_card_2_sn]
           LIMIT 1";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query failed!" . $sql . "<br><br>" . $conn->error);
  }
  $hv_card2 = $result->fetch_array(MYSQLI_ASSOC);

  if(empty($sensor['receiver_unit_sn']) || $sensor['receiver_unit_sn'] == ''){$sensor['receiver_unit_sn'] = '0000';}
  $sql = " SELECT *, receiver_unit.serial_nr AS serial_nr
           FROM receiver_unit
           LEFT JOIN receiver_chip ON receiver_chip_sn = receiver_chip.serial_nr
           WHERE receiver_unit.serial_nr = $sensor[receiver_unit_sn]
           LIMIT 1";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query failed!" . $sql . "<br><br>" . $conn->error);
  }
  $receiver_unit1 = $result->fetch_array(MYSQLI_ASSOC);

  if(empty($sensor['receiver_unit_2_sn']) || $sensor['receiver_unit_2_sn'] == ''){$sensor['receiver_unit_2_sn'] = '0000';}
  $sql = " SELECT *, receiver_unit.serial_nr AS serial_nr
           FROM receiver_unit
           LEFT JOIN receiver_chip ON receiver_chip_sn = receiver_chip.serial_nr
           WHERE receiver_unit.serial_nr = $sensor[receiver_unit_2_sn]
           LIMIT 1";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query failed!" . $sql . "<br><br>" . $conn->error);
  }
  $receiver_unit2 = $result->fetch_array(MYSQLI_ASSOC);

  $conn->close();
} 
else {  
  header("Location: parts.php?alert=NoSerialNumber");
  die();
}
if(!isset($sensor['serial_nr'])){
  header("Location: parts.php?alert=NoSerialNumber");
  die();
} 
?>
<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>


<section class="top_content">
  <a href="edit_sensor.php?serial_nr=<?php echo $_GET['serial_nr'] ?>" class="btn btn-default" role="button">Edit sensor</a>
</section>
<section class="content">
 
 <div class="row">
  <div class="col-sm-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Info</h3>
      </div>
      <div class="panel-body">

        <div class="row">
          <div class="col-xs-6"><strong>Serial Number:</strong></div>
          <div class="col-xs-6"><?php echo $_GET['serial_nr'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Configuration:</strong></div>
          <div class="col-xs-6"><?php echo $sensor['sensor_type'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Status:</strong></div>
          <div class="col-xs-6"><?php echo $sensor['status'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Parent System:</strong></div>
          <div class="col-xs-6">
            <a href="view_system.php?system=<?php echo $parent['system_serial_nr'];?>"><?php echo $parent['system_serial_nr'];?></a>
          </div>
        </div>
<?php
$p_name = '';
$p_sn = '';

if($sensor['sensor_type'] == 'topo' || $sensor['sensor_type'] == 'shallow') {
  $p_name = 'Sensor Unit';
  $p_sn = $parent['sensor_unit_serial_nr'];;
}
else if($sensor['sensor_type'] == 'deep') {
  $p_name = 'Deep System';
  $p_sn = $parent['deep_system_serial_nr'];
}
  $p_string = '
        <div class="row">
          <div class="col-xs-6"><strong>Parent %s</strong></div>
          <div class="col-xs-6">%s</div>
        </div>
        ';
  echo sprintf($p_string, $p_name, $p_sn);
?>
      </div><!-- end panel body -->
    </div><!-- end panel -->
  </div><!-- end col -->
  <div class="col-sm-6">
    <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">DPS Values</h3>
        </div>
        <div class="panel-body">

        <div class="row">
          <div class="col-xs-6"><strong>Input offset t0:</strong></div>
          <div class="col-xs-6"><?php echo $sensor['dps_value_input_offset_t0'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Input offset rec:</strong></div>
          <div class="col-xs-6"><?php echo $sensor['dps_value_input_offset_rec'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Pulse width t0:</strong></div>
          <div class="col-xs-6"><?php echo $sensor['dps_value_pulse_width_t0'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Pulse width rec:</strong></div>
          <div class="col-xs-6"><?php echo $sensor['dps_value_pulse_width_rec'];?></div>
        </div>

        </div><!-- end panel body -->
      </div><!-- end panel -->
    </div><!-- end col -->
  </div><!-- end row -->

  <div class="row">
    <div class="col-sm-12">

  <!--###### Table ######-->
      <div class="panel panel-default">
        <div class="panel-heading" id="headingComponents">
          <h4 class="panel-title">
            Components
          </h4>
        </div>
        <div class="panel-body panel_table">
          <table class="table table-striped table-responsive table_700">
            <thead>
              <tr>
                <th>Type</th>
                <th>Serial nr.</th>
                <th colspan=4></th>
              </tr>
            </thead>
            <tbody>
                <tr>
                  <td>CAT:</td>
                  <td><?php echo $sensor['cat'];?></td>
                  <td colspan=4></td>
                </tr>

                <tr>
                  <td>FPGA ID:</td>
                  <td><?php echo $sensor['fpga_id'];?></td>
                  <td colspan=4></td>
                </tr>

                <tr>
                  <td>Laser:</td>

<?php
$infoText  = "000: " . "<strong>" . $laser['v_0'] . "</strong><br/>";
$infoText .= "005: " . "<strong>" . $laser['v_5'] . "</strong><br/>";
$infoText .= "010: " . "<strong>" . $laser['v_10'] . "</strong><br/>";
$infoText .= "015: " . "<strong>" . $laser['v_15'] . "</strong><br/>";
$infoText .= "020: " . "<strong>" . $laser['v_20'] . "</strong><br/>";
$infoText .= "025: " . "<strong>" . $laser['v_25'] . "</strong><br/>";
$infoText .= "030: " . "<strong>" . $laser['v_30'] . "</strong><br/>";
$infoText .= "040: " . "<strong>" . $laser['v_40'] . "</strong><br/>";
$infoText .= "050: " . "<strong>" . $laser['v_50'] . "</strong><br/>";
$infoText .= "060: " . "<strong>" . $laser['v_60'] . "</strong><br/>";
$infoText .= "070: " . "<strong>" . $laser['v_70'] . "</strong><br/>";
$infoText .= "080: " . "<strong>" . $laser['v_80'] . "</strong><br/>";
$infoText .= "090: " . "<strong>" . $laser['v_90'] . "</strong><br/>";
$infoText .= "100: " . "<strong>" . $laser['v_100'] . "</strong><br/>";
$linkAdr = "edit_laser.php?serial_nr=" . $sensor['laser_sn'];
?>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $sensor['laser_sn'];?>
                    </a>

                    
                  </td>
                  <td colspan=4>
                    <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="<?php echo $infoText ?>">
                      Info
                    </button>
                  </td>
                </tr>

<?php
$linkAdr = "edit_hv_card.php?serial_nr=" . $sensor['hv_card_sn'];
?>
                <tr>
                  <td>HV Card:</td>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $sensor['hv_card_sn'];?>
                    </a>
                  </td>
                  <td>Article nr: <?php echo $hv_card1['art_nr'];?></td>
                  <td>K-value: <?php echo $hv_card1['k_value'];?></td>
                  <td colspan=2>M-value: <?php echo $hv_card1['m_value'];?></td>
                </tr>

<?php
if($sensor['sensor_type'] == 'deep'){
$linkAdr = "edit_hv_card.php?serial_nr=" . $sensor['hv_card_2_sn'];

echo '          <td>HV Card 2:</td>
                  <td>
                    <a href="' . $linkAdr . '" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      ' . $sensor["hv_card_2_sn"] . '
                    </a>
                  </td>
                  <td>Article nr: ' . $hv_card2["art_nr"] . '</td>
                  <td>K-value: ' . $hv_card2["k_value"] . '</td>
                  <td colspan=2>M-value: ' . $hv_card2["m_value"] . '</td>
                </tr>
                ';
}
?>
<?php
$linkAdr = "edit_receiver.php?serial_nr=" . $sensor['receiver_unit_sn'];
?>
                <tr>
                  <td>Receiver:</td>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $sensor['receiver_unit_sn'];?>
                    </a>
                  </td>
                  <td>Article nr: <?php echo $receiver_unit1['art_nr'];?></td>
                  <td>Chip: <?php echo $receiver_unit1['receiver_chip_sn'];?></td>
                  <td>Breakdown V: <?php echo $receiver_unit1['breakdown_voltage'];?></td>
                  <td>Operation V: <?php echo $receiver_unit1['operating_voltage'];?></td>
                </tr>

<?php
$linkAdr = "edit_receiver.php?serial_nr=" . $sensor['receiver_unit_2_sn'];
if($sensor['sensor_type'] == 'deep'){
  echo '<tr>
                  <td>Receiver 2:</td>
                  <td>
                    <a href="' . $linkAdr . '" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      ' . $sensor['receiver_unit_2_sn'] . '
                    </a>
                  </td>
                  <td>Article nr: ' . $receiver_unit2['art_nr'] . '</td>
                  <td>Chip: ' . $receiver_unit2['receiver_chip_sn'] . '</td>
                  <td>Breakdown V: ' . $receiver_unit2['breakdown_voltage'] . '</td>
                  <td>Operation V: ' . $receiver_unit2['operating_voltage'] . '</td>
                </tr>
  ';
}
?>
                

            </tbody>
          </table>

        </div><!-- end panel body -->
      </div><!-- end panel -->
    </div><!-- end col -->
  </div><!-- end row -->


</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>