<?php
require_once '../config/database.php';
require_once '../models/User.php';
require_once '../helpers/jwt.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function register($username, $password) {
        if ($this->user->create($username, $password)) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'User already exists'];
        }
    }

    public function login($username, $password) {
        if ($this->user->checkLogin($username, $password)) {
            $token = JWT::createToken($username); // Crea il JWT
            return ['success' => true, 'token' => $token];
        } else {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
    }
}
?>
