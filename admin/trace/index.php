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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf(
    "INSERT INTO trace (vehicle,user, cur_loc) VALUES (%s,%s, %s)",
    GetSQLValueString($_POST['vehicle'], "int"),
    GetSQLValueString($_POST['user'], "int"),
    GetSQLValueString($_POST['cur_loc'], "int")
  );


  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "index.php";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf(
    "UPDATE trace SET vehicle=%s, user=%s, cur_loc=%s WHERE id=%s",
    GetSQLValueString($_POST['vehicle'], "int"),
    GetSQLValueString($_POST['user'], "int"),
    GetSQLValueString($_POST['cur_loc'], "int"),
    GetSQLValueString($_POST['id'], "int")
  );


  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "index.php";
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['id']) && isset($_GET['delete']) && $_GET['id'] != "") {
  $deleteSQL = sprintf(
    "DELETE FROM trace WHERE id=%s",
    GetSQLValueString($_GET['id'], "int")
  );


  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());

  $deleteGoTo = "index.php";
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_trace = 10;
$pageNum_trace = 0;
if (isset($_GET['pageNum_trace'])) {
  $pageNum_trace = $_GET['pageNum_trace'];
}
$startRow_trace = $pageNum_trace * $maxRows_trace;

$query_trace = "SELECT * FROM tracev";
$colname_trace = "-1";
if (isset($_GET['vehicle'])) {
  $colname_trace = $_GET['vehicle'];
  $query_trace = sprintf("SELECT * FROM tracev WHERE v_id = %s", GetSQLValueString($colname_trace, "int"));
}

$query_limit_trace = sprintf("%s LIMIT %d, %d", $query_trace, $startRow_trace, $maxRows_trace);
$trace = mysql_query($query_limit_trace, $conn) or die(mysql_error());
$row_trace = mysql_fetch_assoc($trace);

if (isset($_GET['totalRows_trace'])) {
  $totalRows_trace = $_GET['totalRows_trace'];
} else {
  $all_trace = mysql_query($query_trace);
  $totalRows_trace = mysql_num_rows($all_trace);
}
$totalPages_trace = ceil($totalRows_trace / $maxRows_trace) - 1;


$query_vehicle = "SELECT * FROM vehicles";
$vehicle = mysql_query($query_vehicle, $conn) or die(mysql_error());
$row_vehicle = mysql_fetch_assoc($vehicle);
$totalRows_vehicle = mysql_num_rows($vehicle);

$colname_t_edit = "-1";
if (isset($_GET['id']) && isset($_GET['edit'])) {
  $colname_t_edit = $_GET['id'];
}

$query_t_edit = sprintf("SELECT * FROM trace WHERE id = %s", GetSQLValueString($colname_t_edit, "int"));
$t_edit = mysql_query($query_t_edit, $conn) or die(mysql_error());
$row_t_edit = mysql_fetch_assoc($t_edit);
$totalRows_t_edit = mysql_num_rows($t_edit);

$queryString_trace = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (
      stristr($param, "pageNum_trace") == false &&
      stristr($param, "totalRows_trace") == false
    ) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_trace = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_trace = sprintf("&totalRows_trace=%d%s", $totalRows_trace, $queryString_trace);

$query_locations = "SELECT * FROM locations";
$locations = mysql_query($query_locations, $conn) or die(mysql_error());
$row_locations = mysql_fetch_assoc($locations);
$totalRows_locations = mysql_num_rows($locations);

$query_user = "SELECT * FROM users";
$user = mysql_query($query_user, $conn) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

include_once('../header.php');
?>


