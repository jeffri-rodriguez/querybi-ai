<?php
if (!defined('QBI_SERVIDOR')) {
    http_response_code(403);
    exit;
}

function qbi_bd(): PDO
{
    static $conexion = null;

    if ($conexion instanceof PDO) {
        return $conexion;
    }

    $ruta = __DIR__ . '/configuracion.local.php';

    if (!is_file($ruta)) {
        throw new RuntimeException(
            'Falta la configuracion local de la base de datos.'
        );
    }

    $config = require $ruta;

    if (!is_array($config)) {
        throw new RuntimeException(
            'La configuracion local no es valida.'
        );
    }

    foreach (['host', 'nombre', 'usuario', 'clave'] as $campo) {
        if (!isset($config[$campo]) || !is_string($config[$campo])) {
            throw new RuntimeException(
                'La configuracion local esta incompleta.'
            );
        }
    }

    if (
        !is_int($config['puerto'] ?? null)
        || $config['puerto'] < 1
        || $config['puerto'] > 65535
        || !preg_match('/^[a-zA-Z0-9_]+$/', $config['nombre'])
    ) {
        throw new RuntimeException(
            'Puerto o nombre de base de datos no valido.'
        );
    }

    $dsn = 'mysql:host=' . $config['host']
        . ';port=' . $config['puerto']
        . ';dbname=' . $config['nombre']
        . ';charset=utf8mb4';

    $conexion = new PDO(
        $dsn,
        $config['usuario'],
        $config['clave'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_TIMEOUT => 5
        ]
    );

    return $conexion;
}