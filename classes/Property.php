<?php

class Property {
    private $id;
    private $title;
    private $description;
    private $price;
    private $location;
    private $type;
    private $status;
    private $features;
    private $image_path;
    private $bedrooms;
    private $bathrooms;
    private $area_size;

    public function __construct($title, $description, $price, $location, $type, $status, $features, $image_path, $bedrooms, $bathrooms, $area_size) {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->location = $location;
        $this->type = $type;
        $this->status = $status;
        $this->features = $features;
        $this->image_path = $image_path;
        $this->bedrooms = $bedrooms;
        $this->bathrooms = $bathrooms;
        $this->area_size = $area_size;
    }

    public function save($db) {
        $sql = "INSERT INTO properties (title, description, price, location, type, status, features, image_path, bedrooms, bathrooms, area_size) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssdsssssiis", $this->title, $this->description, $this->price, $this->location, $this->type, $this->status, $this->features, $this->image_path, $this->bedrooms, $this->bathrooms, $this->area_size);
        return $stmt->execute();
    }

    // Add getter methods for all properties
    
    public static function getAll($db, $start = 0, $limit = 10, $search = '', $type = '', $status = '', $minPrice = 0, $maxPrice = PHP_FLOAT_MAX) {
        $sql = "SELECT * FROM properties WHERE price BETWEEN ? AND ?";
        $params = [$minPrice, $maxPrice];
        $types = "dd";

        if ($search) {
            $sql .= " AND (title LIKE ? OR description LIKE ? OR location LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= "sss";
        }

        if ($type) {
            $sql .= " AND type = ?";
            $params[] = $type;
            $types .= "s";
        }

        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
            $types .= "s";
        }

        $sql .= " LIMIT ?, ?";
        $params[] = $start;
        $params[] = $limit;
        $types .= "ii";

        $stmt = $db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $properties = [];
        while ($row = $result->fetch_assoc()) {
            $property = new Property($row['title'], $row['description'], $row['price'], $row['location'], $row['type'], $row['status'], $row['features'], $row['image_path'], $row['bedrooms'], $row['bathrooms'], $row['area_size']);
            $property->id = $row['id'];
            $properties[] = $property;
        }
        return $properties;
    }

    public static function getById($db, $id) {
        $sql = "SELECT * FROM properties WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $property = new Property($row['title'], $row['description'], $row['price'], $row['location'], $row['type'], $row['status'], $row['features'], $row['image_path'], $row['bedrooms'], $row['bathrooms'], $row['area_size']);
            $property->id = $row['id'];
            return $property;
        }
        return null;
    }

    // Add getter methods for all properties
    public function getId() { return $this->id; }
    public function getTitle() { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getPrice() { return $this->price; }
    public function getLocation() { return $this->location; }
    public function getType() { return $this->type; }
    public function getStatus() { return $this->status; }
    public function getFeatures() { return $this->features; }
    public function getImagePath() {
        if (empty($this->image_path)) {
            return 'img/default-property.jpg'; // Path to a default image
        }
        
        // If the image path is a full URL, return it as is
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        
        // Otherwise, construct the full path
        return 'uploads/' . $this->image_path;
    }
    public function getBedrooms() { return $this->bedrooms; }
    public function getBathrooms() { return $this->bathrooms; }
    public function getAreaSize() { return $this->area_size; }

    public function update($db) {
        $sql = "UPDATE properties SET title = ?, description = ?, price = ?, location = ?, type = ?, status = ?, features = ?, image_path = ?, bedrooms = ?, bathrooms = ?, area_size = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssdsssssiisi", $this->title, $this->description, $this->price, $this->location, $this->type, $this->status, $this->features, $this->image_path, $this->bedrooms, $this->bathrooms, $this->area_size, $this->id);
        return $stmt->execute();
    }

    public static function deleteById($db, $id) {
        $sql = "DELETE FROM properties WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Add setter methods
    public function setTitle($title) { $this->title = $title; }
    public function setDescription($description) { $this->description = $description; }
    public function setPrice($price) { $this->price = $price; }
    public function setLocation($location) { $this->location = $location; }
    public function setType($type) { $this->type = $type; }
    public function setStatus($status) { $this->status = $status; }
    public function setFeatures($features) { $this->features = $features; }
    public function setImagePath($image_path) { $this->image_path = $image_path; }
    public function setBedrooms($bedrooms) { $this->bedrooms = $bedrooms; }
    public function setBathrooms($bathrooms) { $this->bathrooms = $bathrooms; }
    public function setAreaSize($area_size) { $this->area_size = $area_size; }

    public static function getCount($db, $search = '', $type = '', $status = '', $minPrice = 0, $maxPrice = PHP_FLOAT_MAX) {
        $sql = "SELECT COUNT(*) as count FROM properties WHERE price BETWEEN ? AND ?";
        $params = [$minPrice, $maxPrice];
        $types = "dd";

        if ($search) {
            $sql .= " AND (title LIKE ? OR description LIKE ? OR location LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $types .= "sss";
        }

        if ($type) {
            $sql .= " AND type = ?";
            $params[] = $type;
            $types .= "s";
        }

        if ($status) {
            $sql .= " AND status = ?";
            $params[] = $status;
            $types .= "s";
        }

        $stmt = $db->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public static function getCountByStatus($db, $status) {
        $sql = "SELECT COUNT(*) as count FROM properties WHERE status = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public static function getRecent($db, $limit) {
        $sql = "SELECT * FROM properties ORDER BY id DESC LIMIT ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $properties = [];
        while ($row = $result->fetch_assoc()) {
            $property = new Property($row['title'], $row['description'], $row['price'], $row['location'], $row['type'], $row['status'], $row['features'], $row['image_path'], $row['bedrooms'], $row['bathrooms'], $row['area_size']);
            $property->id = $row['id'];
            $properties[] = $property;
        }
        return $properties;
    }
}
