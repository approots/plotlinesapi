<?php

namespace models;
use PDO;

class Db {

    public static function connection() {

        $dbhost="127.0.0.1";
        $dbuser="root";
        $dbpass="password";
        $dbname="plotlines";
        $connection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }
}