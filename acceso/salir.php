<?php
require_once __DIR__ . '/../servidor/sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Utiliza el botón Cerrar sesión.');
}

if (!qbi_token_valido($_POST['token'] ?? null)) {
    http_response_code(403);
    exit('Solicitud no válida.');
}

$_SESSION = [];

$cookie = session_get_cookie_params();

setcookie(session_name(), '', [
    'expires' => time() - 3600,
    'path' => $cookie['path'],
    'domain' => $cookie['domain'],
    'secure' => $cookie['secure'],
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_destroy();

qbi_redirigir('acceso/login.php');