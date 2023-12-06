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


$query_locations = "SELECT * FROM locations";
$locations = mysql_query($query_locations, $conn) or die(mysql_error());
$row_locations = mysql_fetch_assoc($locations);
$totalRows_locations = mysql_num_rows($locations);
include_once('../header.php');
?>
<div class="d-print-none">
<button class="btn btn-dark" onclick="print()">طباعة</button>
</div>
<table class="table table-bordered table-responsive">
  <tr>
    <th>#</th>
    <th>الاسم</th>
    <th>معلومات الموقع</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_locations['id']; ?></td>
      <td><?php echo $row_locations['name']; ?></td>
      <td><?php echo $row_locations['extra']; ?></td>
    </tr>
    <?php } while ($row_locations = mysql_fetch_assoc($locations)); ?>
</table>
<?php 
include_once('../footer.php');
mysql_free_result($locations);
?>
