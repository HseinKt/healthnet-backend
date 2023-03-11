<?php

include("connection_db.php");

$user_id = $_POST['user_id'];

$sql_query = $mysqli -> prepare('SELECT total_amount FROM invoices WHERE user_id = ?');
$sql_query -> bind_param('i', $user_id);
$sql_query -> execute();
$result = $sql_query -> get_result();
$response = [];

while($row = $result -> fetch_array(MYSQLI_NUM)) {
    $arr = [];
    $arr['user_id'] = $row[0];

    array_push($response, $arr);
}

echo json_encode($response);

?>


