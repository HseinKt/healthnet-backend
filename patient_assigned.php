<?php

include "connection_db.php";

$hospital_id = $_POST['hospital_id'];
$user_id = $_POST['user_id'];
$is_active = $_POST['is_active'];
$date_joined = date('Y-m-d');
$date_left = date('Y-m-d');

try {
    // Check if the user exists
    $check_ID = $mysqli->prepare('SELECT u.id FROM users u JOIN hospital_users hu ON u.id = hu.user_id JOIN hospitals h ON h.id = hu.hospital_id WHERE user_id = ?');
    $check_ID->bind_param('i', $user_id);
    $check_ID->execute();
    $check_ID->store_result();
    $num_rows = $check_ID->num_rows();

    $response = [];

    if ($num_rows > 0) {
        $response['status'] = "patient already has an ID";
    }else {
        $sql = "insert into hospital_users(hospital_id, user_id , is_active, date_joined, date_left) values(?,?,?,?,?) ";
        $query = $mysqli -> prepare($sql);
        $query -> bind_param('iisss',$hospital_id, $user_id , $is_active, $date_joined, $date_left);
        $query->execute();
        $response['status'] = "success";
    }
} catch (exception $e) {
    $response['status'] = $e->getMessage();
}
echo json_encode($response);

?>
