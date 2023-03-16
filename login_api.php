<?php
// include the JWT library
require_once 'C:\xampp\htdocs\php-jwt\php-jwt\src\JWT.php';
include "connection_db.php";
use firebase\JWT\JWT;

session_start();

$email = $_POST['email'];
$password = $_POST['password'];
$usertype_id = 1;

// query the database
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

        // Generate a JWT 
        $data = array(
            "user_id" => $id,
            "name" => $name,
            "email" => $email,
            "usertype_id" => $usertype_id,
            "amount" => 0
        );
        $secret_key = "331HK";
        
        $jwt = JWT::encode($data, $secret_key,'HS256');

        $response['response'] = "logged in";
        $response['jwt'] = $jwt;
        
    }
    else {
        $response['response'] = "Incorrect password";
    }
}

echo json_encode($response);

?>


