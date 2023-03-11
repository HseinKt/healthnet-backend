<?php

include "connection_db.php";
session_start();

$service_id = $_POST['service_id'];

$query = $mysqli->prepare('UPDATE services SET status = "approve" WHERE id = ?');
$query->bind_param('i', $service_id);
$result = $query->execute();
$response = [];

if ($result) {
    $response["status"] = "admin approved successfully.";

    // Update the amount of services into the invoices
    $query2 = $mysqli -> prepare('UPDATE invoices SET total_amount = ? WHERE user_id = ?');
    $query2->bind_param("si", $_SESSION['amount'], $_SESSION['patient_id']);
    $query2->execute();
    $result2 = $query2->get_result();
}
else {
    $response["status"] = "admin approved failed";
}
echo json_encode($response);

?>