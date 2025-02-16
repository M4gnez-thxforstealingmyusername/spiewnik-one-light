<?php
    require_once "../../config.php";

    session_start();

    if(!$_SESSION["authorizationLevel"] == 4)
        header("Location: " . SERVER_ROOT);

    Presentation::clear();

    header("Location: " . SERVER_ROOT . "/user/admin.php/?panel=clearPresentations");

?>