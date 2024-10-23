<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JWTHandler {
    private static $secret_key = 'asfidfj8rurff.dòà-sèdòvs0f2w.ascs13';
    private static $encrypt = ['HS256'];
    private static $aud = null;

    public static function createToken($username) {
        $time = time();
        $token = [
            'iat' => $time, // Orario di emissione
            'exp' => $time + (60 * 60), // Scadenza 1 ora
            'data' => ['username' => $username]
        ];

        return JWT::encode($token, self::$secret_key);
    }

    public static function validateToken($token) {
        try {
            // Usare la classe Key come terzo parametro nella funzione decode
            $decoded = JWT::decode($token, new Key(self::$secret_key, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
