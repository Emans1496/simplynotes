// Router.php
<?php
require_once '../controllers/NoteController.php';
require_once '../controllers/UserController.php';

class Router {
    public function direct($uri, $method) {
        switch ($uri) {
            case '/api/add_note':
                if ($method == 'POST') {
                    (new NoteController())->addNote();
                }
                break;
            case '/api/get_notes':
                if ($method == 'GET') {
                    (new NoteController())->getNotes();
                }
                break;
            case '/api/delete_note':
                if ($method == 'POST') {
                    (new NoteController())->deleteNote();
                }
                break;
            case '/api/update_note':
                if ($method == 'POST') {
                    (new NoteController())->updateNote();
                }
                break;
            case '/api/register':
                if ($method == 'POST') {
                    (new UserController())->register();
                }
                break;
            case '/api/login':
                if ($method == 'POST') {
                    (new UserController())->login();
                }
                break;
            case '/api/logout':
                if ($method == 'POST') {
                    (new UserController())->logout();
                }
                break;
            default:
                header("HTTP/1.1 404 Not Found");
                echo json_encode(["success" => false, "message" => "Endpoint non trovato."]);
                break;
        }
    }
}
?>