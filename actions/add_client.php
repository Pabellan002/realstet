<?php
session_start();
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Client.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $client = new Client($name, $email, $phone);

    if ($client->save($db)) {
        header('Location: ../index.php?page=client_management&message=Client added successfully');
    } else {
        header('Location: ../index.php?page=client_management&error=Failed to add client');
    }
    exit;
}