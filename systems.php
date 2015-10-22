<?php
$titel = 'Overview';
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

$sql = "  SELECT system.serial_nr, system.client, system.configuration, system.sensor_unit_sn, system.control_unit_sn, system.deep_system_sn, deep_system.control_system, sensor_unit.topo_sensor_sn, sensor_unit.shallow_sensor_sn, deep_system.deep_sensor_sn, control_unit.scu_sn, control_unit.pdu, system.comment
          FROM system, sensor_unit, control_unit, deep_system 
          WHERE system.sensor_unit_sn = sensor_unit.serial_nr and
                system.control_unit_sn = control_unit.serial_nr and
                system.deep_system_sn = deep_system.serial_nr";
$result = $conn->query($sql);

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
        <li><a href="delete.php?system=%1$s">Delete</a></li>
      </ul>
    </div>
  </td>
';
$client_formating = '
  <td colspan=3>
    <div class="btn-group">
      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      %2$s<span class="caret"></span>
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
      %3$s<span class="caret"></span>
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
        <li><a href="view_system.php?system=s">View</a></li>
        <li role="separator" class="divider"></li>
        %s
        <li><a href="edit_system.php?system=">Edit</a></li>
        <li><a href="delete.php?system=">Remove</a></li>
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
                          . '<li><a href="delete.php?system=">' 
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
        <li><a href="view_system.php?system=%1$s">View</a></li>
        <li><a href="edit_system.php?system=%1$s">Edit</a></li>
        <li><a href="delete.php?system=%1$s">Remove</a></li>
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
        <li><a href="view_system.php?system=%1$s">View</a></li>
        <li><a href="edit_system.php?system=%1$s">Edit</a></li>
        <li><a href="delete.php?system=%1$s">Remove</a></li>
      </ul>
    </div>
  </td>
';
$control_system_formating = '
  <td colspan=2>
    <div class="btn-group">
      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        %6$s <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="view_system.php?system=%1$s">View</a></li>
        <li><a href="edit_system.php?system=%1$s">Edit</a></li>
        <li><a href="delete.php?system=%1$s">Remove</a></li>
      </ul>
    </div>
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
                        . '
 <td colspan=2>
      <a href="#" class="btn btn-default btn-xs">
        %8$s
      </a>
  </td>

  <td colspan=2>
      <a href="#" class="btn btn-default btn-xs">
        %9$s
      </a>
  </td>

  <td colspan=2>
      <a href="#" class="btn btn-default btn-xs">
        %10$s
      </a>
  </td>

  <td colspan=2>
      <a href="#" class="btn btn-default btn-xs">
        %11$s
      </a>
  </td>

  <td colspan=2>
    %12$s
  </td>

  <td colspan=2>
    Ready
  </td>

  <td colspan=5>
    %13$s
  </td>
</tr>
';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      // shorten too long client names
      $client = $row["client"];
      if (strlen ($client) >= 11) {
        $client = substr($client, 0, 9) . "..";
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
          $row["comment"]);
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