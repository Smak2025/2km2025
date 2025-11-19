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

    public function createPointsTable(){
        $query  = 'CREATE TABLE IF NOT EXISTS points ('.
            'id SERIAL PRIMARY KEY, '.
            'points INTEGER CHECK(points >= 0 AND points <= 100) DEFAULT 0 NOT NULL, '.
            'subject VARCHAR(30) NOT NULL, '.
            'type INTEGER NOT NULL DEFAULT 1, '.
            'date DATE, '.
            'stud_id INTEGER NOT NULL, '.
            'FOREIGN KEY (stud_id) REFERENCES students(id))';
        try{
            $this->conn->beginTransaction();
            $this->conn->exec($query);
            $this->conn->commit();
        } catch (\PDOException $e){
            $this->conn->rollBack();
        }
    }

    public function addStudent(array $stud): void
    {
        $query  = 'INSERT INTO students (firstName, lastName, birthDate, groupNum) VALUES ('.
            ':firstName, :lastName, :birthDate, :groupNum)';
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':firstName', $stud['first_name']);
            $stmt->bindParam(':lastName', $stud['last_name']);
            $stmt->bindParam(':birthDate', $stud['birth_date']);
            $stmt->bindParam(':groupNum', $stud['group']);
            $stmt->execute();
            $this->conn->commit();
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            throw new \PDOException($e->getMessage());
        }
    }

    public function deleteStudent(int $id): void
    {
        $query  = 'DELETE FROM students WHERE id = :id';
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $this->conn->commit();
        } catch (\PDOException $e) {
            $this->conn->rollBack();
            throw new \PDOException($e->getMessage());
        }
    }

    public function getAllStudents() : ?array {
        $query  = 'SELECT * FROM students';
        $this->conn->beginTransaction();
        try{
            $result = $this->conn->query($query, PDO::FETCH_ASSOC);
            $studs = $result->fetchAll(PDO::FETCH_ASSOC);
            $result->closeCursor();
            $this->conn->commit();
            return $studs;
        } catch (\PDOException $e){
            $this->conn->rollBack();
            return null;
        }
    }

    public function getPointsForStudent(int $id) : ?array {
        $query  = 'SELECT * FROM points WHERE stud_id = :id';
        $this->conn->beginTransaction();
        try{
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $points = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $this->conn->commit();
            return $points;
        } catch (\PDOException $e){
            $this->conn->rollBack();
            return null;
        }
    }

    public function getAllProducts() : ?array {
        $query  = 'SELECT * FROM products';
        $this->conn->beginTransaction();
        try{
            $result = $this->conn->query($query, PDO::FETCH_ASSOC);
            $prods = $result->fetchAll(PDO::FETCH_ASSOC);
            $result->closeCursor();
            $this->conn->commit();
            return $prods;
        } catch (\PDOException $e){
            $this->conn->rollBack();
            return null;
        }
    }

    public function getSalesForProduct(int $id) : ?array {
        $query  = 'SELECT * FROM sale WHERE sale.product_id = :id';
        $this->conn->beginTransaction();
        try{
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $points = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $this->conn->commit();
            return $points;
        } catch (\PDOException $e){
            $this->conn->rollBack();
            return null;
        }
    }

//    public function addStudentBad($firstName, $lastName, $birthDate, $groupNum){
//        $query  = 'INSERT INTO students (firstName, lastName, birthDate, groupNum) VALUES ('.
//            "'$firstName', '$lastName', '$birthDate', '$groupNum')";
//        try {
//            $this->conn->beginTransaction();
//            $this->conn->exec($query);
//            $this->conn->commit();
//        } catch (\PDOException $e) {
//            $this->conn->rollBack();
//        }
//    }

}