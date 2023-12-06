<?php
require '../../db.php';
require '../../mysql/index.php';
require '../../functions.php';

if (isset($postdata) && !empty($postdata)) {
    // Extract the data.
    $table = $postdata["table"];
    $data = is_assoc($postdata["data"]);
    $result = insertAll($table, $data, $conn);

    $res['msg'] = 'Error , IN your syntac , check ur params';

    if ($result) {
        $res['msg'] = $result;
    }
    
} else {
    $res['msg'] = 'Error , Didn\'t receive data ..';
}
echo json_encode($result);
mysqli_close($conn);
