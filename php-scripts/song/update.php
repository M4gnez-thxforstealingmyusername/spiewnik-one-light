<?php
require_once("./../config/conn.php");

if(isset($_POST["id"]) && isset($_POST["title"]) && isset($_POST["lyrics"])) {
    session_start();

    if(!isset($_SESSION["id"])) {
        echo "{'status': '0', 'message': 'nie zalogowano'}";
        exit();
    }

    if($_SESSION["level"] < 2) {
        echo "{'status': '0', 'message': 'poziom niewystarczający'}";
        exit();
    }

    $id = $_POST["id"];
    $title = $_POST["title"];
    $lyrics = $_POST["lyrics"];


    $sql = "UPDATE `song` SET title = ?, lyrics = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $lyrics, $id);
    $stmt->execute();

    echo "{'status': '1', 'message': 'zmieniono'}";
}
else
    echo "{'status': '0', 'message': 'dane niepoprawne'}";
