<?php
require_once 'config.php';
require_once 'classes/Database.php';
require_once 'classes/Property.php';

$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (isset($_GET['id'])) {
    $propertyId = $_GET['id'];
    $property = Property::getById($db, $propertyId);

    if ($property) {
        echo json_encode([
            'id' => $property->getId(),
            'title' => $property->getTitle(),
            'description' => $property->getDescription(),
            'price' => $property->getPrice(),
            'location' => $property->getLocation(),
            'type' => $property->getType(),
            'status' => $property->getStatus(),
            'features' => $property->getFeatures(),
            'image_path' => $property->getImagePath(),
            'bedrooms' => $property->getBedrooms(),
            'bathrooms' => $property->getBathrooms(),
            'area_size' => $property->getAreaSize()
        ]);
    } else {
        echo json_encode(['error' => 'Property not found']);
    }
} else {
    echo json_encode(['error' => 'No property ID provided']);
}

$db->close();
