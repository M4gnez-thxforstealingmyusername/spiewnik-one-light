<?php
    require "config.php";

    User::logout();

    header("Location: " . SERVER_ROOT);