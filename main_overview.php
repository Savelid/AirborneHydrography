<?php
session_start();
if (!empty($_POST)):
  $_SESSION['showalert'] = 'true';
  include 'res/config.inc.php';
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $sql = "DELETE FROM overview WHERE id = '$_POST[id]';";
  if ($conn->query($sql) === TRUE) {
    $_SESSION['alert'] = 'Message deleted';
    header("Location: main_overview.php");
    die();
  } else {
    echo "Error: " . $sql_insert . "<br>" . $conn->error;
  }
  $conn->close();  // close db
else:
  $titel = 'Overview';
  include 'res/header.inc.php';
  ?>
  <script>
  $(function(){
    $('#confirmModal').on('show.bs.modal', function (event) {
      var button = $(event.relatedTarget) // Button that triggered the modal
      var id = button.data('id') // Extract info from data-* attributes
      var modal = $(this)
      modal.find('.modal-body #id').val(id)
    })
  })
  </script>

  <div class="modal fade" id="confirmModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Delete message</h4>
        </div>
        <div class="modal-body">
          <p>This will permanently remove the message</p>
          <form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post" id="deleteForm">
            <input type="hidden" name="id" id="id" />
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" form="deleteForm" class="btn btn-primary">Delete</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <section class="content">

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

    <div class="row">
      <div class="col-md-6">
        <dl>
          <?php
          // Create connection
          $conn = new mysqli($servername, $username, $password, $dbname);
          // Check connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          //

          $sql = "SELECT * FROM overview ORDER BY datetime DESC LIMIT 5";
          $result = $conn->query($sql);

          // %s will be replaced with variables later
          $message_item_formating =
          '
          <div class="panel panel-default">
          <div class="panel-heading">
          <div class="row">
          <div class="col-xs-9"><h3 class="panel-title">%s <small>%s</small></h3></div>
          <div class="col-xs-3 text-right">
          <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#confirmModal" data-id="%s">
          <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
          </button>
          </div>
          </div>
          </div>
          <div class="panel-body">
          %s
          </div>
          </div>
          ';
          if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {

              echo sprintf($message_item_formating, $row["datetime"], $row["author"], $row["id"], $row["message"]);
            }
          } else {
            echo "No messages";
          }

          ?>
        </dl>
        <form action="post_add_update.php?type=add_message" method="post">
          <div class="form-group">
            <label for="message">Message</label>
            <textarea class="form-control" name="message" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="user">Name</label>
            <input type="text" class="form-control" name="user" <?= !empty($_SESSION['user']) ? 'value="' . $_SESSION['user'] . '"' : ''; ?> required />
          </div>
          <button type="submit" class="btn btn-default">Submit</button><br><br>
        </form>
      </div><!--end column-->

      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Systems</h3>
          </div>
          <div class="panel-body panel_table">

            <table class="table table-striped table-responsive">
              <thead>
                <tr>
                  <th>Serial nr.</th>
                  <th>Client</th>
                  <th>Place</th>
                  <th>Config.</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>

                <?php

                $sql = "  SELECT *
                FROM system
                ORDER BY datetime DESC";
                $result = $conn->query($sql);
                if (!$result) {
                  die("Query failed!");
                }

                // %s will be replaced with variables later

                $table_row_formating = '
                <tr>
                <td><a href="view_system.php?system=%s" class="btn btn-default btn-sm">%s</a></td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                <td>%s</td>
                </tr>
                <tr>
                <td colspan=5>%s</td>
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
                    // shorten too long place names
                    $place = $row["place"];
                    if (strlen ($client) >= 11) {
                      $client = substr($client, 0, 9) . "..";
                    }
                    // shorten too long comments
                    $comment = $row["comment"];
                    if (strlen ($comment) >= 110) {
                      $comment = substr($comment, 0, 107) . "..";
                    }

                    echo sprintf($table_row_formating,
                    $row["serial_nr"],
                    $row["serial_nr"],
                    $client,
                    $place,
                    $row["configuration"],
                    $row["status"],
                    $comment);
                  }
                } else {
                  echo "No messages";
                }
                $conn->close();
                ?>

              </tbody>
            </table>
          </div>
        </div>

      </div><!--end column-->

    </div><!--end row-->

  </section>
  <footer>

  </footer>

  <?php
  include 'res/footer.inc.php';
endif;
?>
