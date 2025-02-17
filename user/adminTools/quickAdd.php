<?php
    require_once "../../config.php";

    session_start();

    if(!$_SESSION["authorizationLevel"] == 4)
        header("Location: " . SERVER_ROOT);

    if(isset($_POST["title"]) && isset($_POST["text"])) {
        Song::add($_POST["title"], $_SESSION["id"], str_replace("\r\n", "\n", $_POST["text"]), "");
        header("Location: " . SERVER_ROOT . "/song/?id=". Song::getTop()[0]["id"]);
    }
    // else
    //     header("Location: " . SERVER_ROOT . "/user/admin.php/?panel=quickAdd");

?>