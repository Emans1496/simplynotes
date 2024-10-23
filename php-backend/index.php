<?php
// Carica i file necessari
require_once 'routes/register.php';
require_once 'routes/login.php';
require_once 'routes/notes.php';

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
