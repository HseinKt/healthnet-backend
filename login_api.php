<?php
include "connection_db.php";
session_start();

$email = $_POST['email'];
$password = $_POST['password'];
$usertype_id = 1;

$query = $mysqli -> prepare("select * from users where email = ?");
$query -> bind_param('s',$email);
$query -> execute();
$query -> store_result();
$num_rows = $query -> num_rows();
$query -> bind_result($id, $name, $email, $hashed_password, $dob, $usertype_id );
$query -> fetch();
$response = [];

if ($num_rows == 0) {
    $response['response'] = "user not found";
}
else {
    if (password_verify($password,$hashed_password)) {
        $response['response'] = "logged in";
        $response['name'] = $name;
        $response['email'] = $email;
        $response['usertype_id'] = $usertype_id;  
        $_SESSION['amount'] = 0;      
    }
    else {
        $response['response'] = "Incorrect password";
    }
}
echo json_encode($response);

?>


