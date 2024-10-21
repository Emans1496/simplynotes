<?php
// Includi le librerie necessarie
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JwtHandler {
    private static $secretKey;

    // Metodo per inizializzare la secretKey dall'ambiente
    public static function init() {
        self::$secretKey = getenv('SECRET_KEY'); // Carica la chiave segreta dal file di ambiente
    }

    // Metodo per generare un token JWT
    public static function generateToken($userId) {
        $issuedAt = time(); // Tempo corrente
        $expirationTime = $issuedAt + (60 * 60); // Imposta l'espirazione a 1 ora

        $payload = [
            'iat' => $issuedAt,       // Tempo di emissione
            'exp' => $expirationTime, // Tempo di scadenza
            'data' => [
                'userId' => $userId
            ]
        ];

        // Genera il token utilizzando la chiave segreta
        return JWT::encode($payload, self::$secretKey, 'HS256');
    }

    // Metodo per validare il token JWT
    public static function validateToken($jwt) {
        try {
            // Decodifica il token utilizzando la chiave segreta
            $decoded = JWT::decode($jwt, new Key(self::$secretKey, 'HS256'));

            // Se il token è valido, restituisci l'userId dal payload
            return $decoded->data->userId;
        } catch (Exception $e) {
            error_log("Errore di validazione del token JWT: " . $e->getMessage());
            return false;
        }
    }
}

// Inizializza la classe JwtHandler per usare la chiave segreta
JwtHandler::init();
?>
