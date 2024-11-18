<?php
require_once("./../config/conn.php");

if(isset($_POST["id"]) && isset($_POST["chords"])) {
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
    $chords = $_POST["chords"];


    $sql = "UPDATE `chord` SET chords = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $chords, $id);
    $stmt->execute();

    echo "{'status': '1', 'message': 'zmieniono'}";
}
else
    echo "{'status': '0', 'message': 'dane niepoprawne'}";
