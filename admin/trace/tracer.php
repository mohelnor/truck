<?php
require_once('../../Connections/conn.php');
require_once('../header.php');

$query_vehicles = "SELECT * FROM vehiclev";
$colname_vehicles = "-1";
if (isset($_GET['driver'])) {
  $colname_vehicles = $_GET['driver'];
  $query_vehicles = sprintf("SELECT * FROM vehiclev WHERE d_id = %s", $colname_vehicles);
}

$vehicles = mysql_query($query_vehicles, $conn) or die(mysql_error());
$row_vehicles = mysql_fetch_assoc($vehicles);
$totalRows_vehicles = mysql_num_rows($vehicles);

$query_drivers = "SELECT * FROM users WHERE users.permit = 'driver'";
$drivers = mysql_query($query_drivers, $conn) or die(mysql_error());
$row_drivers = mysql_fetch_assoc($drivers);
$totalRows_drivers = mysql_num_rows($drivers);
?>


<form action="" method="get" class="d-print-none row g-1 mb-1">
  <div class="col-1">
    <a href="?" class="btn btn-info">الكل</a>
  </div>
  <div class="col-4">

    <select class="form-control" name="driver">
      <?php
      do {
      ?>
        <option value="<?php echo $row_drivers['id'] ?>" <?php if (!(strcmp($row_drivers['id'], $row_drivers['id']))) {
                                                            echo "selected=\"selected\"";
                                                          } ?>><?php echo $row_drivers['name'] ?></option>
      <?php
      } while ($row_drivers = mysql_fetch_assoc($drivers));
      $rows = mysql_num_rows($drivers);
      if ($rows > 0) {
        mysql_data_seek($drivers, 0);
        $row_drivers = mysql_fetch_assoc($drivers);
      }
      ?>

    </select>
  </div>
  <div class="col-1">
    <button class="btn btn-warning" type="submit"> بحث</button>
  </div>
  <div class="col-1">

    <button class="btn btn-dark" onclick="print()">طباعة</button>
  </div>
</form>


<link rel="stylesheet" href="../../assets/css/bs-stepper.min.css">
<div class="container flex-grow-1 flex-shrink-0 py-5">

  <?php
  do {
    $query_trace = sprintf("SELECT * FROM `tracev` WHERE v_id = %s GROUP by state", $row_vehicles['id']);
    $trace = mysql_query($query_trace, $conn) or die(mysql_error());
    $row_trace = mysql_fetch_assoc($trace);
    $totalRows_trace = mysql_num_rows($trace);
  ?>
    <div class="mb-2 p-1 bg-white shadow-sm">
      <h3 class="px-4"><?php echo $row_vehicles['name']; ?></h3>
      <div id="stepper1" class="bs-stepper">
        <div class="bs-stepper-header" role="tablist">
          <?php
          if ($totalRows_trace > 0) {
            $count = 0;
            do { ?>
              <div class="step" data-target="#test-l-1">
                <button type="button" class="step-trigger" role="tab" id="stepper1trigger1" aria-controls="test-l-1">
                  <span class="bs-stepper-circle <?php echo 'bg-info'; ?>"><?php echo ++$count; ?></span>
                  <span class="bs-stepper-label"><?php echo $row_trace['state']; ?></span><?php echo $row_trace['created']; ?>
                </button>
              </div>
              <?php  if ($count < 4) { ?>
              <div class="bs-stepper-line"></div>
            <?php 
              }} while ($row_trace = mysql_fetch_assoc($trace));
          } else { ?>
            <div class="text-center">
              <h6>لا توجد إجراءات متاحه</h6>
            </div>
          <?php
          }
          ?>

        </div>
      </div>
    </div>
  <?php } while ($row_vehicles = mysql_fetch_assoc($vehicles)); ?>
</div>

<script src="../../assets/js/bs-stepper.min.js"></script>
<?php
require_once('../footer.php');
?>