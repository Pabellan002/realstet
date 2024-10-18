<?php

class Appointment {
    private $id;
    private $clientId;
    private $dateTime;
    private $propertyId;
    private $type;

    public function __construct($clientId, $dateTime, $propertyId, $type) {
        $this->clientId = $clientId;
        $this->dateTime = $dateTime;
        $this->propertyId = $propertyId;
        $this->type = $type;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getClientId() { return $this->clientId; }
    public function getDateTime() { return $this->dateTime; }
    public function getPropertyId() { return $this->propertyId; }
    public function getType() { return $this->type; }

    // Setters
    public function setDateTime($dateTime) { $this->dateTime = $dateTime; }
    public function setPropertyId($propertyId) { $this->propertyId = $propertyId; }
    public function setType($type) { $this->type = $type; }

    public function save($db) {
        $sql = "INSERT INTO appointments (client_id, date_time, property_id, type) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("isis", $this->clientId, $this->dateTime, $this->propertyId, $this->type);
        return $stmt->execute();
    }

    public static function getAll($db) {
        $sql = "SELECT * FROM appointments";
        $result = $db->query($sql);
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointment = new Appointment($row['client_id'], $row['date_time'], $row['property_id'], $row['type']);
            $appointment->id = $row['id'];
            $appointments[] = $appointment;
        }
        return $appointments;
    }

    // Add more methods as needed (e.g., update, delete, getById)
}
