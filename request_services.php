<?php

include "connection_db.php";
session_start();

$response = [];

// Check if the required fields are set
if (!isset($_POST['patient_id'], $_POST['employee_id'], $_POST['department_id'], $_POST['description'], $_POST['cost'])) {
    $response["status"] = "Missing required fields.";
    echo json_encode($response);
    exit();
}

$patient_id = $_POST['patient_id'];
$employee_id = $_POST['employee_id'];
$department_id = $_POST['department_id'];
$description = $_POST['description'];
$cost = $_POST['cost'];

$query = $mysqli->prepare('INSERT INTO services (employee_id, patient_id, description, cost, department_id, status) VALUES (?, ?, ?, ?, ?, "pending")');
$query->bind_param('iissi', $employee_id, $patient_id, $description, $cost, $department_id);
$result = $query->execute();

if ($result) {
    $response["status"] = "Added request successfully.";
    // Store the amount value in a session variable
    $_SESSION['patient_id'] = $patient_id;
    $_SESSION['amount'] += $cost;
}
else {
    $response["status"] = "request failed";
}
echo json_encode($response);


?>