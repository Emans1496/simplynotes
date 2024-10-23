<?php
require_once '../controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['username']) && isset($data['password'])) {
        $username = $data['username'];
        $password = $data['password'];

        $controller = new UserController();
        $response = $controller->register($username, $password);

        // Risposta finale
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Gestione degli input mancanti
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Username and password required']);
    }
}
