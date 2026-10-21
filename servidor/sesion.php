<?php
declare(strict_types=1);

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    http_response_code(403);
    exit;
}

define('QBI_SERVIDOR', true);

session_name('QUERYBI_SESION');

session_start([
    'use_strict_mode' => true,
    'use_only_cookies' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_secure' => !empty($_SERVER['HTTPS'])
        && $_SERVER['HTTPS'] !== 'off'
]);

header('Cache-Control: no-store');

function qbi_texto(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function qbi_url(string $ruta): string
{
    // Las páginas PHP están a un nivel de la raíz del proyecto.
    $base = rtrim(
        str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME']))),
        '/'
    );

    return $base . '/' . ltrim($ruta, '/');
}

function qbi_usuario(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function qbi_nombre_rol(string $rol): string
{
    return [
        'superadministrador' => 'Superadministrador',
        'administrador' => 'Administrador',
        'usuario' => 'Usuario'
    ][$rol] ?? 'Sin rol';
}

function qbi_inicio(): string
{
    return (qbi_usuario()['rol'] ?? '') === 'superadministrador'
        ? 'plataforma/panel.php'
        : 'aplicacion/panel.php';
}

function qbi_redirigir(string $ruta): void
{
    header('Location: ' . qbi_url($ruta), true, 303);
    exit;
}

function qbi_token(): string
{
    if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['token'];
}

function qbi_token_valido($token): bool
{
    return is_string($token)
        && hash_equals(qbi_token(), $token);
}

function qbi_requerir(array $roles): void
{
    $usuario = qbi_usuario();

    if ($usuario === null) {
        qbi_redirigir('acceso/login.php');
    }

    if (!in_array($usuario['rol'], $roles, true)) {
        http_response_code(403);

        $estilos = qbi_texto(qbi_url('assets/css/estilos.css'));
        $inicio = qbi_texto(qbi_url(qbi_inicio()));

        echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
        echo '<title>Acceso denegado | QueryBI AI</title>';
        echo '<link rel="stylesheet" href="' . $estilos . '"></head>';
        echo '<body><main class="contenedor tarjeta espacio-superior">';
        echo '<h1>Acceso denegado</h1>';
        echo '<p>Tu cuenta no tiene permiso para abrir esta sección.</p>';
        echo '<a class="boton" href="' . $inicio . '">Volver a mi panel</a>';
        echo '</main></body></html>';

        exit;
    }
}

function qbi_entrar(string $correo, string $clave): bool
{
    $cuentas = require __DIR__ . '/cuentas-demo.php';

    foreach ($cuentas as $cuenta) {
        if (
            $cuenta['correo'] === strtolower(trim($correo))
            && password_verify($clave, $cuenta['hash'])
        ) {
            session_regenerate_id(true);

            unset($cuenta['hash']);

            $_SESSION['usuario'] = $cuenta;
            $_SESSION['token'] = bin2hex(random_bytes(32));

            return true;
        }
    }

    return false;
}