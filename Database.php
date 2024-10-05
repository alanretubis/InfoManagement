<?php

class Database {
    private $host = 'localhost';  // Database host
    private $db_name = 'your_db';  // Database name
    private $username = 'root';    // Database username
    private $password = '';        // Database password
    private $conn;

    // Constructor
    public function __construct() {
        $this->connect();
    }

    // Method to establish a database connection
    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }

    // Method to close the connection
    public function disconnect() {
        $this->conn = null;
    }

    // CREATE operation: Insert data into the database
    public function create($table, $data) {
        $fields = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $query = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute($data);
            return true;
        } catch (PDOException $e) {
            echo 'Create Error: ' . $e->getMessage();
            return false;
        }
    }

    // READ operation: Fetch data from the database
    public function read($table, $conditions = [], $columns = '*') {
        $query = "SELECT $columns FROM $table";

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', array_map(function ($key) {
                return "$key = :$key";
            }, array_keys($conditions)));
        }

        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute($conditions);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'Read Error: ' . $e->getMessage();
            return [];
        }
    }

    // UPDATE operation: Update data in the database
    public function update($table, $data, $conditions) {
        $fields = implode(', ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($data)));

        $condFields = implode(' AND ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($conditions)));

        $query = "UPDATE $table SET $fields WHERE $condFields";
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute(array_merge($data, $conditions));
            return true;
        } catch (PDOException $e) {
            echo 'Update Error: ' . $e->getMessage();
            return false;
        }
    }

    // DELETE operation: Delete data from the database
    public function delete($table, $conditions) {
        $condFields = implode(' AND ', array_map(function ($key) {
            return "$key = :$key";
        }, array_keys($conditions)));

        $query = "DELETE FROM $table WHERE $condFields";
        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute($conditions);
            return true;
        } catch (PDOException $e) {
            echo 'Delete Error: ' . $e->getMessage();
            return false;
        }
    }
}

