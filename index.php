<?php
include 'Connections/conn.php';

if (isset($_POST['login'])) {

  $name = $_POST['name'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE name = '$name' AND password = '$password'";
  $result = mysql_query($sql, $conn) or die(mysql_error());

  if (mysql_num_rows($result)) {
    //  get user info .......
    $row = mysql_fetch_assoc($result);
    $_SESSION['user'] = $row;
    header('Location: admin/index.php');
  } else {
    echo "<script>
    alert('الحساب غير متاح - ".$sql."');
    </script>";
  }
}
?>

<!doctype html>
<html dir='rtl' lang="ar">

<head>
  <meta charset="utf-8">
  <title>truck</title>
  <link rel="stylesheet" href="./assets/css/mdb.rtl.min.css">
</head>

<body class="bg-success">


  <div class="card w-25 mx-auto bg-light mt-5 pt-5 d-flex justify-content-center">
    <form class="card-body form" action="index.php" method="POST" name="loginForm">
      <div class="text-center">
        <img src="assets/img/logo.png" class="w-75 h-75">
      </div>
      <div class="form-group">

        <label class="control-label" for="name">الاسم</label>
        <input class="form-control"type="text" class="form-control" id="name" name="name" placeholder="Enter name">

      </div>
      <div class="form-group">

        <label class="control-label" for="pwd">كلمة السر</label>
        <input class="form-control"name="password" type="password" class="form-control" id="pwd" placeholder="Enter password">
      </div>

      <div class="form-group">
        <button name="login" type="submit" value="login" class="btn btn-success w-100 my-3">دخول</button>
      </div>
    </form>

  </div>
</body>

</html>