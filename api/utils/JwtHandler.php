<?php
header("Access-Control-Allow-Origin: https://simplynotess2i.netlify.app");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");


require_once './vendor/autoload.php';
use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Dotenv\Dotenv;

class JwtHandler {
    private static $secret_key;
    private static $algorithm = 'HS256';

    // Metodo per caricare il segreto JWT dal file .env
    private static function loadSecretKey() {
        if (empty(self::$secret_key)) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../config');
            $dotenv->load();
            self::$secret_key = $_ENV['SECRET_KEY']; // Aggiustato il nome della variabile per combaciare con il .env
        }
    }

    /**
     * Genera un token JWT per l'utente specificato.
     * 
     * @param int|string $user_id L'ID dell'utente per cui generare il token.
     * @return string Il token JWT generato.
     */
    public static function generateToken($user_id): string {
        self::loadSecretKey();  // Assicurati che la chiave segreta sia stata caricata

        $payload = [
            'iss' => 'simplynotes',
            'aud' => 'simplynotes',
            'iat' => time(),
            'exp' => time() + (60 * 60), // 1 ora di scadenza
            'user_id' => $user_id
        ];

        return JWT::encode($payload, self::$secret_key, self::$algorithm); // Specificare l'algoritmo nella codifica
    }

    /**
     * Valida un token JWT e restituisce un oggetto stdClass se il token è valido, o null in caso contrario.
     * 
     * @param string $jwt Il token JWT da validare.
     * @return stdClass|null Restituisce i dati decodificati come stdClass se il token è valido, altrimenti null.
     */
    public static function validateToken(string $jwt): ?stdClass {
        try {
            self::loadSecretKey();  // Assicurati che la chiave segreta sia stata caricata
            $decoded = JWT::decode($jwt, new Key(self::$secret_key, self::$algorithm));
            return $decoded; // Restituisce stdClass come richiesto
        } catch (Exception $e) {
            error_log("Token non valido: " . $e->getMessage());
            return null;
        }
    }
}
?>
