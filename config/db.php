<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require '../vendor/autoload.php';

use Dotenv\Dotenv;

// Carica le variabili dal file .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

function getDBConnection() {
    $servername = $_ENV['DB_HOST'];
    $username   = $_ENV['DB_USERNAME'];
    $password   = $_ENV['DB_PASSWORD'];
    $dbname     = $_ENV['DB_DATABASE'];
    $port       = $_ENV['DB_PORT'];

    try {
        $conn = new PDO("pgsql:host=$servername;port=$port;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        error_log("Connessione fallita: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Errore di connessione al database.'
        ]);
        exit();
    }
}
?>
