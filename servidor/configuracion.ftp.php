<?php
if (!defined('QBI_SERVIDOR')) {
    http_response_code(403);
    exit;
}

// Copiar como configuracion.local.php y ajustar solo ese archivo.
// Valores habituales de XAMPP local; no utilizar en produccion.
return [
    'host' => '127.0.0.1',
    'puerto' => 3306,
    'nombre' => 'querybi_ai_pagina',
    'usuario' => 'root',
    'clave' => ''
];