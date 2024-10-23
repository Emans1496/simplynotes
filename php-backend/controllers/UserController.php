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
            $payload = [
                "iss" => "https://simplynotes-static.onrender.com",  
                "aud" => "https://simplynotes-static.onrender.com", 
                "iat" => time(),               
                "exp" => time() + 3600,         
                "data" => [                     
                    "username" => $username
                ]
            ];

 
            $secret_key = 'asfidfj8rurff.dòà-sèdòvs0f2w.ascs13';  
            $jwt = JWT::encode($payload, $secret_key, 'HS256');

            
            return ['success' => true, 'token' => $jwt];
        } else {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
    }

    public function register($username, $password) {
        if ($this->user->create($username, $password)) {
            return ['success' => true, 'message' => 'User registered successfully'];
        } else {
            return ['success' => false, 'message' => 'User already exists'];
        }
    }

    public function isAuthenticated() {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);

            try {
                $secret_key = 'asfidfj8rurff.dòà-sèdòvs0f2w.ascs13'; 
                $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
                return (array) $decoded;  
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
