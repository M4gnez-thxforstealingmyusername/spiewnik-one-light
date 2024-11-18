<?php
require_once("./../config/conn.php");

if(isset($_POST["title"]) && isset($_POST["lyrics"])) {
    session_start();

    if(!isset($_SESSION["id"])) {
        echo "{'status': '0', 'message': 'nie zalogowano'}";
        exit();
    }

    if($_SESSION["level"] < 1) {
        echo "{'status': '0', 'message': 'poziom niewystarczający'}";
        exit();
    }

    $title = $_POST["title"];
    $lyrics = $_POST["lyrics"];

    $sql = "INSERT INTO `song`(id, title, lyrics, userId, uploadDate) VALUES (DEFAULT, ?, ?, ?, DEFAULT)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title,  $lyrics, $_SESSION["id"]);
    $stmt->execute();


    $sql = "SELECT id FROM `song` order by id DESC LIMIT 1;";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();

    $id;

    if($row = $result->fetch_assoc()) {
        $id = $row["id"];
    }

    echo "{'status': '1', 'message': 'dodano', id:" . $id . "}";
}
else
    echo "{'status': '0', 'message': 'dane niepoprawne'}";
