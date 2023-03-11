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
