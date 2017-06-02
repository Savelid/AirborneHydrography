<?php
session_start();
$titel = 'Parts';
include 'res/header.inc.php';
?>
<script>
// Helper function for eject-modal
$(function () {
  $('[data-toggle="popover"]').popover()
})
$(function(){
  $('#confirmModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var serial_nr = button.data('form_serial_nr') // Extract info from data-* attributes
    var table = button.data('form_table')
    var column = button.data('form_column')
    var column2 = button.data('form_column2')
    var modal = $(this)
    modal.find('.modal-title').text('Eject ' + serial_nr)
    modal.find('.modal-body p').text('This will remove ' + column + ' , ' + column2 + ' from ' + table + ' where serial number is ' + serial_nr)
    modal.find('.modal-body #serial_nr').val(serial_nr)
    modal.find('.modal-body #table').val(table)
    modal.find('.modal-body #column').val(column)
    modal.find('.modal-body #column2').val(column2)
  })
})
</script>
<!-- Modal for UI confermation and initiating of Eject -->
<div class="modal fade" id="confirmModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Confirm Eject</h4>
      </div>
      <div class="modal-body">
        <p></p>
        <form action="post_eject.php" method="post" id="ejectForm">
          <input type="hidden" name="serial_nr" id="serial_nr" />
          <input type="hidden" name="table" id="table" />
          <input type="hidden" name="column" id="column" />
          <input type="hidden" name="column2" id="column2" />

          <h4>Log</h4>
          <label for="user">User</label>
          <div>
            <input type="text" class="form-control" name="user" <?= !empty($_SESSION['user']) ? 'value="' . $_SESSION['user'] . '"' : ''; ?> required />
          </div>

          <label for="log_comment">Log Comment</label>
          <div>
            <textarea class="form-control" name="log_comment" rows="3"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" form="ejectForm" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<section class="content hidden-print">

  <?php
  // Shows status messages if $_SESSION['alert'] has a value and this has not been shown allready
  if(isset($_SESSION['alert']) && isset($_SESSION['showalert']) && $_SESSION['showalert'] == 'true') {
    $_SESSION['showalert'] = 'false';
    echo '
    <div class="alert alert-warning alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    ';
    echo $_SESSION['alert'];
    echo '
    </div>
    ';
  }
  ?>

  <!-- The buttons for "New" and "Go To" -->
  <ul class="nav nav-pills">
    <li role="presentation">

      <div class="dropdown">
        <button class="btn btn-default" id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          New
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dLabel">
          <li><a href="edit_sensor.php">Sensor</a></li>
          <li><a href="edit_sensor_unit.php">Sensor unit</a></li>
          <li><a href="edit_control_system.php">Control system</a></li>
          <li><a href="edit_deep_system.php">Deep system</a></li>
          <li><a href="edit_scu.php">SCU</a></li>
          <li><a href="edit_laser.php">Laser</a></li>
          <li><a href="edit_hv_card.php">HV card</a></li>
          <li><a href="edit_receiver.php">Receiver</a></li>
          <li><a href="edit_leica.php">Leica *</a></li>
        </ul>
      </div>

    </li>
    <li role="presentation">

      <div class="dropdown">
        <button class="btn btn-default" id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Go To
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dLabel">
          <li><a href="#headingSensor">Sensor</a></li>
          <li><a href="#headingSensorUnit">Sensor unit</a></li>
          <li><a href="#headingControlSystem">Control system</a></li>
          <li><a href="#headingDeepSystem">Deep system</a></li>
          <li><a href="#headingSCU">SCU</a></li>
          <li><a href="#headingLaser">Laser</a></li>
          <li><a href="#headingHVCard">HV card</a></li>
          <li><a href="#headingReceiver">Receiver</a></li>
          <li><a href="#headingLeica">Leica</a></li>
        </ul>
      </div>

    </li>
    <li role="presentation">
      <button class="btn btn-default" role="button" id="hideAll"><span class="glyphicon glyphicon-triangle-top" aria-hidden="true"></span></button>
    </li>
    <li role="presentation">
      <button class="btn btn-default" role="button" id="showAll"><span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span></button>
    </li>

  </ul>
  <script>
  // helper script for opening or closing all tables
  $("#hideAll").click(function(){
    $('.collapse').collapse('hide')
  });
  $("#showAll").click(function(){
    $('.collapse').collapse('show')
  });
  </script>

