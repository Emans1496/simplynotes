<?php
header("Access-Control-Allow-Origin: https://simplynotes-production.up.railway.app/");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require_once '../utils/JwtHandler.php';
require_once '../config/db.php';

class NoteController {
    private $conn;

    public function __construct() {
        $this->conn = getDBConnection();
    }

    // Metodo per aggiungere una nota
    public function addNote() {
        $headers = getallheaders();
        $jwt = $headers['Authorization'] ?? '';
        $userId = JwtHandler::validateToken($jwt);

        if (!$userId) {
            echo json_encode(["success" => false, "message" => "Token non valido o utente non autenticato."]);
            return;
        }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';

        if (empty($title) || empty($content)) {
            echo json_encode(["success" => false, "message" => "Dati mancanti."]);
            return;
        }

        try {
            $sql = "INSERT INTO notes (user_id, title, content) VALUES (:user_id, :title, :content)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Nota aggiunta con successo."]);
            } else {
                echo json_encode(["success" => false, "message" => "Errore durante l'aggiunta della nota."]);
            }
        } catch (PDOException $e) {
            error_log("Errore durante l'aggiunta della nota: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "Errore durante l'aggiunta della nota."]);
        }
    }

    // Metodo per ottenere tutte le note
    public function getNotes() {
        $headers = getallheaders();
        $jwt = $headers['Authorization'] ?? '';
        $userId = JwtHandler::validateToken($jwt);

        if (!$userId) {
            echo json_encode(["success" => false, "message" => "Token non valido o utente non autenticato."]);
            return;
        }

        try {
            $sql = "SELECT * FROM notes WHERE user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(["success" => true, "notes" => $notes]);
        } catch (PDOException $e) {
            error_log("Errore durante il recupero delle note: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "Errore durante il recupero delle note."]);
        }
    }

    // Metodo per eliminare una nota
    public function deleteNote() {
        $headers = getallheaders();
        $jwt = $headers['Authorization'] ?? '';
        $userId = JwtHandler::validateToken($jwt);

        if (!$userId) {
            echo json_encode(["success" => false, "message" => "Token non valido o utente non autenticato."]);
            return;
        }

        $noteId = $_POST['id'] ?? '';

        if (empty($noteId)) {
            echo json_encode(["success" => false, "message" => "ID della nota mancante."]);
            return;
        }

        try {
            $sql = "DELETE FROM notes WHERE id = :id AND user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $noteId, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Nota eliminata con successo."]);
            } else {
                echo json_encode(["success" => false, "message" => "Errore durante l'eliminazione della nota."]);
            }
        } catch (PDOException $e) {
            error_log("Errore durante l'eliminazione della nota: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "Errore durante l'eliminazione della nota."]);
        }
    }

    // Metodo per aggiornare una nota
    public function updateNote() {
        $headers = getallheaders();
        $jwt = $headers['Authorization'] ?? '';
        $userId = JwtHandler::validateToken($jwt);

        if (!$userId) {
            echo json_encode(["success" => false, "message" => "Token non valido o utente non autenticato."]);
            return;
        }

        $noteId = $_POST['id'] ?? '';
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';

        if (empty($noteId) || empty($title) || empty($content)) {
            echo json_encode(["success" => false, "message" => "Dati mancanti."]);
            return;
        }

        try {
            $sql = "UPDATE notes SET title = :title, content = :content WHERE id = :id AND user_id = :user_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);
            $stmt->bindValue(':id', $noteId, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Nota aggiornata con successo."]);
            } else {
                echo json_encode(["success" => false, "message" => "Errore durante l'aggiornamento della nota."]);
            }
        } catch (PDOException $e) {
            error_log("Errore durante l'aggiornamento della nota: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "Errore durante l'aggiornamento della nota."]);
        }
    }
}

?>