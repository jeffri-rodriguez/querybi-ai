<?php
declare(strict_types=1);

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    http_response_code(403);
    exit;
}
define('QBI_SERVIDOR', true);
require_once __DIR__ . '/base-datos.php';

session_name('QUERYBI_SESION');
session_start([
    'use_strict_mode' => true,
    'use_only_cookies' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
]);
header('Cache-Control: no-store');

function qbi_texto(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

function qbi_url(string $ruta): string
{
    $base = rtrim(str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME']))), '/');
    return $base . '/' . ltrim($ruta, '/');
}

function qbi_usuario(): ?array
{
    // Las sesiones del login antiguo deben volver a autenticarse.
    if (($_SESSION['origen_auth'] ?? '') !== 'mariadb-v1') return null;
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
        ? 'plataforma/panel.php' : 'aplicacion/panel.php';
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
    return is_string($token) && hash_equals(qbi_token(), $token);
}

function qbi_buscar_cuenta(string $valor, bool $porId = false): ?array
{
    // La columna se elige en el servidor; el valor siempre es un parametro.
    $condicion = $porId ? 'u.id = :valor' : 'u.email = :valor';
    $consulta = qbi_bd()->prepare(
        'SELECT u.id, u.empresa_id, u.nombre, u.email, u.password, u.rol, u.activo,
                e.nombre_empresa, e.estado AS empresa_estado
         FROM usuarios u
         LEFT JOIN empresas e ON e.id = u.empresa_id
         WHERE ' . $condicion . ' LIMIT 1'
    );
    $consulta->execute(['valor' => $valor]);
    $cuenta = $consulta->fetch();

    if (!$cuenta || (int) $cuenta['activo'] !== 1
        || !in_array($cuenta['rol'], ['superadministrador', 'administrador', 'usuario'], true)) {
        return null;
    }
    if ($cuenta['rol'] === 'superadministrador') {
        return $cuenta['empresa_id'] === null ? $cuenta : null;
    }
    if ($cuenta['empresa_id'] === null || $cuenta['empresa_estado'] !== 'Activo') {
        return null;
    }
    return $cuenta;
}

function qbi_contexto_cuenta(array $cuenta): array
{
    // Nunca guardar el hash en la sesion ni enviarlo a JavaScript.
    return [
        'id' => (string) $cuenta['id'],
        'nombre' => $cuenta['nombre'],
        'correo' => $cuenta['email'],
        'rol' => $cuenta['rol'],
        'empresa_id' => $cuenta['empresa_id'] === null ? null : (string) $cuenta['empresa_id'],
        'empresa_nombre' => $cuenta['rol'] === 'superadministrador'
            ? 'Plataforma QueryBI' : $cuenta['nombre_empresa']
    ];
}

function qbi_entrar(string $correo, string $clave): bool
{
    $correo = strtolower(trim($correo));
    if (strlen($correo) > 120 || !filter_var($correo, FILTER_VALIDATE_EMAIL)
        || $clave === '' || strlen($clave) > 4096) {
        return false;
    }
    $cuenta = qbi_buscar_cuenta($correo);
    if ($cuenta === null || !password_verify($clave, $cuenta['password'])) return false;

    session_regenerate_id(true);
    $_SESSION = [
        'origen_auth' => 'mariadb-v1',
        'usuario' => qbi_contexto_cuenta($cuenta),
        'token' => bin2hex(random_bytes(32))
    ];
    return true;
}

function qbi_refrescar_usuario(): bool
{
    $usuario = qbi_usuario();
    if ($usuario === null) return false;

    $cuenta = qbi_buscar_cuenta((string) $usuario['id'], true);
    if ($cuenta === null) {
        $_SESSION = [];
        session_regenerate_id(true);
        return false;
    }
    $_SESSION['usuario'] = qbi_contexto_cuenta($cuenta);
    return true;
}

function qbi_fuentes_contexto(): array
{
    static $fuentes = null;
    if ($fuentes !== null) return $fuentes;
    $cuenta = qbi_usuario();
    if ($cuenta === null || $cuenta['empresa_id'] === null) return $fuentes = [];

    $consulta = qbi_bd()->prepare(
        'SELECT id, nombre_conexion, habilitada FROM fuentes_datos
         WHERE empresa_id = :empresa ORDER BY nombre_conexion, id'
    );
    $consulta->execute(['empresa' => $cuenta['empresa_id']]);
    $fuentes = [];
    foreach ($consulta->fetchAll() as $fila) {
        $fuentes[] = ['id' => (string) $fila['id'], 'nombre' => $fila['nombre_conexion'],
            'habilitada' => (bool) $fila['habilitada']];
    }
    return $fuentes;
}

function qbi_registrar_error_bd(Throwable $error): void
{
    // Registrar solo el tipo y codigo; no publicar credenciales ni mensajes SQL.
    error_log('QueryBI: fallo de acceso a BD. Tipo=' . get_class($error) . '; codigo=' . $error->getCode());
}

function qbi_pagina_error(int $codigo, string $titulo, string $mensaje, string $destino): void
{
    http_response_code($codigo);
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . qbi_texto($titulo) . ' | QueryBI AI</title>';
    echo '<link rel="stylesheet" href="' . qbi_texto(qbi_url('assets/css/estilos.css')) . '"></head>';
    echo '<body><main class="contenedor tarjeta espacio-superior">';
    echo '<h1>' . qbi_texto($titulo) . '</h1><p>' . qbi_texto($mensaje) . '</p>';
    echo '<a class="boton" href="' . qbi_texto(qbi_url($destino)) . '">Volver</a>';
    echo '</main></body></html>';
    exit;
}

function qbi_requerir(array $roles): void
{
    static $revisado = false;
    if (qbi_usuario() === null) qbi_redirigir('acceso/login.php');

    if (!$revisado) {
        try {
            if (!qbi_refrescar_usuario()) qbi_redirigir('acceso/login.php');
            qbi_fuentes_contexto();
            $revisado = true;
        } catch (Throwable $error) {
            qbi_registrar_error_bd($error);
            qbi_pagina_error(503, 'Servicio no disponible',
                'No se pudo verificar tu cuenta. Intenta nuevamente en unos momentos.', 'index.html');
        }
    }
    if (!in_array(qbi_usuario()['rol'], $roles, true)) {
        qbi_pagina_error(403, 'Acceso denegado',
            'Tu cuenta no tiene permiso para abrir esta sección.', qbi_inicio());
    }
}
