<?php
class User {

    static function getAll() {
        return Conn::conn()->query("SELECT id, displayName, cityId, joinDate, authorizationLevel FROM `user`")->fetch_all(MYSQLI_ASSOC);
    }

    static function getOne($id) {
        $stmt = Conn::conn()->prepare("SELECT id, displayName, cityId, joinDate, authorizationLevel FROM `user` WHERE `id` = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    static function getName($id) {
        $stmt = Conn::conn()->prepare("SELECT displayName FROM `user` WHERE `id` = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc()["displayName"];
    }

    static function getNames() {
        return Conn::conn()->query("SELECT id, displayName FROM `user`")->fetch_all(MYSQLI_ASSOC);
    }

    static function getResult() {
        return Conn::conn()->query("SELECT id, displayName, cityId, joinDate, authorizationLevel FROM `user`");
    }

    static function getPrivate($id) {
        $stmt = Conn::conn()->prepare("SELECT id, `login`, displayName, cityId, joinDate, authorizationLevel  FROM `user` WHERE `id` = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc()["displayName"];
    }

    static function register($login, $displayName, $password, $cityId) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = Conn::conn()->prepare("SELECT id FROM `user` WHERE login = ?");

        $stmt->bind_param("s", $login);

        $stmt->execute();

        if($stmt->get_result()->num_rows > 0)
            return 2;

        $stmt = Conn::conn()->prepare("INSERT INTO `user` values (DEFAULT, ?, ?, ?, ?, DEFAULT, DEFAULT)");

        $stmt->bind_param("sssi", $login, $displayName, $passwordHash, $cityId);

        $result = $stmt->execute() ? 1 : 0;

        User::login($login, $password);

        return $result;
    }

    static function login($login, $password) {
        $stmt = Conn::conn()->prepare("SELECT id, `password`, authorizationLevel FROM `user` WHERE login = ?");

        $stmt->bind_param("s", $login);

        $stmt->execute();

        $result = $stmt->get_result();

        if($result->num_rows == 0)
            return false;


        $user = $result->fetch_assoc();

        $passwordHash = $user["password"];

        if(!password_verify($password, $passwordHash))
            return false;

        session_start();
        $_SESSION["id"] = $user["id"];
        $_SESSION["authorizationLevel"] = $user["authorizationLevel"];

        return true;
    }

    static function logout() {
        session_start();
        session_unset();
        session_destroy();
    }

    static function ping() {
        session_start();
        return isset($_SESSION["id"]);
    }
}