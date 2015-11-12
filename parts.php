<?php
$titel = 'Parts';
include 'res/header.inc.php'; 
?>
<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>

<section class="content">

<?php
if(isset($_GET['alert'])) {
  echo '
    <div class="alert alert-warning alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  ';
  echo $_GET['alert'];
  echo '
    </div>
  ';
}
?>

<ul class="nav nav-pills">
  <li role="presentation">

  <div class="dropdown">
    <button class="btn btn-default" id="dLabel" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      New Component
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
      <li><a href="edit_leica_cam.php">Leica camera</a></li>
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
      <li><a href="#headingLeicaCam">Leica camera</a></li>
    </ul>
  </div>

  </li>
</ul>

</section>
<section class="parts">

<!--###### Sensors Table ######-->
  <div role="tab" id="headingSensor">
    <h4>
      <a role="button" data-toggle="collapse" href="#collapseSensor" aria-expanded="true" aria-controls="collapseSensor">
        Sensors (Topo, Shallow, Deep)
      </a>
    </h4>
  </div>
  <div id="collapseSensor" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSensor">

      <table class="table table-striped table-responsive table_750">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Parent</th>
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
        <li><a href="view_system.php?system=%12$s">Parent system</a></li>
      </ul>
    </div>
  </td>
  <td>%11$s</td>
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

      // Pick out parent
      $parent_sql = "  SELECT system.serial_nr AS system_serial_nr, deep_system_sn, sensor_unit_sn
                FROM system
                LEFT JOIN deep_system ON system.deep_system_sn = deep_system.serial_nr
                LEFT JOIN sensor_unit ON system.sensor_unit_sn = sensor_unit.serial_nr
                WHERE (sensor_unit.topo_sensor_sn = '$row[serial_nr]' OR sensor_unit.shallow_sensor_sn = '$row[serial_nr]' OR deep_system.deep_sensor_sn = '$row[serial_nr]')
                LIMIT 1;";

      $parent_result = $conn->query($parent_sql);
      if (!$parent_result) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die();
      }
      $parent = $parent_result->fetch_array(MYSQLI_ASSOC);
      $parent_1 = '';
      if($row["sensor_type"] == 'deep'){
          $parent_1 = $parent["deep_system_sn"];
      }else{
          $parent_1 = $parent["sensor_unit_sn"];
      }

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
          $parent_1,
          $parent["system_serial_nr"]);
    }
} else {
    echo "No rows";
}
?>

      </tbody>
    </table>
<!--     </div>
  </div> -->
</div>

<!--###### Sensor Unit Table ######-->

  <div  role="tab" id="headingSensorUnit">
    <h4>
      <a role="button" data-toggle="collapse" href="#collapseSensorUnit" aria-expanded="true" aria-controls="collapseSensorUnit">
        Sensor Units
      </a>
    </h4>
  </div>
  <div id="collapseSensorUnit" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSensorUnit">
    
      <table class="table table-striped table-responsive table_650">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Parent</th>
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
$sql = "  SELECT *
          FROM sensor_unit
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td><a href="edit_sensor_unit.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
  <td><a href="view_system.php?serial_nr=%2$s" class="btn btn-default btn-sm">%2$s</a></td>
  <td>%3$s</td>
  <td>%4$s</td>
  <td>%5$s</td>
  <td><a href="view_sensor.php?serial_nr=%6$s" class="btn btn-default btn-sm">%6$s</a></td>
  <td><a href="view_sensor.php?serial_nr=%7$s" class="btn btn-default btn-sm">%7$s</a></td>
  <td><button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="%8$s">
      <span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
  </td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      // Pick out parent
      $parent_sql = "  SELECT serial_nr
                FROM system
                WHERE sensor_unit_sn = '$row[serial_nr]'
                LIMIT 1;";

      $parent_result = $conn->query($parent_sql);
      if (!$parent_result) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die();
      }
      $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $parent["serial_nr"],
          $row["imu"],
          $row["leica_cam_sn"],
          $row["leica_lens"],
          $row["topo_sensor_sn"],
          $row["shallow_sensor_sn"],
          $row["comment"]);
    }
} else {
    echo "No rows";
}
?>

      </tbody>
    </table>
    </div>



