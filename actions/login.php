<?php
session_start();
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $user = User::authenticate($db, $username, $password);

    if ($user) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['user_role'] = $user->getRole();
        header('Location: ../index.php?page=dashboard');
        exit;
    } else {
        header('Location: ../index.php?page=login&error=invalid_credentials');
        exit;
    }
}
