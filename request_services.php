<?php

include "connection_db.php";

$patient_id = $_POST['patient_id'];
$employee_id = $_POST['employee_id'];
$department_id = $_POST['department_id'];
$description = $_POST['description'];
$cost = $_POST['cost'];

$query = $mysqli->prepare('INSERT INTO services (employee_id, patient_id, description, cost, department_id, status) VALUES (?, ?, ?, ?, ?, "pending")');
$query->bind_param('iissi', $employee_id, $patient_id, $description, $cost, $department_id);
$result = $query->execute();

$response = [];

if ($result) {
    $response["status"] = "Added request successfully.";
}
else {
    $response["status"] = "request failed";
}
echo json_encode($response);

?>



<!-- 

include "connection_db.php";

$response = [];


