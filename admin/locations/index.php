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

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form2")) {
  $insertSQL = sprintf(
    "INSERT INTO locations (name, extra) VALUES (%s, %s)",
    GetSQLValueString($_POST['name'], "text"),
    GetSQLValueString($_POST['extra'], "text")
  );


  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "index.php";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf(
    "UPDATE locations SET name=%s, extra=%s WHERE id=%s",
    GetSQLValueString($_POST['name'], "text"),
    GetSQLValueString($_POST['extra'], "text"),
    GetSQLValueString($_POST['id'], "int")
  );


  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "index.php";
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['id']) && isset($_GET['delete']) && $_GET['id'] != "") {
  $deleteSQL = sprintf(
    "DELETE FROM locations WHERE id=%s",
    GetSQLValueString($_GET['id'], "int")
  );


  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());

  $deleteGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_locations = 10;
$pageNum_locations = 0;
if (isset($_GET['pageNum_locations'])) {
  $pageNum_locations = $_GET['pageNum_locations'];
}
$startRow_locations = $pageNum_locations * $maxRows_locations;

$query_locations = "SELECT * FROM locations";
if (isset($_GET['id'])){
  $query_locations = "SELECT * FROM locations where id =".$_GET['id'];
}
$query_limit_locations = sprintf("%s LIMIT %d, %d", $query_locations, $startRow_locations, $maxRows_locations);
$locations = mysql_query($query_limit_locations, $conn) or die(mysql_error());
$row_locations = mysql_fetch_assoc($locations);

if (isset($_GET['totalRows_locations'])) {
  $totalRows_locations = $_GET['totalRows_locations'];
} else {
  $all_locations = mysql_query($query_locations);
  $totalRows_locations = mysql_num_rows($all_locations);
}
$totalPages_locations = ceil($totalRows_locations / $maxRows_locations) - 1;

$colname_l_edit = "-1";
if (isset($_GET['id']) && isset($_GET['edit'])) {
  $colname_l_edit = $_GET['id'];
}

$query_l_edit = sprintf("SELECT * FROM locations WHERE id = %s", GetSQLValueString($colname_l_edit, "int"));
$l_edit = mysql_query($query_l_edit, $conn) or die(mysql_error());
$row_l_edit = mysql_fetch_assoc($l_edit);
$totalRows_l_edit = mysql_num_rows($l_edit);

$queryString_locations = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (
      stristr($param, "pageNum_locations") == false &&
      stristr($param, "totalRows_locations") == false
    ) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_locations = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_locations = sprintf("&totalRows_locations=%d%s", $totalRows_locations, $queryString_locations);

include_once('../header.php');
?>

<?php if (isset($_GET['add']) || isset($_GET['edit'])){ ?>
<form method="post" name="form2" action="<?php echo $editFormAction; ?>">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">الاسم</td>
      <td><input class="form-control" type="text" name="name" value="<?php echo htmlentities($row_l_edit['name'], ENT_COMPAT, ''); ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right" valign="top">معلومات</td>
      <td><textarea class="form-control" name="extra" cols="50" rows="5"><?php echo htmlentities($row_l_edit['extra'], ENT_COMPAT, ''); ?></textarea></td>
    </tr>
    <tr valign="baseline">
        <td nowrap align="right">
          <a href="?" class="btn btn-warning">
          <i class="fas fa-eye-slash"></i>
          </a>
        </td>
      <?php if (isset($_GET['edit'])){ ?>
      <td><input class="btn btn-info w-100" type="submit" value="تعديل"></td>
        <input class="btn" type="hidden" name="MM_update" value="form2">
      <?php }else{?>
      <td><input class="btn btn-success w-100" type="submit" value="إضافة"></td>
        <input class="btn" type="hidden" name="MM_insert" value="form2">
      <?php }?>
      </tr>
  </table>
  <input type="hidden" name="id" value="<?php echo $row_l_edit['id']; ?>">
</form>
<p>&nbsp;</p>
<?php }?>
<div class="d-print-none my-2">
  <a href="report.php" class="btn btn-secondary"> تقرير</a>
</div>
<table class="table table-bordered table-responsive">
  <thead>
    <tr>
      <th>#</th>
      <th>الاسم</th>
      <th>معلومات الموقع</th>
      <th>إجراء</th>
    </tr>
  </thead>
  <?php do { ?>
    <tr>
      <td><?php echo $row_locations['id']; ?></td>
      <td><?php echo $row_locations['name']; ?></td>
      <td><?php echo $row_locations['extra']; ?></td>
      <td>
      <a class="btn btn-link"href="index.php?add=true&id=<?php echo $row_locations['id']; ?>"><i class="fas fa-plus text-secondary"></i></a>
        <a class="btn btn-link"href="index.php?edit=true&id=<?php echo $row_locations['id']; ?>"><i class="fas fa-edit text-info"></i></a>
      <a class="btn btn-link"href="index.php?delete=true&id=<?php echo $row_locations['id']; ?>"><i class="fas fa-trash text-danger"></i></a>
      </td>
    </tr>
  <?php } while ($row_locations = mysql_fetch_assoc($locations)); ?>
</table>
<table border="0">
  <tr>
    <td><?php if ($pageNum_locations > 0) { // Show if not first page 
        ?>
        <a href="<?php printf("%s?pageNum_locations=%d%s", $currentPage, 0, $queryString_locations); ?>">الأول</a>
      <?php } // Show if not first page 
      ?>
    </td>
    <td><?php if ($pageNum_locations > 0) { // Show if not first page 
        ?>
        <a href="<?php printf("%s?pageNum_locations=%d%s", $currentPage, max(0, $pageNum_locations - 1), $queryString_locations); ?>">السابق</a>
      <?php } // Show if not first page 
      ?>
    </td>
    <td><?php if ($pageNum_locations < $totalPages_locations) { // Show if not last page 
        ?>
        <a href="<?php printf("%s?pageNum_locations=%d%s", $currentPage, min($totalPages_locations, $pageNum_locations + 1), $queryString_locations); ?>">اللاحق</a>
      <?php } // Show if not last page 
      ?>
    </td>
    <td><?php if ($pageNum_locations < $totalPages_locations) { // Show if not last page 
        ?>
        <a href="<?php printf("%s?pageNum_locations=%d%s", $currentPage, $totalPages_locations, $queryString_locations); ?>">الأخير</a>
      <?php } // Show if not last page 
      ?>
    </td>
  </tr>
</table>
<?php
include_once('../footer.php');
mysql_free_result($locations);

mysql_free_result($l_edit);
?>