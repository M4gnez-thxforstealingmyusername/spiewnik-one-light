<?php
require_once("./../config/conn.php");
session_start();

if(isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            if(password_verify($password, $row["password"]))
            {
                $_SESSION["id"] = $row["id"];
                $_SESSION["level"] = $row["level"];

                echo "{'status': '1', 'message': 'zalogowano'}";
            }
            else
            echo "{'status': '0', 'message': 'hasło niepoprawne'}";
        }
    }
    else
        echo "{'status': '0', 'message': 'użytkownik nie istnieje'}";
}
else
    echo "{'status': '0', 'message': 'niewystarczające dane logowania'}";
