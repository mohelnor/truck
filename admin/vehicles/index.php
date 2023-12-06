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
    "INSERT INTO vehicles (id, name, driver, license) VALUES (%s, %s, %s, %s)",
    GetSQLValueString($_POST['id'], "int"),
    GetSQLValueString($_POST['name'], "text"),
    GetSQLValueString($_POST['driver'], "int"),
    GetSQLValueString($_POST['license'], "text")
  );


  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "index.php";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf(
    "UPDATE vehicles SET name=%s, driver=%s, license=%s WHERE id=%s",
    GetSQLValueString($_POST['name'], "text"),
    GetSQLValueString($_POST['driver'], "int"),
    GetSQLValueString($_POST['license'], "text"),
    GetSQLValueString($_POST['id'], "int")
  );


  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "index.php";
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['id']) && isset($_GET['delete']) && $_GET['id'] != "") {
  $deleteSQL = sprintf(
    "DELETE FROM vehicles WHERE id=%s",
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

$maxRows_vehicles = 10;
$pageNum_vehicles = 0;
if (isset($_GET['pageNum_vehicles'])) {
  $pageNum_vehicles = $_GET['pageNum_vehicles'];
}
$startRow_vehicles = $pageNum_vehicles * $maxRows_vehicles;


$query_vehicles = "SELECT * FROM vehicles";
$query_limit_vehicles = sprintf("%s LIMIT %d, %d", $query_vehicles, $startRow_vehicles, $maxRows_vehicles);
$vehicles = mysql_query($query_limit_vehicles, $conn) or die(mysql_error());
$row_vehicles = mysql_fetch_assoc($vehicles);

if (isset($_GET['totalRows_vehicles'])) {
  $totalRows_vehicles = $_GET['totalRows_vehicles'];
} else {
  $all_vehicles = mysql_query($query_vehicles);
  $totalRows_vehicles = mysql_num_rows($all_vehicles);
}
$totalPages_vehicles = ceil($totalRows_vehicles / $maxRows_vehicles) - 1;

$colname_v_edit = "-1";
if (isset($_GET['id']) && isset($_GET['edit'])) {
  $colname_v_edit = $_GET['id'];
}

$query_v_edit = sprintf("SELECT * FROM vehicles WHERE id = %s", GetSQLValueString($colname_v_edit, "int"));
$v_edit = mysql_query($query_v_edit, $conn) or die(mysql_error());
$row_v_edit = mysql_fetch_assoc($v_edit);
$totalRows_v_edit = mysql_num_rows($v_edit);

$queryString_vehicles = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (
      stristr($param, "pageNum_vehicles") == false &&
      stristr($param, "totalRows_vehicles") == false
    ) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_vehicles = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_vehicles = sprintf("&totalRows_vehicles=%d%s", $totalRows_vehicles, $queryString_vehicles);

$query_drivers = "SELECT * FROM users WHERE permit = 'driver'";
$users = mysql_query($query_drivers, $conn) or die(mysql_error());
$row_drivers = mysql_fetch_assoc($users);
$totalRows_drivers = mysql_num_rows($users);

include_once('../header.php');
if (isset($_GET['add']) || isset($_GET['edit'])) { ?>
  <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
    <table align="center">
      <tr valign="baseline">
        <td nowrap align="right">الاسم</td>
        <td><input class="form-control" type="text" name="name" value="<?php echo htmlentities($row_v_edit['name'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">السائق</td>
        <td>
          <select class="form-control" name="driver">
            <?php
            do {
            ?>
              <option value="<?php echo $row_drivers['id'] ?>" <?php if (!(strcmp($row_drivers['id'], htmlentities($row_v_edit['driver'], ENT_COMPAT, 'UTF-8')))) {
                                                                  echo "SELECTED";
                                                                } ?>><?php echo $row_drivers['name'] ?></option>
            <?php
            } while ($row_drivers = mysql_fetch_assoc($users));
            ?>
          </select>
        </td>

      </tr>

      <tr valign="baseline">
        <td nowrap align="right">الترخيص</td>
        <td><input class="form-control" type="text" name="license" value="<?php echo htmlentities($row_v_edit['license'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">
          <a href="?" class="btn btn-warning">
            <i class="fas fa-eye-slash"></i>
          </a>
        </td>
        <?php if (isset($_GET['edit'])) { ?>
          <td><input class="btn btn-info w-100" type="submit" value="تعديل"></td>
          <input class="" type="hidden" name="MM_update" value="form2">
        <?php } else { ?>
          <td><input class="btn btn-success w-100" type="submit" value="إضافة"></td>
          <input class="" type="hidden" name="MM_insert" value="form2">
        <?php } ?>
      </tr>
    </table>
    <input class="form-control" type="hidden" name="MM_update" value="form2">
    <input class="form-control" type="hidden" name="id" value="<?php echo $row_v_edit['id']; ?>">
  </form>

<?php } ?>
<div class="d-print-none my-2">
  <a href="report.php" class="btn btn-secondary"> تقرير</a>
</div>
<table class="table table-bordered table-responsive">
  <thead>

    <tr>
      <th>#</th>
      <th>الاسم</th>
      <th>السائق</th>
      <th>الترخيص</th>
      <th>الحاله</th>
      <th>الاجراء</th>
    </tr>
  </thead>
  <?php
  if ($totalRows_vehicles > 0) {
    do { ?>
      <tr>
        <td><?php echo $row_vehicles['id']; ?></td>
        <td><?php echo $row_vehicles['name']; ?></td>
        <td>
          <a href="../users/index.php?id=<?php echo $row_vehicles['driver']; ?>">
            <?php echo $row_vehicles['driver']; ?>
          </a>
        </td>
        <td><?php echo $row_vehicles['license']; ?></td>
        <td><?php echo $row_vehicles['state']; ?></td>
        <td>
          <a class="btn btn-link" href="index.php?add=true&id=<?php echo $row_vehicles['id']; ?>"><i class="fas fa-plus text-secondary"></i></a>
          <a class="btn btn-link" href="index.php?edit=true&id=<?php echo $row_vehicles['id']; ?>"><i class="fas fa-edit text-info"></i></a>
          <a class="btn btn-link" href="index.php?delete=true&id=<?php echo $row_vehicles['id']; ?>"><i class="fas fa-trash text-danger"></i></a>
        </td>
      </tr>
    <?php } while ($row_vehicles = mysql_fetch_assoc($vehicles));
  } else { ?>
    <tr>
      <td colspan="5" class="text-center">
        لا توجد بيانات
      </td>
    </tr>
  <?php
  }
  ?>
</table>
<table border="0">
  <tr>
    <td><?php if ($pageNum_vehicles > 0) { // Show if not first page 
        ?>
        <a href="<?php printf("%s?pageNum_vehicles=%d%s", $currentPage, 0, $queryString_vehicles); ?>">الأول</a>
      <?php } // Show if not first page 
      ?>
    </td>
    <td><?php if ($pageNum_vehicles > 0) { // Show if not first page 
        ?>
        <a href="<?php printf("%s?pageNum_vehicles=%d%s", $currentPage, max(0, $pageNum_vehicles - 1), $queryString_vehicles); ?>">السابق</a>
      <?php } // Show if not first page 
      ?>
    </td>
    <td><?php if ($pageNum_vehicles < $totalPages_vehicles) { // Show if not last page 
        ?>
        <a href="<?php printf("%s?pageNum_vehicles=%d%s", $currentPage, min($totalPages_vehicles, $pageNum_vehicles + 1), $queryString_vehicles); ?>">التالي</a>
      <?php } // Show if not last page 
      ?>
    </td>
    <td><?php if ($pageNum_vehicles < $totalPages_vehicles) { // Show if not last page 
        ?>
        <a href="<?php printf("%s?pageNum_vehicles=%d%s", $currentPage, $totalPages_vehicles, $queryString_vehicles); ?>">الأخير</a>
      <?php } // Show if not last page 
      ?>
    </td>
  </tr>
</table>
<?php
include_once('../footer.php');
mysql_free_result($vehicles);
mysql_free_result($v_edit);
?>