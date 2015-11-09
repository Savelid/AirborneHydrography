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

  if(empty($sensor['receiver_chip_sn']) || $sensor['receiver_chip_sn'] == ''){$sensor['receiver_chip_sn'] = '0000';}
  $sql = " SELECT *
           FROM receiver_chip
           WHERE serial_nr = $sensor[receiver_chip_sn]
           LIMIT 1";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query failed!" . $sql . "<br><br>" . $conn->error);
  }
  $receiver_chip1 = $result->fetch_array(MYSQLI_ASSOC);

  if(empty($sensor['receiver_chip_2_sn']) || $sensor['receiver_chip_2_sn'] == ''){$sensor['receiver_chip_2_sn'] = '0000';}
  $sql = " SELECT *
           FROM receiver_chip
           WHERE serial_nr = $sensor[receiver_chip_2_sn]
           LIMIT 1";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query failed!" . $sql . "<br><br>" . $conn->error);
  }
  $receiver_chip2 = $result->fetch_array(MYSQLI_ASSOC);

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
          <table class="table table-striped table-responsive my_table">
            <thead>
              <tr>
                <th>Type</th>
                <th>Serial nr.</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
                <tr>
                  <td>CAT:</td>
                  <td><?php echo $sensor['cat'];?></td>
                  <td></td>
                </tr>

                <tr>
                  <td>FPGA ID:</td>
                  <td><?php echo $sensor['fpga_id'];?></td>
                  <td></td>
                </tr>

                <tr>
                  <td>Laser:</td>

<?php
$infoText = "0: " . $laser['v_0'] . "<br>";
$infoText .= "5: " . $laser['v_5'] . "<br>";
$infoText .= "10: " . $laser['v_10'] . "<br>";
$infoText .= "15: " . $laser['v_15'] . "<br>";
$infoText .= "20: " . $laser['v_20'] . "<br>";
$infoText .= "25: " . $laser['v_25'] . "<br>";
$infoText .= "30: " . $laser['v_30'] . "<br>";
$infoText .= "40: " . $laser['v_40'] . "<br>";
$infoText .= "50: " . $laser['v_50'] . "<br>";
$infoText .= "60: " . $laser['v_60'] . "<br>";
$infoText .= "70: " . $laser['v_70'] . "<br>";
$infoText .= "80: " . $laser['v_80'] . "<br>";
$infoText .= "90: " . $laser['v_90'] . "<br>";
$infoText .= "100: " . $laser['v_100'] . "<br>";
$linkAdr = "edit_laser.php?serial_nr=" . $sensor['laser_sn'];
?>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $sensor['laser_sn'];?>
                    </a>

                    <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="<?php echo $infoText ?>">
                      Info
                    </button>
                  </td>
                  <td></td>
                </tr>

                <tr>
                  <td>HV Cards:</td>

<?php
$infoText = '';
if($sensor['sensor_type'] == 'topo' || $sensor['sensor_type'] == 'shallow') {
  $infoText = "k-value: " . $hv_card1['k_value'] . "<br>";
  $infoText .= "m-value: " . $hv_card1['m_value'] . "<br>";
}
else if($sensor['sensor_type'] == 'deep') {
  $infoText = "0: " . $hv_card1['hv_0'] . "<br>";
  $infoText .= "500: " . $hv_card1['v_500'] . "<br>";
  $infoText .= "1000: " . $hv_card1['v_1000'] . "<br>";
  $infoText .= "1500: " . $hv_card1['v_1500'] . "<br>";
  $infoText .= "2000: " . $hv_card1['v_2000'] . "<br>";
  $infoText .= "2500: " . $hv_card1['v_2500'] . "<br>";
  $infoText .= "3000: " . $hv_card1['v_3000'] . "<br>";
  $infoText .= "3250: " . $hv_card1['v_3250'] . "<br>";
}

$linkAdr = "edit_hv_card.php?serial_nr=" . $sensor['hv_card_sn'];
?>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $sensor['hv_card_sn'];?>
                    </a>

                    <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="<?php echo $infoText ?>">
                      Info
                    </button>
                  </td>
                    <td>
<?php
if($sensor['sensor_type'] == 'deep') {

  $infoText = "0: " . $hv_card2['hv_0'] . "<br>";
  $infoText .= "500: " . $hv_card2['v_500'] . "<br>";
  $infoText .= "1000: " . $hv_card2['v_1000'] . "<br>";
  $infoText .= "1500: " . $hv_card2['v_1500'] . "<br>";
  $infoText .= "2000: " . $hv_card2['v_2000'] . "<br>";
  $infoText .= "2500: " . $hv_card2['v_2500'] . "<br>";
  $infoText .= "3000: " . $hv_card2['v_3000'] . "<br>";
  $infoText .= "3250: " . $hv_card2['v_3250'] . "<br>";

  $linkAdr = "edit_hv_card.php?serial_nr=" . $sensor['hv_card_2_sn'];
  $receiver_chip_2_string = '
                    <a href="%s" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      %s
                    </a>
                    <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="%s">
                      Info
                    </button>
                    ';
  echo sprintf($receiver_chip_2_string, $linkAdr, $sensor['hv_card_2_sn'], $infoText);
}
?>
                  </td>
                </tr>

                <tr>
                  <td>Receiver Units:</td>
                  <td><?php echo $sensor['receiver_unit'];?></td>
                  <td><?php echo $sensor['receiver_unit_2'];?></td>
                </tr>

                <tr>
                  <td>Receiver Chips:</td>

<?php
$infoText = "Unit: " . $receiver_chip1['unit'] . "<br/>";
$infoText .= "Firmware: " . $receiver_chip1['firmware'] . "<br/>";
$linkAdr = "edit_receiver_chip.php?serial_nr=" . $sensor['receiver_chip_sn'];
?>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $sensor['receiver_chip_sn'];?>
                    </a>

                    <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="<?php echo $infoText ?>">
                      Info
                    </button>
                  </td>

                  <td>
<?php
if($sensor['sensor_type'] == 'deep') {
  $infoText = "Unit: " . $receiver_chip2['unit'] . "<br/>";
  $infoText .= "Firmware: " . $receiver_chip2['firmware'] . "<br/>";
  $linkAdr = "edit_receiver_chip.php?serial_nr=" . $sensor['receiver_chip_2_sn'];
  $receiver_chip_2_string = '
                    <a href="%s" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      %s
                    </a>
                    <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="%s">
                      Info
                    </button>
                    ';
  echo sprintf($receiver_chip_2_string, $linkAdr, $sensor['receiver_chip_2_sn'], $infoText);
}
?>
                    
                  </td>
                </tr>
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