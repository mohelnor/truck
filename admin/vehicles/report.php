<?php require_once('../../Connections/conn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
  function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
  {
    if (PHP_VERSION < 6) {
      $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
    }

    $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

    switch ($theType) {
      case "text":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "long":
      case "int":
        $theValue = ($theValue != "") ? intval($theValue) : "NULL";
        break;
      case "double":
        $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
        break;
      case "date":
        $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
        break;
      case "defined":
        $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
        break;
    }
    return $theValue;
  }
}

$query_vehicles = "SELECT * FROM vehiclev";
$colname_vehicles = "-1";
if (isset($_GET['driver'])) {
  $colname_vehicles = $_GET['driver'];
  $query_vehicles = sprintf("SELECT * FROM vehiclev WHERE d_id = %s", GetSQLValueString($colname_vehicles, "int"));
}

$vehicles = mysql_query($query_vehicles, $conn) or die(mysql_error());
$row_vehicles = mysql_fetch_assoc($vehicles);
$totalRows_vehicles = mysql_num_rows($vehicles);


$query_drivers = "SELECT * FROM users WHERE users.permit = 'driver'";
$drivers = mysql_query($query_drivers, $conn) or die(mysql_error());
$row_drivers = mysql_fetch_assoc($drivers);
$totalRows_drivers = mysql_num_rows($drivers);
include_once('../header.php');
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

<table class="table table-bordered table-responsive">
  <thead>
    <tr>
      <th>#</th>
      <th>الاسم</th>
      <th>السائق</th>
      <th>الترخيص</th>
      <th>حالة المركبة</th>
  </thead>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_vehicles['id']; ?></td>
      <td><?php echo $row_vehicles['name']; ?></td>
      <td><?php echo $row_vehicles['driver']; ?></td>
      <td><?php echo $row_vehicles['license']; ?></td>
      <td><?php echo $row_vehicles['state']; ?></td>
    </tr>
  <?php } while ($row_vehicles = mysql_fetch_assoc($vehicles)); ?>
</table>
<?php
include_once('../footer.php');
mysql_free_result($vehicles);

mysql_free_result($drivers);
?>