<!--###### Control System Table ######-->

  <div  role="tab" id="headingControlSystem">
    <h4>
      <a role="button" data-toggle="collapse" href="#collapseControlSystem" aria-expanded="true" aria-controls="collapseControlSystem">
        Control Systems
      </a>
    </h4>
  </div>
  <div id="collapseControlSystem" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingControlSystem">
    
      <table class="table table-striped table-responsive table_550">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Parent</th>
            <th>Battery</th>
            <th>CC32</th>
            <th>PDU</th>
            <th>SCU</th>
            <th></th>
          </tr>
        </thead>
        <tbody>

<?php
$sql = "  SELECT *
          FROM control_system
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td><a href="edit_control_system.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
  <td><a href="view_system.php?serial_nr=%2$s" class="btn btn-default btn-sm">%2$s</a></td>
  <td>%3$s</td>
  <td>%4$s</td>
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

      // Pick out parent
      $parent_sql = "  SELECT serial_nr
                FROM system
                WHERE control_system_sn = '$row[serial_nr]'
                LIMIT 1;";

      $parent_result = $conn->query($parent_sql);
      if (!$parent_result) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die();
      }
      $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $parent["serial_nr"],
          $row["battery"],
          $row["cc32"],
          $row["pdu"],
          $row["scu_sn"],
          $row["comment"]);
    }
} else {
    echo "No rows";
}
?>

      </tbody>
    </table>
    </div>


<!--###### Deep System Table ######-->

  <div  role="tab" id="headingDeepSystem">
    <h4>
      <a role="button" data-toggle="collapse" href="#collapseDeepSystem" aria-expanded="true" aria-controls="collapseDeepSystem">
        Deep Systems
      </a>
    </h4>
  </div>
  <div id="collapseDeepSystem" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingDeepSystem">
    
      <table class="table table-striped table-responsive table_600">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Parent</th>
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
  <td><a href="view_system.php?serial_nr=%2$s" class="btn btn-default btn-sm">%2$s</a></td>
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

      // Pick out parent
      $parent_sql = "  SELECT serial_nr
                FROM system
                WHERE deep_system_sn = '$row[serial_nr]'
                LIMIT 1;";

      $parent_result = $conn->query($parent_sql);
      if (!$parent_result) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die();
      }
      $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $parent["serial_nr"],
          $row["cooling_system"],
          $row["imu"],
          $row["pro_pack"],
          $row["deep_sensor_sn"],
          $row["comment"]);
    }
} else {
    echo "No rows";
}
?>

      </tbody>
    </table>
    </div>


<!--###### SCU Table ######-->

  <div  role="tab" id="headingSCU">
    <h4>
      <a role="button" data-toggle="collapse" href="#collapseSCU" aria-expanded="true" aria-controls="collapseSCU">
        SCU
      </a>
    </h4>
  </div>
  <div id="collapseSCU" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSCU">
    
          <table class="table table-striped table-responsive table_700">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Parent</th>
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
  <td>%2$s</td>
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

      // Pick out parent
      $parent_sql = "  SELECT serial_nr
                FROM control_system
                WHERE scu_sn = '$row[serial_nr]'
                LIMIT 1;";

      $parent_result = $conn->query($parent_sql);
      if (!$parent_result) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die();
      }
      $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $parent["serial_nr"],
          $row["configuration"],
          $row["digitaizer1"],
          $row["digitaizer2"],
          $row["sat"],
          $row["cpu"],
          $row["status"],
          $row["comment"]);
    }
} else {
    echo "No rows";
}
?>

      </tbody>
    </table>
    </div>


<!--###### HV Table ######-->

  <div  role="tab" id="headingHVCard">
    <h4>
      <a role="button" data-toggle="collapse" href="#collapseHVCard" aria-expanded="true" aria-controls="collapseHVCard">
        HV Card
      </a>
    </h4>
  </div>
  <div id="collapseHVCard" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingHVCard">
    
      <table class="table table-striped table-responsive table_500">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Article nr.</th>
            <th>Parent</th>
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
  <td><a href="view_sensor.php?serial_nr=%3$s" class="btn btn-default btn-sm">%3$s</a></td>
  <td>%4$s</td>
  <td>%5$s</td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      // Pick out parent
      $parent_sql = "  SELECT serial_nr
                FROM sensor
                WHERE (hv_card_sn = '$row[serial_nr]' OR hv_card_2_sn = '$row[serial_nr]')
                LIMIT 1;";

      $parent_result = $conn->query($parent_sql);
      if (!$parent_result) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die();
      }
      $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $row["art_nr"],
          $parent["serial_nr"],
          $row["k_value"],
          $row["m_value"]);
    }
} else {
    echo "No rows";
}
?>

      </tbody>
    </table>
    </div>


