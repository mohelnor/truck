<?php
//include Conn
require '../db.php';
require '../mysql/index.php';

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $user = $postdata["user"];
    $password = $postdata["password"] = md5($postdata["password"]);

    // query builder
    $sql = "SELECT * FROM `users` WHERE user = '$phone' or password = '$password'";
    $result = query_result($sql, $conn);

    // account already exists.
    $res['error'] = 100;

    if ($result[0] == 0) {
        // account can't be created.
        $res['error'] = 200;
        $result = insert($table, $postdata, $conn);
        $res['res'] = $result;
    }

} else {
    // error in your postdata
    $res['error'] = 400;
}

echo json_encode($res);
mysqli_close($conn);
