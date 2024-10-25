<?php
require_once '../controllers/UserController.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $controller = new UserController();
        $response = $controller->login($username, $password);

        echo json_encode($response);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Username and password required']);
    }
    exit();
}
?>
