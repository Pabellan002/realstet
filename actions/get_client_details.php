<?php
require_once '../config.php';
require_once '../classes/Database.php';
require_once '../classes/Client.php';

if (isset($_GET['id'])) {
    $db = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $client = Client::getById($db, $_GET['id']);

    if ($client) {
        echo json_encode([
            'id' => $client->getId(),
            'name' => $client->getName(),
            'email' => $client->getEmail(),
            'phone' => $client->getPhone(),
            'property_preferences' => $client->getPropertyPreferences()
        ]);
    } else {
        echo json_encode(['error' => 'Client not found']);
    }
} else {
    echo json_encode(['error' => 'No client ID provided']);
}
