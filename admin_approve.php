<?php

include "connection_db.php";

$service_id = $_POST['service_id'];

$query = $mysqli->prepare('UPDATE services SET status = "approve" WHERE id = ?');
$query->bind_param('i', $service_id);
$result = $query->execute();
$response = [];

if ($result) {
    $response["status"] = "admin approved successfully.";
}
else {
    $response["status"] = "admin approved failed";
}
echo json_encode($response);

?>