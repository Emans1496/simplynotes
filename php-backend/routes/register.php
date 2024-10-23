<?php
require_once '../controllers/UserController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $username = $data['username'];
    $password = $data['password'];

    $controller = new UserController();
    $response = $controller->register($username, $password);

    echo json_encode($response);
}
?>
