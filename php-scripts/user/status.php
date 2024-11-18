<?php
require_once("./../config/conn.php");
session_start();


$sql = "SELECT id, username, level FROM user where id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION["id"]);
$stmt->execute();

$result = $stmt->get_result();

if($row = $result->fetch_assoc()) {
    $userData = json_encode($row, JSON_UNESCAPED_UNICODE);
    $response = [
        'status' => 1,
        'message' => json_decode($userData)
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
}
else {
    echo "{'status': '0', 'message': 'nie zalogowano";
}
