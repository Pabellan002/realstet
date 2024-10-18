<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Property.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $location = ($_POST['address'] ?? '') . ', ' . ($_POST['city'] ?? '') . ', ' . ($_POST['state'] ?? '');
    $type = $_POST['type'] ?? '';
    $status = $_POST['status'] ?? '';
    $features = $_POST['features'] ?? '';
    $bedrooms = $_POST['bedroom'] ?? 0;
    $bathrooms = $_POST['bathroom'] ?? 0;
    $area_size = $_POST['area_size'] ?? 0;
    
    // Handle file upload
    $image_path = '';
    if (isset($_FILES['property_image']) && $_FILES['property_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $image_name = uniqid() . '_' . $_FILES['property_image']['name'];
        $upload_path = $upload_dir . $image_name;
        if (move_uploaded_file($_FILES['property_image']['tmp_name'], $upload_path)) {
            // Save only the filename to the database
            $image_path = $image_name;
        }
    }
    
    $property = new Property($title, $description, $price, $location, $type, $status, $features, $image_path, $bedrooms, $bathrooms, $area_size);
    if ($property->save($db)) {
        header('Location: ../index.php?page=property_listings&message=Property added successfully');
    } else {
        header('Location: ../index.php?page=add_property&error=Failed to add property');
    }
    exit;
}
