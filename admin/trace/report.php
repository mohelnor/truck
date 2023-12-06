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


$query_vehicles = "SELECT * FROM vehicles";
$vehicles = mysql_query($query_vehicles, $conn) or die(mysql_error());
$row_vehicles = mysql_fetch_assoc($vehicles);
$totalRows_vehicles = mysql_num_rows($vehicles);

$query_trace = "SELECT * FROM tracev";
$colname_trace = "-1";
if (isset($_GET['vehicle'])) {
  $colname_trace = $_GET['vehicle'];
  $query_trace = sprintf("SELECT * FROM tracev WHERE v_id = %s", GetSQLValueString($colname_trace, "int"));
}

$trace = mysql_query($query_trace, $conn) or die(mysql_error());
$row_trace = mysql_fetch_assoc($trace);
$totalRows_trace = mysql_num_rows($trace);
include_once('../header.php');
?>

<form action="" method="get" class="d-print-none row g-1 mb-2">
<div class="col-1">
    <a href="?" class="btn btn-info">الكل</a>
  </div>
  <div class="col-4">

    <select class="form-control" name="vehicle">
      <?php
    do {
      ?>
      <option value="<?php echo $row_vehicles['id'] ?>" <?php if (!(strcmp($row_vehicles['id'], $row_vehicles['id']))) {
                                                          echo "selected=\"selected\"";
                                                        } ?>><?php echo $row_vehicles['name'] ?></option>
    <?php
    } while ($row_vehicles = mysql_fetch_assoc($vehicles));
    $rows = mysql_num_rows($vehicles);
    if ($rows > 0) {
      mysql_data_seek($vehicles, 0);
      $row_vehicles = mysql_fetch_assoc($vehicles);
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
  <tr>
    <th>#</th>
    <th>المركبة</th>
    <th>المستخدم</th>
    <th>الموقغ</th>
    <th>التاريخ</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_trace['id']; ?></td>
      <td><?php echo $row_trace['vehicle']; ?></td>
      <td><?php echo $row_trace['user']; ?></td>
      <td><?php echo $row_trace['cur_loc']; ?></td>
      <td><?php echo $row_trace['created']; ?></td>
    </tr>
  <?php } while ($row_trace = mysql_fetch_assoc($trace)); ?>
</table>
<?php
include_once('../footer.php');
mysql_free_result($vehicles);

mysql_free_result($trace);
?>