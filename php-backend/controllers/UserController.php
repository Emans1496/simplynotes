<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function login($username, $password) {
        if ($this->user->checkLogin($username, $password)) {
            // Crea il payload del JWT
            $payload = [
                "iss" => "http://yourapp.com",  // Issuer del token
                "aud" => "http://yourapp.com",  // Audience del token
                "iat" => time(),                // Timestamp di emissione
                "exp" => time() + 3600,         // Scadenza (1 ora)
                "data" => [                     // Dati dell'utente
                    "username" => $username
                ]
            ];

            // Crea il token JWT
            $secret_key = 'your_secret_key';
            $jwt = JWT::encode($payload, $secret_key, 'HS256');

            // Ritorna il token all'utente
            return ['success' => true, 'token' => $jwt];
        } else {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
    }

    public function isAuthenticated() {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);

            try {
                $secret_key = 'your_secret_key';
                $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
                return (array) $decoded;  // Ritorna i dati decodificati
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit();
            }
        }

        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit();
    }
}
?>
