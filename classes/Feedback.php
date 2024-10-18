<?php

class Feedback {
    private $id;
    private $clientId;
    private $propertyId;
    private $rating;
    private $comment;
    private $date;

    public function __construct($clientId, $propertyId, $rating, $comment) {
        $this->clientId = $clientId;
        $this->propertyId = $propertyId;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->date = date('Y-m-d H:i:s');
    }

    // Getters
    public function getId() { return $this->id; }
    public function getClientId() { return $this->clientId; }
    public function getPropertyId() { return $this->propertyId; }
    public function getRating() { return $this->rating; }
    public function getComment() { return $this->comment; }
    public function getDate() { return $this->date; }

    // Setters
    public function setRating($rating) { $this->rating = $rating; }
    public function setComment($comment) { $this->comment = $comment; }

    public function save($db) {
        $sql = "INSERT INTO feedback (client_id, property_id, rating, comment, date) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("iiiss", $this->clientId, $this->propertyId, $this->rating, $this->comment, $this->date);
        return $stmt->execute();
    }

    public static function getAll($db) {
        $sql = "SELECT * FROM feedback";
        $result = $db->query($sql);
        $feedbacks = [];
        while ($row = $result->fetch_assoc()) {
            $feedback = new Feedback($row['client_id'], $row['property_id'], $row['rating'], $row['comment']);
            $feedback->id = $row['id'];
            $feedback->date = $row['date'];
            $feedbacks[] = $feedback;
        }
        return $feedbacks;
    }

    // Add more methods as needed (e.g., update, delete, getById)
}
