<?php
$titel = 'Parts';
include 'res/header.inc.php'; 
?>
<?php
function listWithUnused($type, $serial_nr) {
  $type_sn = $type . '_sn';

// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

  // Add all unused sensor units to the list
  $sql_ = "  SELECT serial_nr
            FROM %s
            WHERE serial_nr NOT IN (
              SELECT system.%s
              FROM system) ";
  $result_ = $conn->query(sprintf($sql_, $type, $type_sn));
  $list_string = '';
  while($row_ = $result_->fetch_assoc()) {
    $list_string = $list_string . '<li><a href="edit_system.php?system=%1$s&sensor_unit_sn=' . $row_["serial_nr"] . '">' . $row_["serial_nr"] . '</a></li>';
  }
$conn->close(); // close connection
  if($list_string != ''){
    $list_string = $list_string . '<li role="separator" class="divider"></li>';
  }
$return_string = '
<td colspan=2>
  <div class="btn-group">
    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      ' .$serial_nr. ' <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
      <li><a href="parts.php">View</a></li>
      <li role="separator" class="divider"></li>
      '
      . $list_string .
      '
      <li><a href="edit_' .$type. '.php?serial_nr=' .$serial_nr. '">Edit</a></li>
      <li><a href="#">Remove</a></li>
    </ul>
  </div>
</td>
';
return $return_string;
}
?>

<section class="top_content">

<?php
if(isset($_GET['alert'])) {
  echo '
    <div class="alert alert-success alert-dismissible fade in" role="alert">
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
      <li><a href="edit_receiver_chip.php">Receiver chip</a></li>
      <li><a href="edit_leica_cam.php">Leica camera (Här?)</a></li>
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
      <li><a href="#headingReceiverChip">Receiver chip</a></li>
      <li><a href="#headingLeicaCam">Leica camera</a></li>
    </ul>
  </div>

  </li>
</ul>

<!--   <a href="edit_sensor.php" class="btn btn-default" role="button">New sensor</a>
  <a href="edit_sensor_unit.php" class="btn btn-default" role="button">New sensor unit</a>
  <a href="edit_control_system.php" class="btn btn-default" role="button">New control system</a>
  <a href="edit_deep_system.php" class="btn btn-default" role="button">New deep system</a>
  <a href="edit_scu.php" class="btn btn-default" role="button">New SCU</a>
  <a href="edit_laser.php" class="btn btn-default" role="button">New laser</a>
  <a href="edit_hv_card.php" class="btn btn-default" role="button">New HV card</a>
  <a href="edit_receiver_chip.php" class="btn btn-default" role="button">New receiver chip</a>
  <a href="edit_leica_cam.php" class="btn btn-default" role="button">New Leica cam (Här?)</a> -->
</section>
<section class="content">

<!--###### Sensor Unit Table ######-->
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingSensorUnit">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" href="#collapseSensorUnit" aria-expanded="true" aria-controls="collapseSensorUnit">
        Sensor Units
      </a>
    </h4>
  </div>
  <div id="collapseSensorUnit" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSensorUnit">
    <div class="panel-body panel_table">
          <table class="table table-striped table-responsive my_table">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>IMU</th>
            <th>Leica camera</th>
            <th>Leica lens</th>
            <th>Topo sensor</th>
            <th>Shallow sensor</th>
          </tr>
        </thead>
        <tbody>

<?php
// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "  SELECT *
          FROM sensor_unit
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
$conn->close(); // close connection

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td><a href="edit_sensor_unit.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
  <td>%2$s</td>
  <td><a href="view_leica_cam.php?serial_nr=%3$s" class="btn btn-default btn-sm">%3$s</a></td>
  <td>%4$s</td>
  <td><a href="view_sensor.php?serial_nr=%5$s" class="btn btn-default btn-sm">%5$s</a></td>
  <td><a href="view_sensor.php?serial_nr=%6$s" class="btn btn-default btn-sm">%6$s</a></td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $row["imu"],
          $row["leica_cam_sn"],
          $row["leica_lens"],
          $row["topo_sensor_sn"],
          $row["shallow_sensor_sn"]);
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


