<?php
class Database {
    private $conn;

    public function __construct($host, $user, $pass, $name) {
        $this->conn = new mysqli($host, $user, $pass, $name);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    public function close() {
        $this->conn->close();
    }

    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

    public function escapeString($string) {
        return $this->conn->real_escape_string($string);
    }
}