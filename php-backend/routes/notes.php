<?php
require_once '../controllers/NoteController.php';
require_once '../controllers/UserController.php';

$userController = new UserController();
$controller = new NoteController();

// Verifica se l'utente è autenticato
$user = $userController->isAuthenticated();

// Se autenticato, procedi con l'operazione sulle note
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
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}

?>
