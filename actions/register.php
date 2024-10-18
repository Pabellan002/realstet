<?php
session_start();
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        header('Location: ../index.php?page=register&error=Passwords do not match');
        exit;
    }

    $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if (User::usernameExists($db, $username) || User::emailExists($db, $email)) {
        header('Location: ../index.php?page=register&error=Username or email already exists');
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $user = new User($username, $email, $hashed_password);
    if ($user->save($db)) {
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['username'] = $user->getUsername();
        $_SESSION['user_role'] = $user->getRole();
        header('Location: ../index.php?page=dashboard');
        exit;
    } else {
        header('Location: ../index.php?page=register&error=Failed to create account');
        exit;
    }
}
