<?php

include "connection_db.php";
session_start();

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
    } else {
        // Check if the user_id exists in the users table
        $check_user = $mysqli->prepare('SELECT id FROM users WHERE id = ?');
        $check_user->bind_param('i', $user_id);
        $check_user->execute();
        $check_user->store_result();
        $num_users = $check_user->num_rows();

        if ($num_users == 0) {
            $response['status'] = "Invalid user ID";
        } else {
            // Insert the user into the user_rooms table
            $datetime_left = null; 
            $query = $mysqli->prepare('INSERT INTO user_rooms(user_id, room_id, datetime_entered, datetime_left, bed_id) VALUES(?,?,?,?,?)');
            $query->bind_param('iissi', $user_id, $room_id, date('Y-m-d'), $datetime_left, $bed_id);
            $query->execute();

            // Check the cost day of the room

            $check_cost = $mysqli->prepare('SELECT cost_day_usd 
            FROM rooms r 
            JOIN user_rooms ur ON r.id = ur.room_id 
            WHERE ur.user_id = ?');
            $check_cost->bind_param('i', $user_id);
            $check_cost->execute();
            $result3 = $check_cost->get_result();

            while($row = $result3 -> fetch_array(MYSQLI_NUM)) {
                $_SESSION['amount'] += $row[0];
            }

            // Update the cost of the room into the invoices
            $query2 = $mysqli -> prepare('UPDATE invoices SET total_amount = ? WHERE user_id = ?');
            $query2->bind_param("si", $_SESSION['amount'], $user_id);
            $query2->execute();
            $result2 = $query2->get_result();


            if ($mysqli->errno != 0) {
                $response['status'] = "Database error: " . $mysqli->error;
            } else {
                $response['status'] = "success";
            }
        }
    }
}

echo json_encode($response);

?>



