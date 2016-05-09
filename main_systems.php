<?php
session_start();
$titel = 'Systems';
include 'res/header.inc.php';
?>
<script>
$(function () {
  $('[data-toggle="popover"]').popover()
})
</script>


<section class="content hidden-print">

  <?php
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

  <a href="edit_system.php" class="btn btn-default" role="button">New system</a>
</section>

<section>
  <div class="table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Serial nr.</th>
        <th>Client</th>
        <th>Config.</th>
        <th>Topo</th>
        <th>Shallow</th>
        <th>Deep</th>
        <th>SCU</th>
        <th>Status</th>
        <th></th>
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

      $sql = "  SELECT system.id AS id, system.serial_nr, client, configuration, sensor_unit_sn, control_system_sn, deep_system_sn,
      sensor_unit.topo_sensor_sn, sensor_unit.topo_sensor_2_sn, sensor_unit.shallow_sensor_sn, deep_system.deep_sensor_sn,
      control_system.scu_sn, status, system.comment
      FROM system
      LEFT JOIN sensor_unit ON sensor_unit_sn = sensor_unit.serial_nr
      LEFT JOIN control_system ON control_system_sn = control_system.serial_nr
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
      <td>
      <div class="btn-group">
      <button class="btn btn-default btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      %1$s <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" role="menu">
      <li><a href="view_system.php?id=%12$s">View</a></li>
      <li role="separator" class="divider"></li>
      <li><a href="edit_system.php?id=%12$s">Edit</a></li>
      </ul>
      </div>
      </td>
      ';
      $client_formating = '
      <td>
      %2$s
      </td>
      ';
      $config_formating = '
      <td>
      %3$s
      </td>
      ';

      $topo_shallow_deep_formating = '
      <td %11$s>
      <a href="view_sensor.php?serial_nr=%4$s" class="btn btn-default btn-sm">%4$s</a>
      %10$s
      <a href="view_sensor.php?serial_nr=%5$s" class="btn btn-default btn-sm">%5$s</a>
      </td>

      <td>
      <a href="view_sensor.php?serial_nr=%6$s" class="btn btn-default btn-sm">%6$s</a>
      </td>
      ';
      $scu_formating = '
      <td>
      <a href="#" class="btn btn-default btn-sm">%7$s</a>
      </td>
      ';
      $system_formating = '
      <td>
      %8$s
      </td>
      ';
      $comment_formating = '
      <td><button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="%9$s">
      <span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
      </td>
      ';

      $table_row_formating = '<tr>'
      . $serial_nr_formating
      . $client_formating
      . $config_formating
      . $topo_shallow_deep_formating
      . $scu_formating
      . $system_formating
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

          $topo2_shallow = $row["shallow_sensor_sn"];
          $formating1 = '</td><td>';
          $formating2 = '';
          if(isset($row["topo_sensor_2_sn"]) && $row["topo_sensor_2_sn"] != '') {
            $topo2_shallow = $row["topo_sensor_2_sn"];
            $formating1 = '';
            $formating2 = 'colspan=2';
          }

          echo sprintf($table_row_formating,
          $row["serial_nr"],
          $client,
          $row["configuration"],
          $row["topo_sensor_sn"],
          $topo2_shallow,
          $row["deep_sensor_sn"],
          $row["scu_sn"],
          $row["status"],
          $row["comment"],
          $formating1,
          $formating2,
          $row["id"]);
        }
      } else {
        echo "No messages";
      }
      ?>

    </tbody>
  </table>
  </div>
</section>
<footer>

</footer>

<?php include 'res/footer.inc.php'; ?>
