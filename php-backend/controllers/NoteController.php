<?php
require_once '../config/database.php';
require_once '../models/Note.php';
require_once '../middleware/auth.php';

class NoteController {
    private $db;
    private $note;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->note = new Note($this->db);
    }

    public function createNote() {
        $user = isAuthenticated(); // Verifica autenticazione
        $data = json_decode(file_get_contents('php://input'), true);

        if ($this->note->create($user['data']['username'], $data['content'])) {
            echo json_encode(['success' => true, 'message' => 'Note created']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create note']);
        }
    }

    public function getNotes() {
        $user = isAuthenticated(); // Verifica autenticazione
        $notes = $this->note->getUserNotes($user['data']['username']);
        echo json_encode($notes);
    }

    public function updateNote() {
        $user = isAuthenticated(); // Verifica autenticazione
        $data = json_decode(file_get_contents('php://input'), true);

        if ($this->note->update($data['id'], $data['content'])) {
            echo json_encode(['success' => true, 'message' => 'Note updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update note']);
        }
    }

    public function deleteNote() {
        $user = isAuthenticated(); // Verifica autenticazione
        $data = json_decode(file_get_contents('php://input'), true);

        if ($this->note->delete($data['id'])) {
            echo json_encode(['success' => true, 'message' => 'Note deleted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete note']);
        }
    }
}
?>