<!--###### Control System Table ######-->
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingControlSystem">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" href="#collapseControlSystem" aria-expanded="true" aria-controls="collapseControlSystem">
        Control Systems
      </a>
    </h4>
  </div>
  <div id="collapseControlSystem" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingControlSystem">
    <div class="panel-body panel_table">
          <table class="table table-striped table-responsive my_table">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Battery</th>
            <th>CC32</th>
            <th>PDU</th>
            <th>SCU</th>
          </tr>
        </thead>
        <tbody>

<?php
// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "  SELECT *
          FROM control_system
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
$conn->close(); // close connection

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td><a href="edit_control_system.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
  <td>%2$s</td>
  <td>%3$s</td>
  <td>%4$s</td>
  <td><a href="view_scu.php?serial_nr=%5$s" class="btn btn-default btn-sm">%5$s</a></td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $row["battery"],
          $row["cc32"],
          $row["pdu"],
          $row["scu_sn"]);
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

<!--###### Deep System Table ######-->
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingDeepSystem">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" href="#collapseDeepSystem" aria-expanded="true" aria-controls="collapseDeepSystem">
        Deep Systems
      </a>
    </h4>
  </div>
  <div id="collapseDeepSystem" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingDeepSystem">
    <div class="panel-body panel_table">
          <table class="table table-striped table-responsive my_table">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Cooling system</th>
            <th>IMU</th>
            <th>Pro pack</th>
            <th>Deep sensor</th>
          </tr>
        </thead>
        <tbody>

<?php
// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "  SELECT *
          FROM deep_system
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
$conn->close(); // close connection

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td><a href="edit_deep_system.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
  <td>%2$s</td>
  <td>%3$s</td>
  <td>%4$s</td>
  <td><a href="view_sensor.php?serial_nr=%5$s" class="btn btn-default btn-sm">%5$s</a></td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $row["cooling_system"],
          $row["imu"],
          $row["pro_pack"],
          $row["deep_sensor_sn"]);
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

<!--###### Sensors Table ######-->
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingSensor">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" href="#collapseSensor" aria-expanded="true" aria-controls="collapseSensor">
        Sensors (Topo, Shallow, Deep)
      </a>
    </h4>
  </div>
  <div id="collapseSensor" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSensor">
    <div class="panel-body panel_table">
          <table class="table table-striped table-responsive my_table">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Config</th>
            <th>CAT</th>
            <th>FPGA id</th>
            <th>Laser</th>
            <th>HV card</th>
            <th>Receiver unit</th>
            <th>Receiver chip</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>

<?php
// Create connection
include 'res/config.inc.php';
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
$conn->close(); // close connection

// %s will be replaced with variables later

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
        <li><a href="delete.php?type=sensor&serial_nr=%1$s" onclick="return confirm(\'Are you sure that you want to delete this sensor: %1$s\'); ">Delete</a></li>
      </ul>
    </div>
  </td>

  <td>%2$s</td>
  <td>%3$s</td>
  <td>%4$s</td>
  <td><a href="view_laser.php?serial_nr=%5$s" class="btn btn-default btn-sm">%5$s</a></td>
  <td><a href="view_hv.php?serial_nr=%6$s" class="btn btn-default btn-sm">%6$s</a></td>
  <td>%7$s</td>
  <td><a href="view_receiver_chip.php?serial_nr=%8$s" class="btn btn-default btn-sm">%8$s</a></td>
  <td>%9$s</td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $row["sensor_type"],
          $row["cat"],
          $row["fpga_id"],
          $row["laser_sn"],
          $row["hv_card_sn"],
          $row["receiver_unit"],
          $row["receiver_chip_sn"],
          $row["status"]);
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

<!--###### SCU Table ######-->
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingSCU">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" href="#collapseSCU" aria-expanded="true" aria-controls="collapseSCU">
        Sensor Control Unit (SCU)
      </a>
    </h4>
  </div>
  <div id="collapseSCU" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSCU">
    <div class="panel-body panel_table">
          <table class="table table-striped table-responsive my_table">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Config</th>
            <th>Digitaizer 1</th>
            <th>Digitaizer 2</th>
            <th>SAT</th>
            <th>CPU</th>
            <th>Version</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>

