<?php
if (!defined('QBI_SERVIDOR')) {
    http_response_code(403);
    exit;
}

return [
    'host' => '127.0.0.1',
    'puerto' => 3306,
    'nombre' => 'querybi_ai_pagina',
    'usuario' => 'root',
    'clave' => ''
];