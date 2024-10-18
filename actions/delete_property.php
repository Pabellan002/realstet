<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Property.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    $id = $_GET['id'];
    
    if (Property::deleteById($db, $id)) {
        header('Location: ../index.php?page=property_listings&message=Property deleted successfully');
    } else {
        header('Location: ../index.php?page=property_listings&error=Failed to delete property');
    }
    exit;
}
