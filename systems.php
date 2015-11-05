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
        <th colspan=2>Topo</th>
        <th colspan=2>Shallow</th>
        <th colspan=2>Deep</th>
        <th colspan=2>SCU</th>
        <th colspan=2>Status</th>
        <th colspan=5>Comments</th>
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

$sql = "  SELECT system.serial_nr, client, configuration, sensor_unit_sn, control_unit_sn, deep_system_sn,
          deep_system.control_system, sensor_unit.topo_sensor_sn, sensor_unit.shallow_sensor_sn, deep_system.deep_sensor_sn,
          control_unit.scu_sn, control_unit.pdu, comment
          FROM system
          LEFT JOIN sensor_unit ON sensor_unit_sn = sensor_unit.serial_nr
          LEFT JOIN control_unit ON control_unit_sn = control_unit.serial_nr
          LEFT JOIN deep_system ON deep_system_sn = deep_system.serial_nr
          ORDER BY system.datetime DESC";
$result = $conn->query($sql);
if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
}
$conn->close();

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
    %2$s
  </td>
';
$config_formating = '
  <td colspan=3>
    %3$s
  </td>
';

$topo_shallow_deep_formating = '
 <td colspan=2>
      <a href="view_sensor.php?serial_nr=%4$s" class="btn btn-default btn-xs">
        %4$s
      </a>
  </td>

  <td colspan=2>
      <a href="view_sensor.php?serial_nr=%5$s" class="btn btn-default btn-xs">
        %5$s
      </a>
  </td>

  <td colspan=2>
      <a href="view_sensor.php?serial_nr=%6$s" class="btn btn-default btn-xs">
        %6$s
      </a>
  </td>
';
$scu_formating = '
  <td colspan=2>
      <a href="#" class="btn btn-default btn-xs">
        %7$s
      </a>
  </td>
';
$system_status_formating = '
  <td colspan=2>
    %8$s
  </td>
';
$comment_formating = '
  <td colspan=5>
    %9$s
  </td>
';

$table_row_formating = '<tr>'
                        . $serial_nr_formating
                        . $client_formating
                        . $config_formating
                        . $topo_shallow_deep_formating
                        . $scu_formating
                        . $system_status_formating
                        . $comment_formating
                        .'</tr>';

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

      // shorten too long client names
      $client = $row["client"];
      if (strlen ($client) >= 14) {
        $client = substr($client, 0, 12) . "..";
      }
      // shorten too long comments
      $comment = $row["comment"];
      if (strlen ($comment) >= 32) {
        $comment = substr($comment, 0, 30) . "..";
      }

      // // Merge the 8 status options into ready or not
      // if(       $row['status_potta_heat'] &&
      //           $row['status_shallow_heat']&&
      //           $row['status_scu_pdu']&&
      //           $row['status_hv_topo']&&
      //           $row['status_hv_shallow']&&
      //           $row['status_hv_deep']&&
      //           $row['status_cat']&&
      //           $row['status_pwr_cable']){
      //   $status = 'Ready';
      // }else if( !$row['status_potta_heat'] &&
      //           !$row['status_shallow_heat']&&
      //           !$row['status_scu_pdu']&&
      //           !$row['status_hv_topo']&&
      //           !$row['status_hv_shallow']&&
      //           !$row['status_hv_deep']&&
      //           !$row['status_cat']&&
      //           !$row['status_pwr_cable']){
      //   $status = 'Nothing';
      // } else {
      //   $status = 'Some';
      // }
      $status = 'Temp';

        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $client,
          $row["configuration"],
          $row["topo_sensor_sn"],
          $row["shallow_sensor_sn"],
          $row["deep_sensor_sn"],
          $row["scu_sn"],
          $status,
          $comment);
    }
} else {
    echo "No messages";
}
?>

    </tbody>
  </table>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>