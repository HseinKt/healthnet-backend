<?php

include "connection_db.php";
session_start();
// Initialize the variable to hold the amount value
$amount = 0;

$user_id = $_POST['user_id'];

$query = $mysqli -> prepare('SELECT  u.name, m.name, m.cost, um.quantity, u.id
FROM medications m
JOIN users_medications um ON m.id = um.medication_id 
JOIN users u ON u.id = um.user_id 
WHERE user_id = ?');

$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

$response = [];

while($row = $result->fetch_array(MYSQLI_NUM)) {
    $arr = [];
    $arr['name'] = $row[0];
    $arr['name_medication'] = $row[1];
    $arr['cost'] = $row[2];
    $arr['quantity'] = $row[3];
    $arr['user_id'] = $row[4];
    $arr['amount'] = $row[2] * $row[3];
    $amount = $arr['amount'];

    $_SESSION['amount'] += $amount;

    // Update the amount medications into the invoices
    $query2 = $mysqli -> prepare('UPDATE invoices SET total_amount = ? WHERE user_id = ?');
    $query2->bind_param("si", $_SESSION['amount'], $user_id);
    $query2->execute();
    $result2 = $query2->get_result();
    
    array_push($response, $arr);
}

echo json_encode($response);


?>


