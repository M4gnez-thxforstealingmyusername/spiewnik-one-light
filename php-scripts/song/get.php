<?php
require_once("./../config/conn.php");

$sql = "SELECT * FROM `song`";

$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0) {
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($rows as &$row) {
        if (isset($row['lyrics'])) {
            $row['lyrics'] = json_decode($row['lyrics'], true);
        }
    }

    unset($row);

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
}
else {
    echo json_encode(null, JSON_UNESCAPED_UNICODE);
}