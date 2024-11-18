<?php
require_once("./../config/conn.php");

if(isset($_POST["id"])) {
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

    $sql = "DELETE FROM `song` WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo "{'status': '1', 'message': 'usunięto'}";
}
else
    echo "{'status': '0', 'message': 'dane niepoprawne'}";
