<?php

namespace common;

use PDO;

class DbHelper
{
    private static string $host = "localhost";
    private static int $port = 5432;
    private static string $dbname = "2km2025";
    private static string $username = "postgres";
    private static string $password = "postgres";
    private static ?DbHelper $dbHelper = null;
    private PDO $conn;
    private function __construct()
    {
        $conn_str  = "pgsql:host=" . self::$host;
        $conn_str .= ";port=" . self::$port;
        $conn_str .= ";dbname=" . self::$dbname;
        $conn_str .= ";username=" . self::$username;
        $conn_str .= ";password=" . self::$password;
        $this->conn = new PDO($conn_str);
    }

    public static function getInstance(): DbHelper{
        if (self::$dbHelper == null) {
            self::$dbHelper = new DbHelper();
        }
        return self::$dbHelper;
    }

}