<?php
require_once '../controllers/NoteController.php';

$controller = new NoteController();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $controller->createNote();
        break;
    case 'GET':
        $controller->getNotes();
        break;
    case 'PUT':
        $controller->updateNote();
        break;
    case 'DELETE':
        $controller->deleteNote();
        break;
    default:
        http_response_code(405); // Metodo non consentito
        echo json_encode(['
