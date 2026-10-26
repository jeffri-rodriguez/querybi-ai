
<?php
define('QBI_SERVIDOR', true);

$config = require __DIR__ . '/servidor/configuracion.local.php';

try {
    $pdo = new PDO(
        'mysql:host=' . $config['host'] .
        ';port=' . $config['puerto'] .
        ';dbname=' . $config['nombre'] .
        ';charset=utf8mb4',
        $config['usuario'],
        $config['clave'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    echo 'CONEXIÓN CORRECTA: QueryBI AI se conecta a MariaDB.';
} catch (Throwable $e) {
    http_response_code(500);
    echo 'ERROR DE CONEXIÓN. Revisa la configuración local de MariaDB.';
}
