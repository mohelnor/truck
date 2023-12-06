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
    "INSERT INTO users (name, password, phone, address,location, permit) VALUES (%s, %s, %s,%s, %s, %s)",
    GetSQLValueString($_POST['name'], "text"),
    GetSQLValueString($_POST['password'], "text"),
    GetSQLValueString($_POST['phone'], "text"),
    GetSQLValueString($_POST['address'], "text"),
    GetSQLValueString($_POST['location'], "text"),
    GetSQLValueString($_POST['permit'], "text")
  );


  $Result1 = mysql_query($insertSQL, $conn) or die(mysql_error());

  $insertGoTo = "index.php";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf(
    "UPDATE users SET name=%s, password=%s, phone=%s, address=%s, location=%s, permit=%s WHERE id=%s",
    GetSQLValueString($_POST['name'], "text"),
    GetSQLValueString($_POST['password'], "text"),
    GetSQLValueString($_POST['phone'], "text"),
    GetSQLValueString($_POST['address'], "text"),
    GetSQLValueString($_POST['location'], "text"),
    GetSQLValueString($_POST['permit'], "text"),
    GetSQLValueString($_POST['id'], "int")
  );


  $Result1 = mysql_query($updateSQL, $conn) or die(mysql_error());

  $updateGoTo = "index.php";
  header(sprintf("Location: %s", $updateGoTo));
}

if (isset($_GET['id']) && isset($_GET['delete']) && $_GET['id'] != "") {
  $deleteSQL = sprintf(
    "DELETE FROM users WHERE id=%s",
    GetSQLValueString($_GET['id'], "int")
  );


  $Result1 = mysql_query($deleteSQL, $conn) or die(mysql_error());

  $deleteGoTo = "index.php";
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_users = 10;
$pageNum_users = 0;
if (isset($_GET['pageNum_users'])) {
  $pageNum_users = $_GET['pageNum_users'];
}
$startRow_users = $pageNum_users * $maxRows_users;

$query_users = "SELECT
`id`,
`name`,
`password`,
`phone`,
`address`,
 `location` AS l_id,
(SELECT name FROM locations WHERE id = `location`) AS `location`,
`permit`,
`created`
FROM
`users`";

// if (isset($_GET['id'])) {
//   $id = $_GET['id'];
//   $query_users = sprintf("SELECT * FROM users WHERE id = %s", GetSQLValueString($id, "int"));
// }

$query_limit_users = sprintf("%s LIMIT %d, %d", $query_users, $startRow_users, $maxRows_users);
$users = mysql_query($query_limit_users, $conn) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);

if (isset($_GET['totalRows_users'])) {
  $totalRows_users = $_GET['totalRows_users'];
} else {
  $all_users = mysql_query($query_users);
  $totalRows_users = mysql_num_rows($all_users);
}
$totalPages_users = ceil($totalRows_users / $maxRows_users) - 1;

$colname_u_edit = "-1";
if (isset($_GET['id']) && isset($_GET['edit'])) {
  $colname_u_edit = $_GET['id'];
}

$query_u_edit = sprintf("SELECT * FROM users WHERE id = %s", GetSQLValueString($colname_u_edit, "int"));
$u_edit = mysql_query($query_u_edit, $conn) or die(mysql_error());
$row_u_edit = mysql_fetch_assoc($u_edit);
$totalRows_u_edit = mysql_num_rows($u_edit);

$queryString_users = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (
      stristr($param, "pageNum_users") == false &&
      stristr($param, "totalRows_users") == false
    ) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_users = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_users = sprintf("&totalRows_users=%d%s", $totalRows_users, $queryString_users);

$query_locations = "SELECT * FROM locations";
$locations = mysql_query($query_locations, $conn) or die(mysql_error());
$row_locations = mysql_fetch_assoc($locations);
$totalRows_locations = mysql_num_rows($locations);

include_once('../header.php');
?>


