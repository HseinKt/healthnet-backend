<?php

include "connection_db.php";

$user_id = $_POST['user_id'];

$query = $mysqli -> prepare('SELECT  u.name, m.name, m.cost, um.quantity
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

    array_push($response, $arr);
}

echo json_encode($response);

?>


