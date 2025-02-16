<?php
    require_once "../../config.php";

    session_start();

    if(!$_SESSION["authorizationLevel"] == 4)
        header("Location: " . SERVER_ROOT);

    if(isset($_POST["id"], $_POST["authorizationLevel"]))
        User::setAuthorizationLevel($_POST["id"], $_POST["authorizationLevel"]);

    header("Location: " . SERVER_ROOT . "/user/admin.php/?panel=users");

?>