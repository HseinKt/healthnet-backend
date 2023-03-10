<?php

include "connection_db.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$usertype_id = $_POST['usertype_id'];
$dob = $_POST['dob'];

$check_email = $mysqli->prepare('select email from users where email=?');
$check_email->bind_param('s', $email);
$check_email->execute();
$check_email->store_result();
$num_rows = $check_email->num_rows();

$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$response = [];

if ($num_rows > 0) {
    $response['status'] = "failed";
} else {
    $query = $mysqli->prepare('insert into users(name,email,password,dob,usertype_id ) values(?,?,?,?,?)');
    $query->bind_param('ssssi', $name, $email, $hashed_password, $dob, $usertype_id );
    $query->execute();
    $response['status'] = "success";
}

echo json_encode($response);

