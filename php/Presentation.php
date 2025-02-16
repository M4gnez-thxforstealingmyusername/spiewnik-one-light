<?php
class Presentation {

    static function getAll() {
        return Conn::conn()->query("SELECT * FROM `presentation`")->fetch_all(MYSQLI_ASSOC);
    }

    static function countNotPermanent() {
        return Conn::conn()->query("SELECT COUNT(*) AS `count` FROM `presentation` WHERE isPermanent = 0")->fetch_assoc()["count"];
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

    static function getTop() {
        return Conn::conn()->query("SELECT id, title FROM `presentation` ORDER BY id DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);
    }

    static function getPage($page, $search) {
        $start = 0 + ($page - 1) * 24;
        $end = $start + 24;

        $searchTerm = "%$search%";

        $stmt = Conn::conn()->prepare("SELECT * FROM `presentation` WHERE title LIKE ? ORDER BY id DESC LIMIT $start, $end");

        $stmt->bind_param("s", $searchTerm);

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
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

    static function clear() {
        if($_SESSION["authorizationLevel"] != 4)
            return;

        $stmt = Conn::conn()->prepare("DELETE FROM `presentation` WHERE isPermanent = 0");

        $stmt->execute();
    }
}