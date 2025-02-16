<?php
class Conn {

    private static $conn;

    static function conn () {
        if(is_null(Conn::$conn))
            Conn::$conn = new mysqli("localhost", "root", "", "songbook-one-light");

        Conn::$conn->set_charset("utf8mb4");

        return Conn::$conn;
    }
}