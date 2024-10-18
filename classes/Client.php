<?php

class Client {
    private $id;
    private $name;
    private $email;
    private $phone;
    private $propertyPreferences;

    public function __construct($name, $email, $phone, $propertyPreferences) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->propertyPreferences = $propertyPreferences;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getPhone() { return $this->phone; }
    public function getPropertyPreferences() { return $this->propertyPreferences; }

    // Setters
    public function setName($name) { $this->name = $name; }
    public function setEmail($email) { $this->email = $email; }
    public function setPhone($phone) { $this->phone = $phone; }
    public function setPropertyPreferences($propertyPreferences) { $this->propertyPreferences = $propertyPreferences; }

    public function save($db) {
        $sql = "INSERT INTO clients (name, email, phone, property_preferences) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssss", $this->name, $this->email, $this->phone, $this->propertyPreferences);
        return $stmt->execute();
    }

    public function update($db) {
        $sql = "UPDATE clients SET name = ?, email = ?, phone = ?, property_preferences = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssssi", $this->name, $this->email, $this->phone, $this->propertyPreferences, $this->id);
        return $stmt->execute();
    }

    public static function getAll($db) {
        $sql = "SELECT * FROM clients";
        $result = $db->query($sql);
        $clients = [];
        while ($row = $result->fetch_assoc()) {
            $client = new Client($row['name'], $row['email'], $row['phone'], $row['property_preferences']);
            $client->id = $row['id'];
            $clients[] = $client;
        }
        return $clients;
    }

    public static function getById($db, $id) {
        $sql = "SELECT * FROM clients WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $client = new Client($row['name'], $row['email'], $row['phone'], $row['property_preferences']);
            $client->id = $row['id'];
            return $client;
        }
        return null;
    }

    public static function deleteById($db, $id) {
        $sql = "DELETE FROM clients WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}