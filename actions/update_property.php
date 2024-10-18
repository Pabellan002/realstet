<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Property.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    $id = $_POST['id'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? 0;
    $location = $_POST['location'] ?? '';
    $type = $_POST['type'] ?? '';
    $status = $_POST['status'] ?? '';
    $features = $_POST['features'] ?? '';
    $bedrooms = $_POST['bedroom'] ?? 0;
    $bathrooms = $_POST['bathroom'] ?? 0;
    $area_size = $_POST['area_size'] ?? 0;
    
    $property = Property::getById($db, $id);
    if ($property) {
        $property->setTitle($title);
        $property->setDescription($description);
        $property->setPrice($price);
        $property->setLocation($location);
        $property->setType($type);
        $property->setStatus($status);
        $property->setFeatures($features);
        $property->setBedrooms($bedrooms);
        $property->setBathrooms($bathrooms);
        $property->setAreaSize($area_size);

        // Handle file upload
        if (isset($_FILES['property_image']) && $_FILES['property_image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            $image_name = uniqid() . '_' . $_FILES['property_image']['name'];
            $upload_path = $upload_dir . $image_name;
            if (move_uploaded_file($_FILES['property_image']['tmp_name'], $upload_path)) {
                // Delete the old image if it exists
                $old_image_path = $property->getImagePath();
                if (file_exists($old_image_path) && is_file($old_image_path)) {
                    unlink($old_image_path);
                }
                // Save only the filename to the database
                $property->setImagePath($image_name);
            }
        }

        if ($property->update($db)) {
            header('Location: ../index.php?page=property_listings&message=Property updated successfully');
        } else {
            header('Location: ../index.php?page=edit_property&id=' . $id . '&error=Failed to update property');
        }
    } else {
        header('Location: ../index.php?page=property_listings&error=Property not found');
    }
    exit;
}
