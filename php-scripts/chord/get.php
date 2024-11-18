<?php
require_once("./../config/conn.php");

$sql = "SELECT * FROM `chord`";

$stmt = $conn->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0) {
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($rows as &$row) {
        if (isset($row['chords'])) {
            $row['chords'] = json_decode($row['chords'], true);
        }
    }

    unset($row);

    echo json_encode($rows, JSON_UNESCAPED_UNICODE);
}
else {
    echo json_encode(null, JSON_UNESCAPED_UNICODE);
}