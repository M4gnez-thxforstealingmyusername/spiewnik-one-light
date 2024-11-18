<?php
require_once("./../config/conn.php");

if(isset($_POST["id"])) {
    session_start();

    if(!isset($_SESSION["id"])) {
        echo "{'status': '0', 'message': 'nie zalogowano'}";
        exit();
    }

    if($_SESSION["level"] < 1) {
        echo "{'status': '0', 'message': 'poziom niewystarczający'}";
        exit();
    }

    $id = $_POST["id"];
    if(isset($_POST["chords"]))
        $chords = $_POST["chords"];
    else
        $chords = null;

    $sql = "INSERT INTO `chord`(id, chords, userId) VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $id,  $chords, $_SESSION["id"]);
    $stmt->execute();

    echo "{'status': '1', 'message': 'dodano'}";
}
else
    echo "{'status': '0', 'message': 'dane niepoprawne'}";
