<?php

include "connection_db.php";

$user_id = $_POST['user_id'];  //4
$department_id = $_POST['department_id']; //1
$room_id = $_POST['room_id']; //1
$bed_id = $_POST['bed_id']; //1

$check_room = $mysqli->prepare('SELECT *
FROM users u
JOIN user_rooms ur ON u.id = ur.user_id 
JOIN rooms r ON r.id = ur.room_id 
JOIN departments d ON d.id = r.department_id 
WHERE r.number_beds > 0 ');

$check_room->execute();
$check_room->store_result();
$num_rows = $check_room->num_rows();
$response = [];

$check_bed = $mysqli->prepare('SELECT *
FROM user_rooms ur
JOIN users u ON u.id = ur.user_id 
JOIN rooms r ON r.id = ur.room_id
WHERE ur.room_id = ? AND ur.bed_id = ?');
$check_bed->bind_param('ii',$room_id, $bed_id);
$check_bed->execute();
$check_bed->store_result();
$num_beds = $check_bed->num_rows();

if ($num_beds != 0 ) {
    $response['status'] = "this bed is not available";
} else {
    if ($num_rows == 0) {
        $response['status'] = "No available beds";
    }else {
        try {
            $query = $mysqli->prepare('INSERT INTO user_rooms(user_id, room_id,datetime_entered, datetime_left, bed_id) VALUES(?,?,?,?,?)');
            $datetime_left = null; 
            $query->bind_param('iissi', $user_id, $room_id, date('Y-m-d'), $datetime_left, $bed_id);
            $query->execute();
            $response['status'] = "success";
        } catch (exception $e) {
        $response['status'] = $e->getMessage();
    }
        
    }
}


echo json_encode($response);

?>



