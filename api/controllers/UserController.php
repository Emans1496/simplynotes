<?php
header("Access-Control-Allow-Origin: https://simplynotess2i.netlify.app");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");


require_once __DIR__ . '../config/db.php';
require_once __DIR__ . '../utils/JwtHandler.php';

class UserController {
    private $conn;

    public function __construct() {
        $this->conn = getDBConnection();
    }

    // Metodo per il login dell'utente
    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            echo json_encode(["success" => false, "message" => "Dati mancanti."]);
            return;
        }

        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $jwt = JwtHandler::generateToken(['user_id' => $user['id']]);
                echo json_encode(["success" => true, "token" => $jwt]);
            } else {
                echo json_encode(["success" => false, "message" => "Username o password errati."]);
            }
        } catch (PDOException $e) {
            error_log("Errore durante il login: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "Errore durante il login."]);
        }
    }

    // Metodo per registrare un nuovo utente
    public function register() {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($email) || empty($password)) {
            echo json_encode(["success" => false, "message" => "Dati mancanti."]);
            return;
        }

        // Hash della password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Verifica se l'username o l'email esistono già
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->fetch()) {
                echo json_encode(["success" => false, "message" => "Username o email già in uso."]);
                return;
            }

            // Inserisci il nuovo utente nel database
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();

            echo json_encode(["success" => true, "message" => "Registrazione avvenuta con successo."]);
        } catch (PDOException $e) {
            error_log("Errore durante la registrazione: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "Errore durante la registrazione."]);
        }
    }

    // Metodo per il logout dell'utente
    public function logout() {
        $headers = getallheaders();
        $jwt = $headers['Authorization'] ?? '';
        $decodedToken = JwtHandler::validateToken($jwt);

        if (!$decodedToken) {
            echo json_encode(["success" => false, "message" => "Token non valido o utente non autenticato."]);
            return;
        }

        // Non è necessario distruggere il token lato server, basta che il client lo elimini
        echo json_encode(["success" => true, "message" => "Logout effettuato con successo."]);
    }

    // Metodo per ottenere informazioni sull'utente
    public function getUserInfo() {
        $headers = getallheaders();
        $jwt = $headers['Authorization'] ?? '';
        $decodedToken = JwtHandler::validateToken($jwt);

        if (!$decodedToken) {
            echo json_encode(["success" => false, "message" => "Token non valido o utente non autenticato."]);
            return;
        }

        $userId = $decodedToken['user_id'];

        try {
            $stmt = $this->conn->prepare("SELECT id, username, email FROM users WHERE id = :id");
            $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode(["success" => true, "user" => $user]);
            } else {
                echo json_encode(["success" => false, "message" => "Utente non trovato."]);
            }
        } catch (PDOException $e) {
            error_log("Errore durante il recupero delle informazioni utente: " . $e->getMessage());
            echo json_encode(["success" => false, "message" => "Errore durante il recupero delle informazioni utente."]);
        }
    }
}
?>
