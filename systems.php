<?php
$titel = 'Systems';
include 'res/header.inc.php'; 
?>

<section class="content">
  <a href="edit_system.php" class="btn btn-default" role="button">New system</a>
</section>

<section class="all__systems">
  <table class="large__table table table-striped table-responsive">
    <thead>
      <tr>
        <th colspan=2>Serial nr.</th>
        <th colspan=3>Client</th>
        <th colspan=3>Config.</th>
        <th colspan=2>Sensor unit</th>
        <th colspan=2>Control unit</th>
        <th colspan=2>Deep system</th>
        <th colspan=2>Control system</th>
        <th colspan=2>Topo</th>
        <th colspan=2>Shallow</th>
        <th colspan=2>Deep</th>
        <th colspan=2>SCU</th>
        <th colspan=2>PDU</th>
        <th colspan=2>Status</th>
        <th colspan=5>Comments</th>
      </tr>
    </thead>
    <tbody>

<?php
include 'res/config.inc.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//

$sql = "  SELECT system.serial_nr, client, configuration, sensor_unit_sn, control_unit_sn, deep_system_sn,
          deep_system.control_system, sensor_unit.topo_sensor_sn, sensor_unit.shallow_sensor_sn, deep_system.deep_sensor_sn,
          control_unit.scu_sn, control_unit.pdu, comment,
          status_potta_heat, status_shallow_heat, status_scu_pdu, status_hv_topo, status_hv_shallow, status_hv_deep, status_cat, status_pwr_cable
          FROM system
          LEFT JOIN sensor_unit ON sensor_unit_sn = sensor_unit.serial_nr
          LEFT JOIN control_unit ON control_unit_sn = control_unit.serial_nr
          LEFT JOIN deep_system ON deep_system_sn = deep_system.serial_nr
          ORDER BY datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    die("Query failed!");
}

// %s will be replaced with variables later

$serial_nr_formating = '
  <td colspan=2>
    <div class="btn-group">
      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        %1$s <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="view_system.php?system=%1$s">View</a></li>
        <li role="separator" class="divider"></li>
        <li><a href="edit_system.php?system=%1$s">Edit</a></li>
        <li><a href="delete.php?type=system&serial_nr=%1$s" onclick="return confirm(\'Are you sure that you want to delete this system: %1$s\'); ">Delete</a></li>
      </ul>
    </div>
  </td>
';
$client_formating = '
  <td colspan=3>
    <div class="btn-group">
      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      %2$s <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="edit_system.php?system=%1$s">Edit</a></li>
      </ul>
    </div>
  </td>
';
$config_formating = '
  <td colspan=3>
    <div class="btn-group">
      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      %3$s <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="edit_system.php?system=%1$s&configuration=DualDragon">DualDragon</a></li>
        <li><a href="edit_system.php?system=%1$s&configuration=HawkEyeIII">HawkEyeIII</a></li>
        <li><a href="edit_system.php?system=%1$s&configuration=Chiroptera">Chiroptera</a></li>
      </ul>
    </div>
  </td>
';
// %% escapes the input spot for later
$sensor_unit_formating = '
  <td colspan=2>
    <div class="btn-group">
      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        %%4$s <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="parts.php">View</a></li>
        <li role="separator" class="divider"></li>
        %1$s
        <li><a href="edit_sensor_unit.php?serial_nr=%%4$s">Edit</a></li>
        <li><a href="#">Remove</a></li>
      </ul>
    </div>
  </td>
';
// Add all unused sensor units to the list
$sql_sensor_unit = "  SELECT sensor_unit.serial_nr
          FROM sensor_unit
          WHERE sensor_unit.serial_nr NOT IN (
            SELECT system.sensor_unit_sn 
            FROM system) ";
