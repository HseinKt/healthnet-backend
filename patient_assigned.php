<?php

include "connection_db.php";
session_start();
$_SESSION['amount'] = 0;

$hospital_id = $_POST['hospital_id'];
$user_id = $_POST['user_id'];
$is_active = $_POST['is_active'];
$date_joined = date('Y-m-d');
$date_left = null;

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

            // insert the default invoices to the new patient
            $query2 = $mysqli -> prepare("insert into invoices(user_id , hospital_id, total_amount, date_issued) values(?,?,?,?)");
            $query2 -> bind_param('iiss',$user_id, $hospital_id , $_SESSION['amount'], $date_joined);
            $query2 ->execute();

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
