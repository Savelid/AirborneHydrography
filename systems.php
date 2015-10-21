<?php
$titel = 'Overview';
include 'res/header.inc.php'; 
?>

<section class="content">

  <section>
    <a href="#" class="btn btn-default" role="button">New system</a>
  </section>


  <section class="all__systems">
    <table class="large__table table table-striped table-responsive">
    <thead>
      <tr>
        <th>Serial nr.</th>
        <th>Client</th>
        <th>Config.</th>
        <th>Sensor unit</th>
        <th>Control unit</th>
        <th>Deep system</th>
        <th>Control system</th>
        <th>Topo</th>
        <th>Shallow</th>
        <th>Deep</th>
        <th>SCU</th>
        <th>PDU</th>
        <th>Status</th>
        <th colspan=2>Comments</th>
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
$table_row_formating =
'
<tr>
  <td>
    <div class="btn-group">
      <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        %1$s <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
        <li><a href="view_system.php?system=%1$s">View</a></li>
        <li><a href="edit_system.php?system=%1$s">Edit</a></li>
        <li><a href="delete.php?system=%1$s">Delete</a></li>
      </ul>
    </div>
  </td>

  <td>
    %2$s
  </td>

  <td>
    %3$s
  </td>

  <td>
    %4$s
  </td>

  <td>
    %5$s
  </td>

  <td>
    %6$s
  </td>

  <td>
    %7$s
  </td>

  <td>
    %8$s
  </td>

  <td>
    %9$s
  </td>

  <td>
    %10$s
  </td>

  <td>
    %11$s
  </td>

  <td>
    %12$s
  </td>

  <td>
    Ready
  </td>

  <td colspan=2>
    %13$s
  </td>
</tr>
';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo sprintf($table_row_formating,
          $row["serial_nr"],
          $row["client"],
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

</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>