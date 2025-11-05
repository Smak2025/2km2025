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
        $this->conn = new PDO($conn_str, self::$username, self::$password);
    }

    public static function getInstance(): DbHelper{
        if (self::$dbHelper == null) {
            self::$dbHelper = new DbHelper();
        }
        return self::$dbHelper;
    }

    public function createStudentTable(){
        $query  = 'CREATE TABLE IF NOT EXISTS students ('.
                  'id SERIAL PRIMARY KEY, '.
                  'firstName VARCHAR(30) NOT NULL, '.
                  'lastName VARCHAR(30) NOT NULL, '.
                  'birthDate DATE NOT NULL, '.
                  'groupNum VARCHAR(10) NOT NULL)';
        try{
            $this->conn->beginTransaction();
            $this->conn->exec($query);
            $this->conn->commit();
        } catch (\PDOException $e){
            $this->conn->rollBack();
        }
    }

    public function addStudent($firstName, $lastName, $birthDate, $groupNum): void
    {
        $query  = 'INSERT INTO students (firstName, lastName, birthDate, groupNum) VALUES ('.
            ':firstName, :lastName, :birthDate, :groupNum)';
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':firstName', $firstName);
            $stmt->bindParam(':lastName', $lastName);
            $stmt->bindParam(':birthDate', $birthDate);
            $stmt->bindParam(':groupNum', $groupNum);
            $stmt->execute();
            $this->conn->commit();
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            throw new \PDOException($e->getMessage());
        }
    }

    public function addStudentBad($firstName, $lastName, $birthDate, $groupNum){
        $query  = 'INSERT INTO students (firstName, lastName, birthDate, groupNum) VALUES ('.
            "'$firstName', '$lastName', '$birthDate', '$groupNum')";
        try {
            $this->conn->beginTransaction();
            $this->conn->exec($query);
            $this->conn->commit();
        } catch (\PDOException $e) {
            $this->conn->rollBack();
        }
    }

}