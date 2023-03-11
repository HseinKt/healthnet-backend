<?php

include "connection_db.php";

$display_medication = $mysqli -> query('SELECT * FROM medications');

$response = [];

while ($row = $display_medication -> fetch_array(MYSQLI_NUM)) {
    $arr = [];
    $arr['id'] = $row[0];
    $arr['name'] = $row[1];
    $arr['cost'] = $row[2];

    array_push($response, $arr);
}

echo json_encode($response);

?>



<!-- 

$user_id = $_GET['user_id'];
$medication_id = $_GET['medication_id'];

'SELECT *
FROM medications m
JOIN users_medications um ON m.id = um.medication_id 
JOIN users u ON u.id = um.user_id
WHERE u.id = ?'); 

$display_medication->bind_param('i', $user_id);
$display_medication->execute();
$display_medication->store_result();
$num_rows = $display_medication->num_rows();

$query = $mysqli->prepare('insert into users(name,email,password,dob,usertype_id ) values(?,?,?,?,?)');
    $query->bind_param('ssssi', $name, $email, $hashed_password, $dob, $usertype_id );
    $query->execute();
    $response['status'] = "success";
-->