<?php
class Conn {

    private static $conn;

    static function conn () {
        if(is_null(Conn::$conn))
            Conn::$conn = new mysqli("localhost", "root", "", "songbook-one-light");

        return Conn::$conn;
    }
}