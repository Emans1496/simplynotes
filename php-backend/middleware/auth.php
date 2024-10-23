<?php
require_once '../helpers/jwt.php';

function isAuthenticated() {
    $headers = apache_request_headers();
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        $decoded = JWTHandler::validateToken($token);
        if ($decoded) {
            return $decoded;
        }
    }
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}
?>