$result_sensor_unit = $conn->query($sql_sensor_unit);
$add_rows_sensor_unit = '';
while($row_sensor_unit = $result_sensor_unit->fetch_assoc()) {
  $add_rows_sensor_unit = $add_rows_sensor_unit
                          . '<li><a href="edit_system.php?system=%1$s&sensor_unit_sn='
                          . $row_sensor_unit["serial_nr"] 
                          . '">' 
                          . $row_sensor_unit["serial_nr"] 
                          . '</a></li>';
}
if($add_rows_sensor_unit != ''){
  $add_rows_sensor_unit = $add_rows_sensor_unit
                          . '<li role="separator" class="divider"></li>';
}
$sensor_unit_formating = sprintf($sensor_unit_formating, $add_rows_sensor_unit);

$control_unit_formating = '
  <td colspan=2>
    <div class="btn-group">
      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        %5$s <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="parts.php">View</a></li>
        <li role="separator" class="divider"></li>
        <li><a href="edit_control_unit.php?serial_nr=%5$s">Edit</a></li>
        <li><a href="#">Remove</a></li>
      </ul>
    </div>
  </td>
';
$deep_system_formating = '
  <td colspan=2>
    <div class="btn-group">
      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        %6$s <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="parts.php">View</a></li>
        <li role="separator" class="divider"></li>
        <li><a href="edit_deep_system.php?serial_nr=%6$s">Edit</a></li>
        <li><a href="#">Remove</a></li>
      </ul>
    </div>
  </td>
';
$control_system_formating = '
  <td colspan=2>
    <div class="btn-group">
      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        %7$s <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="parts.php">View</a></li>
        <li role="separator" class="divider"></li>
        <li><a href="edit_control_system.php?serial_nr=%7$s">Edit</a></li>
        <li><a href="#">Remove</a></li>
      </ul>
    </div>
  </td>
';
$topo_shallow_deep_formating = '
 <td colspan=2>
      <a href="view_sensor.php?serial_nr=%8$s" class="btn btn-default btn-xs">
        %8$s
      </a>
  </td>

  <td colspan=2>
      <a href="view_sensor.php?serial_nr=%9$s" class="btn btn-default btn-xs">
        %9$s
      </a>
  </td>

  <td colspan=2>
      <a href="view_sensor.php?serial_nr=%10$s" class="btn btn-default btn-xs">
        %10$s
      </a>
  </td>
';
$scu_pdu_formating = '
  <td colspan=2>
      <a href="#" class="btn btn-default btn-xs">
        %11$s
      </a>
  </td>

  <td colspan=2>
    %12$s
  </td>
';
$system_status_formating = '
  <td colspan=2>
    %13$s
  </td>
';
$comment_formating = '
  <td colspan=5>
    %14$s
  </td>
';

$table_row_formating = '<tr>'
                        . $serial_nr_formating
                        . $client_formating
                        . $config_formating
                        . $sensor_unit_formating
                        . $control_unit_formating
                        . $deep_system_formating
                        . $control_system_formating
                        . $topo_shallow_deep_formating
                        . $scu_pdu_formating
                        . $system_status_formating
                        . $comment_formating
                        .'</tr>';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      // shorten too long client names
      $client = $row["client"];
      if (strlen ($client) >= 11) {
        $client = substr($client, 0, 9) . "..";
      }
      // shorten too long comments
      $comment = $row["comment"];
      if (strlen ($comment) >= 18) {
        $comment = substr($comment, 0, 16) . "..";
      }

      // Merge the 8 status options into ready or not
      if(       $row['status_potta_heat'] &&
                $row['status_shallow_heat']&&
                $row['status_scu_pdu']){
        $status = 'Ready';
      }else if( !$row['status_potta_heat'] &&
                !$row['status_shallow_heat']){
        $status = 'Nothing';
      } else {
        $status = 'Some';
      }

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $client,
          $row["configuration"],
          $row["sensor_unit_sn"],
          $row["control_unit_sn"],
          $row["deep_system_sn"],
          $row["control_system"],
          $row["topo_sensor_sn"],
          $row["shallow_sensor_sn"],
          $row["deep_sensor_sn"],
          $row["scu_sn"],
          $row["pdu"],
          $status,
          $comment);
    }
} else {
    echo "No messages";
}
$conn->close();
?>

    </tbody>
  </table>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>