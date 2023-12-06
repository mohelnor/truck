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

$query_users = "SELECT
`id`,
`name`,
`password`,
`phone`,
`address`,
(SELECT name from locations WHERE id = `location`) AS `location`,
`permit`,
`created`
FROM
`users`";
$colname_users = "-1";
if (isset($_GET['permit'])) {
  $colname_users = $_GET['permit'];
  $query_users = sprintf("SELECT
  `id`,
  `name`,
  `password`,
  `phone`,
  `address`,
  (SELECT name from locations WHERE id = `location`) AS `location`,
  `permit`,
  `created`
FROM
  `users` WHERE permit = %s", GetSQLValueString($colname_users, "text"));
}

$users = mysql_query($query_users, $conn) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);
include_once('../header.php');
?>

<div class="d-print-none mb-1">
  <a class="btn" href="report.php">الكل</a>
  <a class="btn" href="?permit=admin">المشرف</a>
  <a class="btn" href="?permit=user">مستخدم</a>
  <a class="btn" href="?permit=driver">سائق</a>
  <button class="btn btn-dark" onclick="print()">طباعة</button>
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
  </thead>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_users['id']; ?></td>
      <td><?php echo $row_users['name']; ?></td>
      <td><?php echo $row_users['password']; ?></td>
      <td><?php echo $row_users['phone']; ?></td>
      <td><?php echo $row_users['address']; ?></td>
      <td><?php echo $row_users['location']; ?></td>
      <td><?php echo $row_users['permit']; ?></td>
      <td><?php echo $row_users['created']; ?></td>
    </tr>
  <?php } while ($row_users = mysql_fetch_assoc($users)); ?>
</table>
<?php
include_once('../footer.php');
mysql_free_result($users);
?>