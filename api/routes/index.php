// index.php
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

require_once '../config/db.php';
require_once '../routes/Router.php';

if (\$_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Rispondi con 200 OK per le richieste preflight
    header("HTTP/1.1 200 OK");
    exit();
}

\$router = new Router();
\$router->direct(\$_SERVER['REQUEST_URI'], \$_SERVER['REQUEST_METHOD']);
?>