</section>
<section class="parts">

  <div class="row">
    <div class="col-xs-12">
      <!--###### Sensors Table ######-->
      <div role="tab" id="headingSensor">
        <h4>
          <a role="button" data-toggle="collapse" href="#collapseSensor" aria-expanded="true" aria-controls="collapseSensor">
            Sensors (Topo, Shallow, Deep)
          </a>
        </h4>
      </div>
      <div id="collapseSensor" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSensor">

        <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Serial nr.</th>
              <th>Parent sys.</th>
              <th>Config</th>
              <th>CAT</th>
              <th>FPGA id</th>
              <th>Laser</th>
              <th>HV card</th>
              <th>Receiver</th>
              <th>HV card 2</th>
              <th>Receiver 2</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>

            <?php
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }
            $sql = "  SELECT *
            FROM sensor
            ORDER BY datetime DESC";
            $result = $conn->query($sql);
            if (!$result) {
              echo $sql . "<br><br>" . $conn->error;
              die("Query failed!");
            }

            // %s will be replaced with variables later

            // <li><a href="delete.php?type=sensor&serial_nr=%1$s" onclick="return confirm(\'Are you sure that you want to delete this sensor: %1$s\'); ">Delete</a></li>

            $table_row_formating = '
            <tr>

            <td>
            <div class="btn-group">
            <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            %1$s <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
            <li><a href="view_sensor.php?serial_nr=%1$s">View</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="edit_sensor.php?serial_nr=%1$s">Edit</a></li>
            %12$s
            </ul>
            </div>
            </td>
            <td><a href="view_system.php?serial_nr=%11$s">%11$s</a> %13$s</td>
            <td>%2$s</td>
            <td>%3$s</td>
            <td>%4$s</td>
            <td>%5$s</td>
            <td>%6$s</td>
            <td>%7$s</td>
            <td>%8$s</td>
            <td>%9$s</td>
            <td>%10$s</td>
            </tr>
            ';

            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {

                // Pick out parents

                $parent_unit_sql = "";
                $parent_unit = null;
                if($row['sensor_type'] == 'deep') {
                  $parent_unit_sql = "SELECT serial_nr FROM deep_system WHERE deep_sensor_sn = '$row[serial_nr]' LIMIT 1;";
                }
                else if($row['sensor_type'] == 'shallow'){
                  $parent_unit_sql = "SELECT serial_nr FROM sensor_unit WHERE shallow_sensor_sn = '$row[serial_nr]' LIMIT 1;";
                }
                else if($row['sensor_type'] == 'topo'){
                  $parent_unit_sql = "SELECT serial_nr FROM sensor_unit WHERE (topo_sensor_sn = '$row[serial_nr]' OR topo_sensor_2_sn = '$row[serial_nr]') LIMIT 1;";
                }

                $parent_unit_result = $conn->query($parent_unit_sql);
                if (!$parent_unit_result) {
                  echo "Error: " . $parent_unit_sql . "<br>" . $conn->error;
                  die();
                }
                $parent_unit = $parent_unit_result->fetch_array(MYSQLI_ASSOC);

                $parent_system = null;
                if(!empty($parent_unit['serial_nr'])) {
                  if($row['sensor_type'] == 'deep') {
                    $parent_system_sql = "SELECT serial_nr FROM system WHERE deep_system_sn = '$parent_unit[serial_nr]' LIMIT 1;";
                  }
                  else{
                    $parent_system_sql = "SELECT serial_nr FROM system WHERE sensor_unit_sn = '$parent_unit[serial_nr]' LIMIT 1;";
                  }
                  $parent_system_result = $conn->query($parent_system_sql);
                  if (!$parent_system_result) {
                    echo "Error: " . $parent_system_sql . "<br>" . $conn->error;
                    die();
                  }
                  $parent_system = $parent_system_result->fetch_array(MYSQLI_ASSOC);
                }

                $eject = '';
                $table = 'sensor_unit';
                $column = 'topo_sensor_sn';
                $column2 = 'shallow_sensor_sn';
                if($row['sensor_type'] == 'deep'){
                  $table = 'deep_system';
                  $column = 'deep_sensor_sn';
                  $column2 = '';
                }
                if(!empty($parent_unit['serial_nr'])) {
                  $eject = '<button type="button" class="btn btn-default btn-sm hidden-print" data-toggle="modal" data-target="#confirmModal" data-form_serial_nr="' . $row["serial_nr"] . '" data-form_table="' . $table . '" data-form_column="' . $column . '" data-form_column2="' . $column2 . '"><span class="glyphicon glyphicon-eject" aria-hidden="true"></span></button>';
                }

                $parent_type = $row['sensor_type'] == 'deep' ? "deep_system" : "sensor_unit";
                $formated_parent = '';
                if(!empty($parent_unit["serial_nr"])) {$formated_parent .= '<li><a href="edit_' . $parent_type . '.php?serial_nr=' . $parent_unit["serial_nr"] . '">Direct Parent (' . $parent_unit["serial_nr"] . ')</a></li>';}
                if(!empty($parent_system["serial_nr"])) {$formated_parent .= '<li><a href="view_system.php?serial_nr=' . $parent_system["serial_nr"] . '">Parent System(' . $parent_system["serial_nr"] . ')</a></li>';}

                echo sprintf($table_row_formating,
                $row["serial_nr"],
                $row["sensor_type"],
                $row["cat"],
                $row["fpga_id"],
                $row["laser_sn"],
                $row["hv_card_sn"],
                $row["receiver_unit_sn"],
                $row["hv_card_2_sn"],
                $row["receiver_unit_2_sn"],
                $row["status"],
                empty($parent_system['serial_nr']) ? "" : $parent_system['serial_nr'],
                $formated_parent,
                $eject);
              }
            } else {
              echo "No rows";
            }
            ?>

          </tbody>
        </table>
        </div>
      </div>

    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <!--###### Sensor Unit Table ######-->

      <div  role="tab" id="headingSensorUnit">
        <h4>
          <a role="button" data-toggle="collapse" href="#collapseSensorUnit" aria-expanded="true" aria-controls="collapseSensorUnit">
            Sensor Units
          </a>
        </h4>
      </div>
      <div id="collapseSensorUnit" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSensorUnit">
        <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Serial nr.</th>
              <th>Parent sys.</th>
              <th>IMU</th>
              <th>Leica camera</th>
              <th>Leica lens</th>
              <th>Topo</th>
              <th>Shallow</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

            <?php
            $sql = "  SELECT *, sensor_unit.serial_nr AS serial_nr
            FROM sensor_unit
            LEFT JOIN leica ON leica_cam_sn = leica.serial_nr
            ORDER BY sensor_unit.datetime DESC";
            $result = $conn->query($sql);
            if (!$result) {
              echo $sql . "<br><br>" . $conn->error;
              die("Query failed!");
            }

            // %s will be replaced with variables later

            $table_row_formating = '
            <tr>
            <td><a href="edit_sensor_unit.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
            <td><a href="view_system.php?serial_nr=%2$s">%2$s</a> %12$s</td>
            <td>%3$s</td>
            <td>
              <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="Firmware: %4$s">
                <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> %5$s
              </button>
            </td>
            <td><a href="edit_leica.php?serial_nr=%6$s">%6$s</a></td>
            <td>
              <a href="view_sensor.php?serial_nr=%7$s">%7$s</a>
              %11$s
              <a href="view_sensor.php?serial_nr=%8$s">%8$s</a>
            </td>
            <td>
              <a href="view_sensor.php?serial_nr=%9$s">%9$s</a></td>
            </td>
            <td>
              <button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="%10$s">
                <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
              </button>
            </td>
            </tr>
            ';

            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {

                $eject = '';
                $table = 'system';
                $column = 'sensor_unit_sn';
                $column2 = '';
                // Pick out parent
                $parent_sql = "  SELECT serial_nr
                FROM system
                WHERE sensor_unit_sn = '$row[serial_nr]'
                LIMIT 1;";

                $parent = null;
                $parent_result = $conn->query($parent_sql);
                if (!$parent_result) {
                  echo "Error: " . $sql . "<br>" . $conn->error;
                  die();
                }
                $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

                if($parent["serial_nr"] != '') {

                  $eject = '<button type="button" class="btn btn-default btn-sm hidden-print" data-toggle="modal" data-target="#confirmModal" data-form_serial_nr="' . $row["serial_nr"] . '" data-form_table="' . $table . '" data-form_column="' . $column . '" data-form_column2="' . $column2 . '"><span class="glyphicon glyphicon-eject" aria-hidden="true"></span></button>';
                }

                echo sprintf($table_row_formating,
                $row["serial_nr"],
                $parent["serial_nr"],
                $row["imu"],
                $row["firmware"],
                $row["leica_cam_sn"],
                $row["leica_lens"],
                $row["topo_sensor_sn"],
                $row["topo_sensor_2_sn"],
                $row["shallow_sensor_sn"],
                $row["comment"],
                empty($row["topo_sensor_2_sn"]) ? "" : " ,",
                $eject);
              }
            } else {
              echo "No rows";
            }
            ?>

          </tbody>
        </table>
        </div>
      </div>


    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <!--###### Control System Table ######-->

      <div  role="tab" id="headingControlSystem">
        <h4>
          <a role="button" data-toggle="collapse" href="#collapseControlSystem" aria-expanded="true" aria-controls="collapseControlSystem">
            Control Systems
          </a>
        </h4>
      </div>
      <div id="collapseControlSystem" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingControlSystem">

        <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Serial nr.</th>
              <th>Parent sys.</th>
              <th>Battery</th>
              <th>CC32</th>
              <th>PDU</th>
              <th>SCU</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

            <?php
            $sql = "  SELECT *, control_system.serial_nr AS serial_nr
            FROM control_system
            LEFT JOIN leica ON cc32_sn = leica.serial_nr
            ORDER BY control_system.datetime DESC";
            $result = $conn->query($sql);
            if (!$result) {
              echo $sql . "<br><br>" . $conn->error;
              die("Query failed!");
            }

            // %s will be replaced with variables later

            $table_row_formating = '
            <tr>
            <td><a href="edit_control_system.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
            <td><a href="view_system.php?serial_nr=%2$s">%2$s</a> %9$s</td>
            <td>%3$s</td>
            <td><button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="Firmware: %8$s">
            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span> %4$s</button>
            </td>
            <td>%5$s</td>
            <td>%6$s</td>
            <td><button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="%7$s">
            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
            </td>
            </tr>
            ';

            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {

                $eject = '';
                $table = 'system';
                $column = 'control_system_sn';
                $column2 = '';
                // Pick out parent
                $parent_sql = "  SELECT serial_nr
                FROM system
                WHERE control_system_sn = '$row[serial_nr]'
                LIMIT 1;";

                $parent = null;
                $parent_result = $conn->query($parent_sql);
                if (!$parent_result) {
                  echo "Error: " . $sql . "<br>" . $conn->error;
                  die();
                }
                $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

                if($parent["serial_nr"] != '') {

                  $eject = '<button type="button" class="btn btn-default btn-sm hidden-print" data-toggle="modal" data-target="#confirmModal" data-form_serial_nr="' . $row["serial_nr"] . '" data-form_table="' . $table . '" data-form_column="' . $column . '" data-form_column2="' . $column2 . '"><span class="glyphicon glyphicon-eject" aria-hidden="true"></span></button>';
                }

                echo sprintf($table_row_formating,
                $row["serial_nr"],
                $parent["serial_nr"],
                $row["battery"],
                $row["cc32_sn"],
                $row["pdu"],
                $row["scu_sn"],
                $row["comment"],
                $row["firmware"],
                $eject);
              }
            } else {
              echo "No rows";
            }
            ?>

          </tbody>
        </table>
        </div>
      </div>

    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <!--###### Deep System Table ######-->

      <div  role="tab" id="headingDeepSystem">
        <h4>
          <a role="button" data-toggle="collapse" href="#collapseDeepSystem" aria-expanded="true" aria-controls="collapseDeepSystem">
            Deep Systems
          </a>
        </h4>
      </div>
      <div id="collapseDeepSystem" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingDeepSystem">

        <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Serial nr.</th>
              <th>Parent sys.</th>
              <th>Cooling sys.</th>
              <th>IMU</th>
              <th>Pro pack</th>
              <th>Deep</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

            <?php
            $sql = "  SELECT *
            FROM deep_system
            ORDER BY datetime DESC";
            $result = $conn->query($sql);
            if (!$result) {
              echo $sql . "<br><br>" . $conn->error;
              die("Query failed!");
            }

            // %s will be replaced with variables later

            $table_row_formating = '
            <tr>
            <td><a href="edit_deep_system.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
            <td><a href="view_system.php?serial_nr=%2$s">%2$s</a> %8$s</td>
            <td>%3$s</td>
            <td>%4$s</td>
            <td>%5$s</td>
            <td><a href="view_sensor.php?serial_nr=%6$s" class="btn btn-default btn-sm">%6$s</a></td>
            <td><button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="%7$s">
            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
            </td>
            </tr>
            ';

            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {

                $eject = '';
                $table = 'system';
                $column = 'deep_system_sn';
                $column2 = '';
                // Pick out parent
                $parent_sql = "  SELECT serial_nr
                FROM system
                WHERE deep_system_sn = '$row[serial_nr]'
                LIMIT 1;";

                $parent = null;
                $parent_result = $conn->query($parent_sql);
                if (!$parent_result) {
                  echo "Error: " . $sql . "<br>" . $conn->error;
                  die();
                }
                $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

                if($parent["serial_nr"] != '') {

                  $eject = '<button type="button" class="btn btn-default btn-sm hidden-print" data-toggle="modal" data-target="#confirmModal" data-form_serial_nr="' . $row["serial_nr"] . '" data-form_table="' . $table . '" data-form_column="' . $column . '" data-form_column2="' . $column2 . '"><span class="glyphicon glyphicon-eject" aria-hidden="true"></span></button>';
                }

                echo sprintf($table_row_formating,
                $row["serial_nr"],
                $parent["serial_nr"],
                $row["cooling_system"],
                $row["imu"],
                $row["pro_pack"],
                $row["deep_sensor_sn"],
                $row["comment"],
                $eject);
              }
            } else {
              echo "No rows";
            }
            ?>

          </tbody>
        </table>
        </div>
      </div>

    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <!--###### SCU Table ######-->

      <div  role="tab" id="headingSCU">
        <h4>
          <a role="button" data-toggle="collapse" href="#collapseSCU" aria-expanded="true" aria-controls="collapseSCU">
            SCU
          </a>
        </h4>
      </div>
      <div id="collapseSCU" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSCU">

        <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Serial nr.</th>
              <th>Parent ctr-sys.</th>
              <th>Config</th>
              <th>Digitaizer1</th>
              <th>Digitaizer2</th>
              <th>SAT</th>
              <th>CPU</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

            <?php
            $sql = "  SELECT *
            FROM scu
            ORDER BY datetime DESC";
            $result = $conn->query($sql);
            if (!$result) {
              echo $sql . "<br><br>" . $conn->error;
              die("Query failed!");
            }

            // %s will be replaced with variables later

            $table_row_formating = '
            <tr>
            <td><a href="edit_scu.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
            <td><a href="edit_control_system.php?serial_nr=%2$s">%2$s</a> %10$s</td>
            <td>%3$s</td>
            <td>%4$s</td>
            <td>%5$s</td>
            <td>%6$s</td>
            <td>%7$s</td>
            <td>%8$s</td>
            <td><button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="%8$s">
            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
            </td>
            </tr>
            ';

            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {

                $eject = '';
                $table = 'control_system';
                $column = 'scu_sn';
                $column2 = '';
                // Pick out parent
                $parent_sql = "  SELECT serial_nr FROM system
                WHERE control_system_sn = (SELECT serial_nr FROM control_system WHERE scu_sn = '$row[serial_nr]')
                LIMIT 1;";

                $parent = null;
                $parent_result = $conn->query($parent_sql);
                if (!$parent_result) {
                  echo "Error: " . $sql . "<br>" . $conn->error;
                  die();
                }
                $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

                if($parent["serial_nr"] != '') {

                  $eject = '<button type="button" class="btn btn-default btn-sm hidden-print" data-toggle="modal" data-target="#confirmModal" data-form_serial_nr="' . $row["serial_nr"] . '" data-form_table="' . $table . '" data-form_column="' . $column . '" data-form_column2="' . $column2 . '"><span class="glyphicon glyphicon-eject" aria-hidden="true"></span></button>';
                }

                echo sprintf($table_row_formating,
                $row["serial_nr"],
                $parent["serial_nr"],
                $row["configuration"],
                $row["digitaizer1"],
                $row["digitaizer2"],
                $row["sat"],
                $row["cpu"],
                $row["status"],
                $row["comment"],
                $eject);
              }
            } else {
              echo "No rows";
            }
            ?>

          </tbody>
        </table>
        </div>
      </div>

    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <!--###### HV Table ######-->

      <div  role="tab" id="headingHVCard">
        <h4>
          <a role="button" data-toggle="collapse" href="#collapseHVCard" aria-expanded="true" aria-controls="collapseHVCard">
            HV Card
          </a>
        </h4>
      </div>
      <div id="collapseHVCard" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingHVCard">

        <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Serial nr.</th>
              <th>Article nr.</th>
              <th>Parent sens.</th>
              <th>K</th>
              <th>M</th>
            </tr>
          </thead>
          <tbody>

            <?php
            $sql = "  SELECT *
            FROM hv_card
            ORDER BY datetime DESC";
            $result = $conn->query($sql);
            if (!$result) {
              echo $sql . "<br><br>" . $conn->error;
              die("Query failed!");
            }

            // %s will be replaced with variables later

            $table_row_formating = '
            <tr>
            <td><a href="edit_hv_card.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
            <td>%2$s</td>
            <td><a href="view_sensor.php?serial_nr=%3$s" class="btn btn-default btn-sm">%3$s</a> %6$s</td>
            <td>%4$s</td>
            <td>%5$s</td>
            </tr>
            ';

            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {

                $eject = '';
                $table = 'sensor';
                $column = 'hv_card_sn';
                $column2 = 'hv_card_2_sn';
                // Pick out parent
                $parent_sql = "  SELECT serial_nr
                FROM sensor
                WHERE (hv_card_sn = '$row[serial_nr]' OR hv_card_2_sn = '$row[serial_nr]')
                LIMIT 1;";

                $parent = null;
                $parent_result = $conn->query($parent_sql);
                if (!$parent_result) {
                  echo "Error: " . $sql . "<br>" . $conn->error;
                  die();
                }
                $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

                if($parent["serial_nr"] != '') {

                  $eject = '<button type="button" class="btn btn-default btn-sm hidden-print" data-toggle="modal" data-target="#confirmModal" data-form_serial_nr="' . $row["serial_nr"] . '" data-form_table="' . $table . '" data-form_column="' . $column . '" data-form_column2="' . $column2 . '"><span class="glyphicon glyphicon-eject" aria-hidden="true"></span></button>';
                }

                echo sprintf($table_row_formating,
                $row["serial_nr"],
                $row["art_nr"],
                $parent["serial_nr"],
                $row["k_value"],
                $row["m_value"],
                $eject);
              }
            } else {
              echo "No rows";
            }
            ?>

          </tbody>
        </table>
        </div>
      </div>

    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <!--###### Laser Table ######-->

      <div  role="tab" id="headingLaser">
        <h4>
          <a role="button" data-toggle="collapse" href="#collapseLaser" aria-expanded="true" aria-controls="collapseLaser">
            Laser
          </a>
        </h4>
      </div>
      <div id="collapseLaser" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingLaser">

        <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th colspan=2>Serial nr.</th>
              <th colspan=2>Parent sens.</th>
              <th>0</th>
              <th>5</th>
              <th>10</th>
              <th>15</th>
              <th>20</th>
              <th>25</th>
              <th>30</th>
              <th>40</th>
              <th>50</th>
              <th>60</th>
              <th>70</th>
              <th>80</th>
              <th>90</th>
              <th>100</th>
            </tr>
          </thead>
          <tbody>

            <?php
            $sql = "  SELECT *
            FROM laser
            ORDER BY datetime DESC";
            $result = $conn->query($sql);
            if (!$result) {
              echo $sql . "<br><br>" . $conn->error;
              die("Query failed!");
            }

            // %s will be replaced with variables later

            $table_row_formating = '
            <tr>
            <td colspan=2><a href="edit_laser.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
            <td colspan=2><a href="view_sensor.php?serial_nr=%2$s" class="btn btn-default btn-sm">%2$s</a> %17$s</td>
            <td>%3$s</td>
            <td>%4$s</td>
            <td>%5$s</td>
            <td>%6$s</td>
            <td>%7$s</td>
            <td>%8$s</td>
            <td>%9$s</td>
            <td>%10$s</td>
            <td>%11$s</td>
            <td>%12$s</td>
            <td>%13$s</td>
            <td>%14$s</td>
            <td>%15$s</td>
            <td>%16$s</td>
            </tr>
            ';

            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {

                $eject = '';
                $table = 'sensor';
                $column = 'laser_sn';
                $column2 = '';
                // Pick out parent
                $parent_sql = "  SELECT serial_nr
                FROM sensor
                WHERE laser_sn = '$row[serial_nr]'
                LIMIT 1;";

                $parent = null;
                $parent_result = $conn->query($parent_sql);
                if (!$parent_result) {
                  echo "Error: " . $sql . "<br>" . $conn->error;
                  die();
                }
                $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

                if($parent["serial_nr"] != '') {

                  $eject = '<button type="button" class="btn btn-default btn-sm hidden-print" data-toggle="modal" data-target="#confirmModal" data-form_serial_nr="' . $row["serial_nr"] . '" data-form_table="' . $table . '" data-form_column="' . $column . '" data-form_column2="' . $column2 . '"><span class="glyphicon glyphicon-eject" aria-hidden="true"></span></button>';
                }

                echo sprintf($table_row_formating,
                $row["serial_nr"],
                $parent["serial_nr"],
                $row["v_0"],
                $row["v_5"],
                $row["v_10"],
                $row["v_15"],
                $row["v_20"],
                $row["v_25"],
                $row["v_30"],
                $row["v_40"],
                $row["v_50"],
                $row["v_60"],
                $row["v_70"],
                $row["v_80"],
                $row["v_90"],
                $row["v_100"],
                $eject);
              }
            } else {
              echo "No rows";
            }
            ?>

          </tbody>
        </table>
        </div>
      </div>

    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <!--###### Receiver Chip Table ######-->

      <div  role="tab" id="headingReceiver">
        <h4>
          <a role="button" data-toggle="collapse" href="#collapseReceiver" aria-expanded="true" aria-controls="collapseReceiver">
            Receiver
          </a>
        </h4>
      </div>
      <div id="collapseReceiver" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingReceiver">

        <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Serial nr.</th>
              <th>Article nr.</th>
              <th>Parent sens.</th>
              <th>Chip</th>
              <th>Breakdown V</th>
              <th>Operating V</th>
            </tr>
          </thead>
          <tbody>

            <?php
            $sql = "  SELECT *, receiver_chip.serial_nr AS receiver_chip_serial_nr, receiver_unit.serial_nr AS receiver_unit_serial_nr
            FROM receiver_unit
            LEFT JOIN receiver_chip ON receiver_unit.receiver_chip_sn = receiver_chip.serial_nr
            ORDER BY receiver_unit.datetime DESC";
            $result = $conn->query($sql);
            if (!$result) {
              echo $sql . "<br><br>" . $conn->error;
              die("Query failed!");
            }

            // %s will be replaced with variables later

            $table_row_formating = '
            <tr>
            <td><a href="edit_receiver.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
            <td>%2$s</td>
            <td><a href="view_sensor.php?serial_nr=%3$s">%3$s</a> %7$s</td>
            <td>%4$s</td>
            <td>%5$s</td>
            <td>%6$s</td>
            </tr>
            ';

            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {


                $eject = '';
                $table = 'sensor';
                $column = 'receiver_unit_sn';
                $column2 = 'receiver_unit_2_sn';
                // Pick out parent
                $parent_sql = "  SELECT serial_nr
                FROM sensor
                WHERE (receiver_unit_sn = '$row[receiver_unit_serial_nr]' OR receiver_unit_2_sn = '$row[receiver_unit_serial_nr]')
                LIMIT 1;";

                $parent = null;
                $parent_result = $conn->query($parent_sql);
                if (!$parent_result) {
                  echo "Error: " . $sql . "<br>" . $conn->error;
                  die();
                }
                $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

                if($parent["serial_nr"] != '') {

                  $eject = '<button type="button" class="btn btn-default btn-sm hidden-print" data-toggle="modal" data-target="#confirmModal" data-form_serial_nr="' . $row["receiver_unit_serial_nr"] . '" data-form_table="' . $table . '" data-form_column="' . $column . '" data-form_column2="' . $column2 . '"><span class="glyphicon glyphicon-eject" aria-hidden="true"></span></button>';
                }

                echo sprintf($table_row_formating,
                $row["receiver_unit_serial_nr"],
                $row["art_nr"],
                $parent["serial_nr"],
                $row["receiver_chip_sn"],
                $row["breakdown_voltage"],
                $row["operating_voltage"],
                $eject);
              }
            } else {
              echo "No rows";
            }
            ?>
          </tbody>
        </table>
        </div>
      </div>

    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <!--###### Leica Table ######-->

      <div  role="tab" id="headingLeica">
        <h4>
          <a role="button" data-toggle="collapse" href="#collapseLeica" aria-expanded="true" aria-controls="collapseLeica">
            Leica
          </a>
        </h4>
      </div>
      <div id="collapseLeica" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingLeica">

        <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Serial nr.</th>
              <th>Parent sys.</th>
              <th>Type</th>
              <th>Firmware</th>
            </tr>
          </thead>
          <tbody>

            <?php
            $sql = "  SELECT *
            FROM leica
            ORDER BY datetime DESC";
            $result = $conn->query($sql);
            if (!$result) {
              echo $sql . "<br><br>" . $conn->error;
              die("Query failed!");
            }

            // %s will be replaced with variables later

            $table_row_formating = '
            <tr>
            <td><a href="edit_leica.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
            <td><a href="view_system.php?serial_nr=%2$s">%2$s</a> %5$s</td>
            <td>%3$s</td>
            <td>%4$s</td>
            </tr>
            ';

            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {

                $eject = '';
                $table = '';
                $column = '';
                $column2 = '';


                // Pick out parent
                //OC60
                if($row["type"] == 'OC60'){
                  $table = 'system';
                  $column = 'oc60_1_sn';
                  $column2 = 'oc60_2_sn';
                  $parent_sql = "  SELECT serial_nr FROM system
                  WHERE (oc60_1_sn = '$row[serial_nr]' OR oc60_2_sn = '$row[serial_nr]')
                  LIMIT 1;";
                }

                //PAV
                if($row["type"] == 'PAV') {
                  $table = 'system';
                  $column = 'pav_sn';
                  $parent_sql = "  SELECT serial_nr FROM system
                  WHERE $column = '$row[serial_nr]'
                  LIMIT 1;";
                }

                //Pilote Monitor
                if($row["type"] == 'Pilote Monitor') {
                  $table = 'system';
                  $column = 'pd60';
                  $parent_sql = "  SELECT serial_nr FROM system
                  WHERE $column = '$row[serial_nr]'
                  LIMIT 1;";
                }
                
                //Leica Camera
                if($row["type"] == 'Camera'){
                  $table = 'sensor_unit';
                  $column = 'leica_cam_sn';
                  $parent_sql = "  SELECT serial_nr FROM system
                  WHERE sensor_unit_sn = (SELECT serial_nr FROM sensor_unit WHERE $column = '$row[serial_nr]')
                  LIMIT 1;";
                }

                //Leica Camera Lens
                if($row["type"] == 'Leica Lens'){
                  $table = 'sensor_unit';
                  $column = 'leica_lens';
                  $parent_sql = "  SELECT serial_nr FROM system
                  WHERE sensor_unit_sn = (SELECT serial_nr FROM sensor_unit WHERE $column = '$row[serial_nr]')
                  LIMIT 1;";
                }

                //IMU (in the sensor_unit - Chiroptera system)
                if($row["type"] == 'IMU'){
                  $table = 'sensor_unit';
                  $column = 'imu';
                  $parent_sql = "  SELECT serial_nr FROM system
                  WHERE sensor_unit_sn = (SELECT serial_nr FROM sensor_unit WHERE $column = '$row[serial_nr]')
                  LIMIT 1;";
                }

                //CC32
                if($row["type"] == 'CC32'){
                  $table = 'control_system';
                  $column = 'cc32_sn';
                  $parent_sql = "  SELECT serial_nr FROM system
                  WHERE control_system_sn = (SELECT serial_nr FROM control_system WHERE $column = '$row[serial_nr]')
                  LIMIT 1;";
                }

                $parent = null;
                $parent_result = $conn->query($parent_sql);
                if (!$parent_result) {
                  echo "Error: " . $sql . "<br>" . $conn->error;
                  die();
                }
                $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

                //IMU: In HEIII there is an extra IMU in the deep_system, do an extra check)
                if(empty($parent["serial_nr"]) && $row["type"] == 'IMU') {

                      $column = 'imu';
                      $parent_sql = "  SELECT serial_nr FROM system
                      WHERE deep_system_sn = (SELECT serial_nr FROM deep_system WHERE $column = '$row[serial_nr]')
                      LIMIT 1;";
                    
                      $parent = null;
                      $parent_result = $conn->query($parent_sql);
                      
                      if (!$parent_result) {
                          echo "Error: " . $sql . "<br>" . $conn->error;
                          die();
                      }    
                      $parent = $parent_result->fetch_array(MYSQLI_ASSOC);
                }
                if(!empty($parent["serial_nr"])) {
                  $eject = '<button type="button" class="btn btn-default btn-sm hidden-print" data-toggle="modal" data-target="#confirmModal" data-form_serial_nr="' . $row["serial_nr"] . '" data-form_table="' . $table . '" data-form_column="' . $column . '" data-form_column2="' . $column2 . '"><span class="glyphicon glyphicon-eject" aria-hidden="true"></span></button>';
                }

                //print out rows
                echo sprintf($table_row_formating,
                $row["serial_nr"],
                $parent["serial_nr"],
                $row["type"],
                $row["firmware"],
                $eject);
              }
            } else {
              echo "No rows";
            }
            ?>

          </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>


    </section>
    <footer style="height:64px;">

    </footer>

    <div class="fixed-footer">
      <a href="#headingSensor">Sensor</a>
      <a href="#headingSensorUnit">Sensor unit</a>
      <a href="#headingControlSystem">Control system</a>
      <a href="#headingDeepSystem">Deep system</a>
      <a href="#headingSCU">SCU</a>
      <a href="#headingLaser">Laser</a>
      <a href="#headingHVCard">HV card</a>
      <a href="#headingReceiver">Receiver</a>
      <a href="#headingLeica">Leica</a>
    </div>

    <?php
    $conn->close(); // close connection
    include 'res/footer.inc.php';
    ?>
