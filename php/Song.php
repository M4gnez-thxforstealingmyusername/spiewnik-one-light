<?php
class Song {

    static function getAll() {
        return Conn::conn()->query("SELECT * FROM `song`")->fetch_all(MYSQLI_ASSOC);
    }

    static function getOne($id) {
        $stmt = Conn::conn()->prepare("SELECT * FROM `song` WHERE `id` = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    static function getResult() {
        return Conn::conn()->query("SELECT * FROM `song`");
    }

    static function getTitles() {
        return Conn::conn()->query("SELECT id, title FROM `song`")->fetch_all(MYSQLI_ASSOC);
    }

    static function getList($idString) {
        $idList = explode(",", $idString);

        foreach($idList as $id) {
            if(!is_numeric($id))
                return [];
        }

        return Conn::conn()->query("SELECT id, title FROM `song` WHERE id IN ($idString) ORDER BY FIELD (id, $idString)")->fetch_all(MYSQLI_ASSOC);
    }

    static function getTextList($idString) {
        $idList = explode(",", $idString);

        foreach($idList as $id) {
            if(!is_numeric($id))
                return [];
        }

        return Conn::conn()->query("SELECT `text` FROM `song` WHERE id IN ($idString) ORDER BY FIELD (id, $idString)")->fetch_all(MYSQLI_ASSOC);
    }

    static function getTop() {
        return Conn::conn()->query("SELECT id, title FROM `song` ORDER BY id DESC LIMIT 10")->fetch_all(MYSQLI_ASSOC);
    }

    static function getPage($page, $search) {
        $start = 0 + ($page - 1) * 24;
        $end = $start + 24;

        $searchTerm = "%$search%";

        $stmt = Conn::conn()->prepare("SELECT * FROM `song` WHERE title LIKE ? ORDER BY id DESC LIMIT $start, $end");

        $stmt->bind_param("s", $searchTerm);

        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    static function add($title, $userId, $description, $text, $chord) {
        $stmt = Conn::conn()->prepare("INSERT INTO `song` values (DEFAULT, ?, ?, ?, ?, ?, DEFAULT)");

        $stmt->bind_param("sisss", $title, $userId, $description, $text, $chord);

        $stmt->execute();
    }

    static function update($id, $title, $description, $text, $chord) {
        $stmt = Conn::conn()->prepare("UPDATE `song` set `title` = ?, `text` = ?, `chord` = ?, `description` = ? WHERE id = ?");

        $stmt->bind_param("ssssi", $title, $text, $chord, $description, $id);

        $stmt->execute();
    }

    static function delete($id) {
        $stmt = Conn::conn()->prepare("DELETE FROM `song` WHERE id = ?");

        $stmt->bind_param("i", $id);

        $stmt->execute();
    }

}