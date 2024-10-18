<?php

class User {
    private $id;
    private $username;
    private $email;
    private $role;
    private $password;

    public function __construct($id, $username, $email, $role) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public static function authenticate($db, $username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                return new User($user['id'], $user['username'], $user['email'], $user['role']);
            }
        }

        return null;
    }

    public function save($db) {
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssss", $this->username, $this->email, $this->password, $this->role);
        if ($stmt->execute()) {
            $this->id = $stmt->insert_id;
            return true;
        }
        return false;
    }

    public static function usernameExists($db, $username) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE username = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public static function emailExists($db, $email) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }
}
