<?php
header("Access-Control-Allow-Origin: https://simplynotes-static.onrender.com");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Rispondi con 200 OK per le richieste preflight
    header("HTTP/1.1 200 OK");
    exit();
}
session_set_cookie_params([
    'lifetime' => 0,
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None',
]);


session_start();

// Distruggi la sessione
session_unset();
session_destroy();

// Risposta di successo
echo json_encode(["success" => true, "message" => "Logout effettuato con successo."]);
?>
