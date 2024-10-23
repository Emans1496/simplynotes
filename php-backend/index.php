<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/routes/register.php';
require_once __DIR__ . '/routes/login.php';
require_once __DIR__ . '/routes/notes.php';

// Gestisci la richiesta in base al path
$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/register':
        require 'routes/register.php';
        break;
    case '/login':
        require 'routes/login.php';
        break;
    case '/notes':
        require 'routes/notes.php';
        break;
    default:
        http_response_code(404);
        echo json_encode(['message' => 'Not Found']);
        break;
}
?>
