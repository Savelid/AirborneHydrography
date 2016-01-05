<?php
session_start();
$titel = 'ISP log';
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

  <a href="edit_isp.php" class="btn btn-default navbar-btn" role="button">New ISP entry</a>

</section>

<section>

  <?php

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "  SELECT * FROM isp ";
  $sql .= "ORDER BY isp_nr DESC";
  $result = $conn->query($sql);
  if (!$result) {
    echo $sql . "<br><br>" . $conn->error;
    die("Query failed!");
  }
  $conn->close();

  if ($result->num_rows > 0) {

    echo '
    <table class="table table-striped table-responsive table_750">
    <thead>
    <tr>
    <th>ISP</th>
    <th>Date</th>
    <th>Product</th>
    <th>Code</th>
    <th>Receiver</th>
    <th>Country</th>
    <th>Value</th>
    <th class="hidden-print"></th>
    </tr>
    </thead>
    <tbody>';

    // %3$s will be replaced with variables later
    $table_row_formating =
    '
    <tr>

    <td>
      <a href="edit_isp.php?id=%10$s" class="btn btn-default btn-sm">
      <span class="glyphicon glyphicon-pencil hidden-print" aria-hidden="true"></span>
      %1$s
      </a>
    </td>

    <td> %2$s </td>
    <td> %3$s </td>
    <td> %4$s </td>
    <td> %5$s </td>
    <td> %6$s </td>
    <td> %7$s pcs %8$s </td>

    <td class="hidden-print"><button type="button" class="btn btn-sm btn-default" data-container="body" data-toggle="popover" data-placement="left" data-html="true" data-content="%9$s">
    <span class="glyphicon glyphicon-comment" aria-hidden="true"></span></button>
    </td>

    </tr>';
    // output data of each row
    while($row = $result->fetch_assoc()) {

      echo sprintf($table_row_formating,
      $row["isp_nr"],
      substr($row["datetime"], 0 , 10),
      $row["product"],
      $row["code"],
      $row["receiver"],
      $row["country"],
      $row["amount"],
      $row["value"],
      $row["comment"],
      $row["id"]);
    }
    echo '
    </tbody>
    </table>';
  } else {
    echo "Empty table or no search results";
  }
  ?>

</section>
<footer>

</footer>

<?php
include 'res/footer.inc.php';
?>
