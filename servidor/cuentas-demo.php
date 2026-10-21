<?php
if (!defined('QBI_SERVIDOR')) {
    http_response_code(403);
    exit;
}

// Cuentas fijas para probar el acceso. No son registros de MySQL.
return [
    [
        'id' => 'super-demo',
        'nombre' => 'Superadministrador Demo',
        'correo' => 'superadmin@querybi.test',
        'rol' => 'superadministrador',
        'empresa_id' => null,
        'empresa_nombre' => 'Plataforma QueryBI',
        'hash' => '$2y$10$17uWgpQN41FBKbd/IxRDG.Fm8RKGGgnSsgSSFqcgTcvrgccmYZn5G'
    ],
    [
        'id' => 'admin-tienda',
        'nombre' => 'Administrador Demo',
        'correo' => 'admin@tienda.test',
        'rol' => 'administrador',
        'empresa_id' => 'tienda',
        'empresa_nombre' => 'Tienda Demo',
        'hash' => '$2y$10$O3uNo1UaKMU.sbNaus97pe/1u9yYnRUekXIqxoLYWKhfq4cTszw.W'
    ],
    [
        'id' => 'usuario-tienda',
        'nombre' => 'Usuario Demo',
        'correo' => 'usuario@tienda.test',
        'rol' => 'usuario',
        'empresa_id' => 'tienda',
        'empresa_nombre' => 'Tienda Demo',
        'hash' => '$2y$10$3SMLsJx3gBL/b0hMTYIvBOcW5gGSuklIBunhLYuyXgbCmBgtv0Hfy'
    ]
];