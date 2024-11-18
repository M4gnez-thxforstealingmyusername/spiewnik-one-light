<?php
require_once("./../config/conn.php");

$sql = "SELECT id, username, joinDate, cityId, level FROM `user`";

$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0) {
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
}
else {
    echo json_encode(null, JSON_UNESCAPED_UNICODE);
}