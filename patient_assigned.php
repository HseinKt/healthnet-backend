<?php

include "connection_db.php";

$hospital_id = $_POST['hospital_id'];
$user_id = $_POST['user_id'];
$is_active = $_POST['is_active'];
$date_joined = date('Y-m-d');
$date_left = date('Y-m-d');
// $usertype_id = $_POST['usertype_id'];


try {
    // Check if the user exists
    $check_ID = $mysqli->prepare('SELECT u.id, t.id 
    FROM users u 
    JOIN hospital_users hu ON u.id = hu.user_id 
    JOIN hospitals h ON h.id = hu.hospital_id 
    JOIN user_types t ON t.id = u.usertype_id 
    WHERE u.id = ?');       
    $check_ID->bind_param('i', $user_id);
    $check_ID->execute();
    $check_ID->store_result();
    $num_rows = $check_ID->num_rows();
    
    $response = [];

    if ($num_rows > 0) {
        $response['status'] = "patient already has an ID";
    }else {
        $check_type = $mysqli->prepare('SELECT u.usertype_id
        FROM users u  
        JOIN user_types t ON t.id = u.usertype_id 
        WHERE u.id = ? AND u.usertype_id = 1');
        $check_type->bind_param('i', $user_id);
        $check_type->execute();
        $check_type->store_result();
        $rows = $check_type->num_rows();

        if ($rows > 0){
            $sql = "insert into hospital_users(hospital_id, user_id , is_active, date_joined, date_left) values(?,?,?,?,?) ";
            $query = $mysqli -> prepare($sql);
            $query -> bind_param('iisss',$hospital_id, $user_id , $is_active, $date_joined, $date_left);
            $query->execute();
            $response['status'] = "success";
        }else {
            $response['status'] = "Sorry this id belongs to an employee";
        }
    }
} catch (exception $e) {
    $response['status'] = $e->getMessage();
}
echo json_encode($response);

?>