<?php
// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "  SELECT *
          FROM scu
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
$conn->close(); // close connection

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
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $row["configuration"],
          $row["digitaizer1"],
          $row["digitaizer2"],
          $row["sat"],
          $row["cpu"],
          $row["version"],
          $row["status"]);
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

<!--###### HV Table ######-->
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingHVCard">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" href="#collapseHVCard" aria-expanded="true" aria-controls="collapseHVCard">
        HV Card
      </a>
    </h4>
  </div>
  <div id="collapseHVCard" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingHVCard">
    <div class="panel-body panel_table">
          <table class="table table-striped table-responsive my_table">
        <thead>
          <tr>
            <th colspan=2>Serial nr.</th>
            <th colspan=2>Art. nr.</th>
            <th colspan=2>Config</th>
            <th>K</th>
            <th>M</th>
            <th>0</th>
            <th>500</th>
            <th>1000</th>
            <th>1500</th>
            <th>2000</th>
            <th>2500</th>
            <th>3000</th>
            <th>3250</th>
          </tr>
        </thead>
        <tbody>

<?php
// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "  SELECT *
          FROM hv_card
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
$conn->close(); // close connection

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td colspan=2><a href="edit_hv_card.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
  <td colspan=2>%2$s</td>
  <td colspan=2>%3$s</td>
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
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $row["configuration"],
          $row["art_nr"],
          $row["k_value"],
          $row["m_value"],
          $row["v_0"],
          $row["v_500"],
          $row["v_1000"],
          $row["v_1500"],
          $row["v_2000"],
          $row["v_2500"],
          $row["v_3000"],
          $row["v_3250"]);
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

<!--###### Laser Table ######-->
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingLaser">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" href="#collapseLaser" aria-expanded="true" aria-controls="collapseLaser">
        Laser
      </a>
    </h4>
  </div>
  <div id="collapseLaser" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingLaser">
    <div class="panel-body panel_table">
          <table class="table table-striped table-responsive my_table">
        <thead>
          <tr>
            <th colspan=2>Serial nr.</th>
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
// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "  SELECT *
          FROM laser
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
$conn->close(); // close connection

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td colspan=2><a href="edit_laser.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
  <td>%2$s</td>
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
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        echo sprintf($table_row_formating,
          $row["serial_nr"],
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
  </div>
</div>

<!--###### Receiver Chip Table ######-->
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingReceiverChip">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" href="#collapseReceiverChip" aria-expanded="true" aria-controls="collapseReceiverChip">
        Receiver Chip
      </a>
    </h4>
  </div>
  <div id="collapseReceiverChip" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingReceiverChip">
    <div class="panel-body panel_table">
          <table class="table table-striped table-responsive my_table">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Art. nr.</th>
            <th>Unit</th>
            <th>Firmware</th>
          </tr>
        </thead>
        <tbody>

<?php
// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "  SELECT *
          FROM receiver_chip
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
$conn->close(); // close connection

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td><a href="edit_receiver_chip.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
  <td>%2$s</td>
  <td>%3$s</td>
  <td>%4$s</td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $row["art_nr"],
          $row["unit"],
          $row["firmware"]);
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

<!--###### Leica Camera Table ######-->
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingLeicaCam">
    <h4 class="panel-title">
      <a role="button" data-toggle="collapse" href="#collapseLeicaCam" aria-expanded="true" aria-controls="collapseLeicaCam">
        Leica Cam (Var ska den här visas? Här?)
      </a>
    </h4>
  </div>
  <div id="collapseLeicaCam" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingLeicaCam">
    <div class="panel-body panel_table">
          <table class="table table-striped table-responsive my_table">
        <thead>
          <tr>
            <th>Serial nr.</th>
            <th>Config</th>
            <th>Breakdown</th>
            <th>Operating Voltage</th>
          </tr>
        </thead>
        <tbody>

<?php
// Create connection
include 'res/config.inc.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "  SELECT *
          FROM leica_cam
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
$conn->close(); // close connection

// %s will be replaced with variables later

$table_row_formating = '
<tr>
  <td><a href="edit_leica_cam.php?serial_nr=%1$s" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> %1$s</a></td>
  <td>%2$s</td>
  <td>%3$s</td>
  <td>%4$s</td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

        echo sprintf($table_row_formating,
          $row["serial_nr"],
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
  </div>
</div>

</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>