<!--###### Laser Table ######-->

  <div  role="tab" id="headingLaser">
    <h4>
      <a role="button" data-toggle="collapse" href="#collapseLaser" aria-expanded="true" aria-controls="collapseLaser">
        Laser
      </a>
    </h4>
  </div>
  <div id="collapseLaser" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingLaser">
    
      <table class="table table-striped table-responsive table_750">
        <thead>
          <tr>
            <th colspan=2>Serial nr.</th>
            <th colspan=2>Parent</th>
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
  <td colspan=2><a href="view_sensor.php?serial_nr=%2$s" class="btn btn-default btn-sm">%2$s</a></td>
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

      // Pick out parent
      $parent_sql = "  SELECT serial_nr
                FROM sensor
                WHERE laser_sn = '$row[serial_nr]'
                LIMIT 1;";

      $parent_result = $conn->query($parent_sql);
      if (!$parent_result) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die();
      }
      $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

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
          $row["v_100"]);
    }
} else {
    echo "No rows";
}
?>

      </tbody>
    </table>
    </div>


<!--###### Receiver Chip Table ######-->

  <div  role="tab" id="headingReceiver">
    <h4>
      <a role="button" data-toggle="collapse" href="#collapseReceiver" aria-expanded="true" aria-controls="collapseReceiver">
        Receiver
      </a>
    </h4>
  </div>
  <div id="collapseReceiver" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingReceiver">
    
      <table class="table table-striped table-responsive table_600">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Article nr.</th>
            <th>Parent</th>
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
  <td><a href="view_sensor.php?serial_nr=%3$s" class="btn btn-default btn-sm">%3$s</td>
  <td>%4$s</td>
  <td>%5$s</td>
  <td>%6$s</td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      // Pick out parent
      $parent_sql = "  SELECT serial_nr
                FROM sensor
                WHERE receiver_unit_sn = '$row[receiver_unit_serial_nr]'
                LIMIT 1;";

      $parent_result = $conn->query($parent_sql);
      if (!$parent_result) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die();
      }
      $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

        echo sprintf($table_row_formating,
          $row["receiver_unit_serial_nr"],
          $row["art_nr"],
          $parent["serial_nr"],
          $row["receiver_chip_sn"],
          $row["breakdown_voltage"],
          $row["operating_voltage"]);
    }
} else {
    echo "No rows";
}
?>

      </tbody>
    </table>
    </div>


<!--###### Leica Camera Table ######-->

  <div  role="tab" id="headingLeicaCam">
    <h4>
      <a role="button" data-toggle="collapse" href="#collapseLeicaCam" aria-expanded="true" aria-controls="collapseLeicaCam">
        Leica Cam
      </a>
    </h4>
  </div>
  <div id="collapseLeicaCam" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingLeicaCam">

      <table class="table table-striped table-responsive table_500">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Parent</th>
            <th>Config</th>
            <th>Breakdown</th>
            <th>Operating V</th>
          </tr>
        </thead>
        <tbody>

<?php
$sql = "  SELECT *
          FROM leica_cam
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td><a href="edit_leica_cam.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
  <td>%2$s</td>
  <td>%3$s</td>
  <td>%4$s</td>
  <td>%5$s</td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      // Pick out parent
      $parent_sql = "  SELECT serial_nr
                FROM sensor_unit
                WHERE leica_cam_sn = '$row[serial_nr]'
                LIMIT 1;";

      $parent_result = $conn->query($parent_sql);
      if (!$parent_result) {
        echo "Error: " . $sql . "<br>" . $conn->error;
        die();
      }
      $parent = $parent_result->fetch_array(MYSQLI_ASSOC);

        //print out rows
        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $parent["serial_nr"],
          $row["configuration"],
          $row["breakdown"],
          $row["operating_voltage"]);
    }
} else {
    echo "No rows";
}
?>

      </tbody>
    </table>
    </div>


</section>
<footer>

</footer>

<?php 
$conn->close(); // close connection
include 'res/footer.inc.php';
?>