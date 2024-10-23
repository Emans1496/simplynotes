<?php
class Note {
    private $conn;
    private $table_name = "notes";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crea una nuova nota
    public function create($userId, $content) {
        $query = "INSERT INTO " . $this->table_name . " (user_id, content) VALUES (:user_id, :content)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->bindParam(":content", $content);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Ottiene tutte le note di un utente
    public function getUserNotes($userId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Modifica una nota
    public function update($noteId, $content) {
        $query = "UPDATE " . $this->table_name . " SET content = :content WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":content", $content);
        $stmt->bindParam(":id", $noteId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Elimina una nota
    public function delete($noteId) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $noteId);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
