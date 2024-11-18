<?php
require_once("./../config/conn.php");
session_start();

if(isset($_POST["username"]) && isset($_POST["password"])  && isset($_POST["cityId"])){
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $cityId = $_POST["cityId"];

    $sql = "SELECT id FROM user where username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 0)  {

        $sql = "INSERT INTO `user`(id, username, password, joinDate, cityId, level) VALUES (DEFAULT, ?, ?, DEFAULt, ?, DEFAULT)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $cityId);
        $registered = $stmt->execute();

        if($registered) {
            $sql = "SELECT id, level FROM user where username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();

            $result = $stmt->get_result();

            $id;
            $level;

            if($row = $result->fetch_assoc()) {
                $id = $row["id"];
                $level = $row["level"];
            }

            $_SESSION["id"] = $id;
            $_SESSION["level"] = $level;
            echo "{'status': '1', 'message': 'zarejestrowano'}";
        }
    }
    else
        echo "{'status': '0', 'message': 'nazwa jest już zajęta'}";
}