<?php
$host_name = 'localhost';
$database = 'healthnet_db';
$username = 'root';
$password = '';

$mysqli = new mysqli($host_name, $username, $password, $database);

header("Access-Control-Allow-Origin: * ");
header("Access-Control-Allow-Headers:*");
if ($mysqli->connect_error) {
    die('<p>Failed to connect to MySQL: ' . $mysqli->connect_error . '</p>');
}


?>