<?php if (isset($_GET['add']) || isset($_GET['edit'])) { ?>
  <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
    <table align="center">
      <tr valign="baseline">
        <td nowrap align="right">المركبة</td>
        <td><select class="form-control" name="vehicle">
            <?php
            do {
            ?>
              <option value="<?php echo $row_vehicle['id'] ?>" <?php if (!(strcmp($row_vehicle['id'], htmlentities($row_t_edit['vehicle'], ENT_COMPAT, 'UTF-8')))) {
                                                                  echo "SELECTED";
                                                                } ?>><?php echo $row_vehicle['name'] ?></option>
            <?php
            } while ($row_vehicle = mysql_fetch_assoc($vehicle));
            ?>
          </select></td>
      <tr>
      <tr valign="baseline">
        <td nowrap align="right">المستخدم</td>
        <td><select class="form-control" name="user">
            <?php
            do {
            ?>
              <option value="<?php echo $row_user['id'] ?>" <?php if (!(strcmp($row_user['id'], htmlentities($row_t_edit['user'], ENT_COMPAT, 'UTF-8')))) {
                                                              echo "SELECTED";
                                                            } ?>><?php echo $row_user['name'] ?></option>
            <?php
            } while ($row_user = mysql_fetch_assoc($user));
            ?>
          </select></td>
      <tr>
      <tr valign="baseline">
        <td nowrap align="right">الموقع الحالي</td>
        <td>
          <select class="form-control" name="cur_loc">
            <?php
            do {
            ?>
              <option value="<?php echo $row_locations['id'] ?>" <?php if (!(strcmp($row_locations['id'], htmlentities($row_v_edit['location'], ENT_COMPAT, 'UTF-8')))) {
                                                                    echo "SELECTED";
                                                                  } ?>><?php echo $row_locations['name'] ?></option>
            <?php
            } while ($row_locations = mysql_fetch_assoc($locations));
            ?>
          </select>
        </td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">
          <a href="?" class="btn btn-warning">
            <i class="fas fa-eye-slash"></i>
          </a>
        </td>
        <?php if (isset($_GET['edit'])) { ?>
          <td><input class="btn btn-info w-100" type="submit" value="تعديل"></td>
          <input class="btn" type="hidden" name="MM_update" value="form2">
        <?php } else { ?>
          <td><input class="btn btn-success w-100" type="submit" value="إضافة"></td>
          <input class="btn" type="hidden" name="MM_insert" value="form2">
        <?php } ?>
      </tr>
    </table>
    <input class="form-control" type="hidden" name="id" value="<?php echo $row_t_edit['id']; ?>">
  </form>

<?php } ?>
<div class="d-print-none my-2">
  <a href="report.php" class="btn btn-secondary"> تقرير</a>
  <a href="tracer.php" class="btn btn-warning">مخطط</a>
</div>
<table class="table table-bordered table-responsive">
  <thead>
    <tr>
      <th>#</th>
      <th>المركبة</th>
      <th>المستخدم</th>
      <th>الموقع</th>
      <th>التاريخ</th>
      <th>الاجراء</th>
    </tr>
  </thead>
  <?php
  if ($totalRows_trace > 0) {
    do { ?>
      <tr>
        <td><?php echo $row_trace['id']; ?></td>
        <td><?php echo $row_trace['vehicle']; ?></td>

        <td><?php echo $row_trace['user']; ?></td>
        <td><?php echo $row_trace['cur_loc']; ?></td>
        <td><?php echo $row_trace['created']; ?></td>
        <td>
          <a class="btn btn-link" href="index.php?add=true"><i class="fas fa-plus text-secondary"></i></a>
          <a class="btn btn-link" href="index.php?edit=true&id=<?php echo $row_trace['id']; ?>"><i class="fas fa-edit text-info"></i></a>
          <a class="btn btn-link" href="index.php?delete=true&id=<?php echo $row_trace['id']; ?>"><i class="fas fa-trash text-danger"></i></a>
        </td>
      </tr>
    <?php } while ($row_trace = mysql_fetch_assoc($trace));
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
    <td><?php if ($pageNum_trace > 0) { // Show if not first page 
        ?>
        <a href="<?php printf("%s?pageNum_trace=%d%s", $currentPage, 0, $queryString_trace); ?>">الأول</a>
      <?php } // Show if not first page 
      ?>
    </td>
    <td><?php if ($pageNum_trace > 0) { // Show if not first page 
        ?>
        <a href="<?php printf("%s?pageNum_trace=%d%s", $currentPage, max(0, $pageNum_trace - 1), $queryString_trace); ?>">السابق</a>
      <?php } // Show if not first page 
      ?>
    </td>
    <td><?php if ($pageNum_trace < $totalPages_trace) { // Show if not last page 
        ?>
        <a href="<?php printf("%s?pageNum_trace=%d%s", $currentPage, min($totalPages_trace, $pageNum_trace + 1), $queryString_trace); ?>">التالي</a>
      <?php } // Show if not last page 
      ?>
    </td>
    <td><?php if ($pageNum_trace < $totalPages_trace) { // Show if not last page 
        ?>
        <a href="<?php printf("%s?pageNum_trace=%d%s", $currentPage, $totalPages_trace, $queryString_trace); ?>">الأخير</a>
      <?php } // Show if not last page 
      ?>
    </td>
  </tr>
</table>
<?php
include_once('../footer.php');
mysql_free_result($trace);

mysql_free_result($vehicle);

mysql_free_result($t_edit);
?>