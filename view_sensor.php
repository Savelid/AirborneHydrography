<?php
$titel = 'Sensor ' . $_GET['serial_nr'];
include 'res/header.inc.php'; 
?>
<?php
if (!empty($_GET['serial_nr'])) {

  include 'res/config.inc.php';
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = " SELECT *
           FROM sensor 
           WHERE serial_nr = $_GET[serial_nr]";
  $result = $conn->query($sql);
  if (!$result) {
    die("Query failed!");
  }

    $row = $result->fetch_array(MYSQLI_ASSOC);
} else {  
  header("Location: parts.php?NoSerialNumber");
  die();
}

$conn->close();
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
          <div class="col-xs-6"><?php echo $row['serial_nr'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Configuration:</strong></div>
          <div class="col-xs-6"><?php echo $row['sensor_type'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Status:</strong></div>
          <div class="col-xs-6"><?php echo $row['status'];?></div>
        </div>
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
          <div class="col-xs-6"><?php echo $row['dps_value_input_offset_t0'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Input offset rec:</strong></div>
          <div class="col-xs-6"><?php echo $row['dps_value_input_offset_rec'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Pulse width t0:</strong></div>
          <div class="col-xs-6"><?php echo $row['dps_value_pulse_width_t0'];?></div>
        </div>

        <div class="row">
          <div class="col-xs-6"><strong>Pulse width rec:</strong></div>
          <div class="col-xs-6"><?php echo $row['dps_value_pulse_width_rec'];?></div>
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
                  <td><?php echo $row['cat'];?></td>
                  <td></td>
                </tr>

                <tr>
                  <td>FPGA ID:</td>
                  <td><?php echo $row['fpga_id'];?></td>
                  <td></td>
                </tr>

                <tr>
                  <td>Laser:</td>

<?php
$infoText = "";
$linkAdr = "edit_laser.php?serial_nr=" . $row['laser_sn'];
?>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $row['laser_sn'];?>
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
$infoText = "Config: " .$row['sensor_type']. "<br> k-value: 1.4 <br> m-value: 15";
$linkAdr = "edit_hv_card.php?serial_nr=" . $row['hv_card_sn'];
?>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $row['hv_card_sn'];?>
                    </a>

                    <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="<?php echo $infoText ?>">
                      Info
                    </button>
                  </td>

<?php
$infoText = "Config: " .$row['sensor_type']. "<br> k-value: 1.4 <br> m-value: 15";
$linkAdr = "edit_hv_card.php?serial_nr=" . $row['hv_card_2_sn'];
?>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $row['hv_card_2_sn'];?>
                    </a>

                    <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="<?php echo $infoText ?>">
                      Info
                    </button>
                  </td>
                </tr>

                <tr>
                  <td>Receiver Units:</td>
                  <td><?php echo $row['receiver_unit'];?></td>
                  <td><?php echo $row['receiver_unit_2'];?></td>
                </tr>

                <tr>
                  <td>Receiver Chips:</td>

<?php
$infoText = "Config: " .$row['sensor_type']. "<br> k-value: 1.4 <br> m-value: 15";
$linkAdr = "edit_receiver_chip.php?serial_nr=" . $row['receiver_chip_sn'];
?>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $row['receiver_chip_sn'];?>
                    </a>

                    <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="<?php echo $infoText ?>">
                      Info
                    </button>
                  </td>

<?php
$infoText = "Config: " .$row['sensor_type']. "<br> k-value: 1.4 <br> m-value: 15";
$linkAdr = "edit_receiver_chip.php?serial_nr=" . $row['receiver_chip_2_sn'];
?>
                  <td>
                    <a href="<?php echo $linkAdr;?>" class="btn btn-default btn-sm">
                      <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                      <?php echo $row['receiver_chip_2_sn'];?>
                    </a>

                    <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="<?php echo $infoText ?>">
                      Info
                    </button>
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