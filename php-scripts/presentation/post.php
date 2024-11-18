<?php
require_once("./../config/conn.php");

if(isset($_POST["title"]) && isset($_POST["code"])) {
    session_start();

    if(!isset($_SESSION["id"])) {
        echo "{'status': '0', 'message': 'nie zalogowano'}";
        exit();
    }

    $title = $_POST["title"];
    $code = $_POST["code"];

    $sql = "INSERT INTO `presentation`(id, title, code, userId, uploadDate) VALUES (DEFAULT, ?, ?, ?, DEFAULT)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title,  $code, $_SESSION["id"]);
    $stmt->execute();

    echo "{'status': '1', 'message': 'dodano'}";
}
else
    echo "{'status': '0', 'message': 'dane niepoprawne'}";
