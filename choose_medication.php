<?php

include "connection_db.php";

$user_id = $_POST['user_id'];
$medication_id = $_POST['medication_id'];
$quantity = $_POST['quantity'];

$choose_medication = $mysqli -> prepare('INSERT INTO users_medications (user_id, medication_id, quantity) VALUES(?,?,?)');
$choose_medication->bind_param('iis', $user_id, $medication_id, $quantity);
$result = $choose_medication->execute();

$response = [];

if ($result) {
    $response["status"] = "added succefuly";
}
else {
    $response["status"] = "can't add to users medications";
}
echo json_encode($response);

?>