<?php if (isset($_GET['add']) || isset($_GET['edit'])) { ?>
  <form method="post" name="form2" action="<?php echo $editFormAction; ?>">
    <table align="center">
      <tr valign="baseline">
        <td nowrap align="right">الاسم</td>
        <td><input class="form-control" type="text" name="name" value="<?php echo htmlentities($row_u_edit['name'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">كلمة السر</td>
        <td><input class="form-control" type="text" name="password" value="<?php echo htmlentities($row_u_edit['password'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">الهاتف</td>
        <td><input class="form-control" type="text" name="phone" value="<?php echo htmlentities($row_u_edit['phone'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">العنوان</td>
        <td><input class="form-control" type="text" name="address" value="<?php echo htmlentities($row_u_edit['address'], ENT_COMPAT, 'UTF-8'); ?>" size="32"></td>
      </tr>
      <tr valign="baseline">
        <td nowrap align="right">الموقع</td>
        <td>
          <select class="form-control" name="location">
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
        <td nowrap align="right">الصلاحية</td>
        <td><select class="form-control" name="permit">
            <option value="admin" <?php if (!(strcmp("admin", htmlentities($row_u_edit['permit'], ENT_COMPAT, 'UTF-8')))) {
                                    echo "SELECTED";
                                  } ?>>المشرف</option>
            <option value="inner" <?php if (!(strcmp("inner", htmlentities($row_u_edit['permit'], ENT_COMPAT, 'UTF-8')))) {
                                    echo "SELECTED";
                                  } ?>>مشرف ادخال</option>
            <option value="outter" <?php if (!(strcmp("outter", htmlentities($row_u_edit['permit'], ENT_COMPAT, 'UTF-8')))) {
                                      echo "SELECTED";
                                    } ?>>مشرف اخراج</option>
            <option value="driver" <?php if (!(strcmp("driver", htmlentities($row_u_edit['permit'], ENT_COMPAT, 'UTF-8')))) {
                                      echo "SELECTED";
                                    } ?>>السائق</option>
          </select></td>
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
    <input class="form-control" type="hidden" name="id" value="<?php echo $row_u_edit['id']; ?>">
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
      <th>كلمة السر</th>
      <th>الهاتف</th>
      <th>العنوان</th>
      <th>الموقع</th>
      <th>الصلاحية</th>
      <th>التاريخ</th>
      <th>الاجراء</th>
  </thead>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_users['id']; ?></td>
      <td><?php echo $row_users['name']; ?></td>
      <td><?php echo $row_users['password']; ?></td>
      <td><?php echo $row_users['phone']; ?></td>
      <td><?php echo $row_users['address']; ?></td>
      <td>
        <a href="../locations/index.php?id=<?php echo $row_users['l_id']; ?>">
          <?php echo $row_users['location']; ?>
        </a>
      </td>
      <td><?php echo $row_users['permit']; ?></td>
      <td><?php echo $row_users['created']; ?></td>
      <td>
        <a class="btn btn-link" href="index.php?add=true"><i class="fas fa-plus text-secondary"></i></a>
        <a class="btn btn-link" href="index.php?edit=true&id=<?php echo $row_users['id']; ?>"><i class="fas fa-edit text-info"></i></a>
        <a class="btn btn-link" href="index.php?delete=true&id=<?php echo $row_users['id']; ?>"><i class="fas fa-trash text-danger"></i></a>
      </td>
    </tr>
  <?php } while ($row_users = mysql_fetch_assoc($users)); ?>
</table>
<table border="0">
  <tr>
    <td><?php if ($pageNum_users > 0) { // Show if not first page 
        ?>
        <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, 0, $queryString_users); ?>">الأول</a>
      <?php } // Show if not first page 
      ?>
    </td>
    <td><?php if ($pageNum_users > 0) { // Show if not first page 
        ?>
        <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, max(0, $pageNum_users - 1), $queryString_users); ?>">السابق</a>
      <?php } // Show if not first page 
      ?>
    </td>
    <td><?php if ($pageNum_users < $totalPages_users) { // Show if not last page 
        ?>
        <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, min($totalPages_users, $pageNum_users + 1), $queryString_users); ?>">التالي</a>
      <?php } // Show if not last page 
      ?>
    </td>
    <td><?php if ($pageNum_users < $totalPages_users) { // Show if not last page 
        ?>
        <a href="<?php printf("%s?pageNum_users=%d%s", $currentPage, $totalPages_users, $queryString_users); ?>">الأخير</a>
      <?php } // Show if not last page 
      ?>
    </td>
  </tr>
</table>
<?php
include_once('../footer.php');
mysql_free_result($users);

mysql_free_result($u_edit);
?>