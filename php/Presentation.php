<?php
class Presentation {

    static function getAll() {
        return Conn::conn()->query("SELECT * FROM `presentation`")->fetch_all(MYSQLI_ASSOC);
    }

    static function getOne($id) {
        $stmt = Conn::conn()->prepare("SELECT * FROM `presentation` WHERE `id` = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    static function getResult() {
        return Conn::conn()->query("SELECT * FROM `presentation`");
    }

    static function add($title, $userId, $songs, $isPermanent) {
        $stmt = Conn::conn()->prepare("INSERT INTO `presentation` values (DEFAULT, ?, ?, ?, DEFAULT, ?)");

        $stmt->bind_param("sisi", $title, $userId, $songs, $isPermanent);

        $stmt->execute();
    }

    static function update($id, $title, $songs, $isPermanent) {
        $stmt = Conn::conn()->prepare("UPDATE `presentation` SET `title` = ?, `songs` = ?, `isPermanent` = ? WHERE id = ?");

        $stmt->bind_param("ssii", $title, $songs, $isPermanent, $id);

        $stmt->execute();
    }

    static function delete($id) {
        $stmt = Conn::conn()->prepare("DELETE FROM `presentation` WHERE id = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();
    }

}