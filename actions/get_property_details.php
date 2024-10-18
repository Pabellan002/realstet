<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Property.php';

if (isset($_GET['id'])) {
    $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $property = Property::getById($db, $_GET['id']);

    if ($property) {
        echo json_encode([
            'id' => $property->getId(),
            'title' => $property->getTitle(),
            'description' => $property->getDescription(),
            'price' => $property->getPrice(),
            'location' => $property->getLocation(),
            'type' => $property->getType(),
            'status' => $property->getStatus(),
            'bedrooms' => $property->getBedrooms(),
            'bathrooms' => $property->getBathrooms(),
            'area_size' => $property->getAreaSize(),
            'features' => $property->getFeatures(),
            'image_path' => $property->getImagePath()
        ]);
    } else {
        echo json_encode(['error' => 'Property not found']);
    }
} else {
    echo json_encode(['error' => 'No property ID provided']);
}