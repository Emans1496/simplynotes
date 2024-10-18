// Router.php
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require_once '../controllers/NoteController.php';
require_once '../controllers/UserController.php';

class Router {
    public function direct($uri, $method) {
        $cleanUri = strtok($uri, '?');
        $parsedUri = parse_url($uri, PHP_URL_PATH);


        switch ($cleanUri) {
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
                    error_log("Richiesta URI: " . $_SERVER['REQUEST_URI']);
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