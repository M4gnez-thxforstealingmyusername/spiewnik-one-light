<?php
class City {

    static function getAll() {
        return Conn::conn()->query("SELECT * FROM `city`")->fetch_all(MYSQLI_ASSOC);
    }

    static function getName($id) {
        $stmt = Conn::conn()->prepare("SELECT * FROM `city` WHERE `id` = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc()["cityName"];
    }

    static function getResult() {
        return Conn::conn()->query("SELECT * FROM `city`");
    }

}