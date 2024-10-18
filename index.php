<?php
session_start();
require_once 'config.php';
require_once 'classes/Database.php';

$db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Pages that don't require authentication
$public_pages = ['home', 'properties', 'login', 'register'];

// Pages that require admin role
$admin_pages = ['dashboard', 'property_listings', 'add_property', 'edit_property', 'client_management'];

// Check if the user is trying to access a restricted page
if (!in_array($page, $public_pages)) {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }

    // Check if the user is trying to access an admin page without admin role
    if (in_array($page, $admin_pages) && $_SESSION['user_role'] !== 'admin') {
        header('Location: index.php?page=home');
        exit;
    }
}

switch ($page) {
    case 'home':
        include 'views/landing.php';
        break;
    case 'properties':
        include 'views/properties.php';
        break;
    case 'login':
        include 'views/login.php';
        break;
    case 'register':
        include 'views/register.php';
        break;
    case 'dashboard':
        include 'views/dashboard.php';
        break;
    case 'property_listings':
        include 'views/property_listings.php';
        break;
    case 'add_property':
        include 'views/add_property.php';
        break;
    case 'edit_property':
        include 'views/edit_property.php';
        break;
    case 'client_management':
        include 'views/client_management.php';
        break;
    case 'my_inquiries':
        include 'views/my_inquiries.php';
        break;
    default:
        include 'views/landing.php';
}

$db->close();
