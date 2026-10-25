<?php
require_once __DIR__ . '/../servidor/sesion.php';

qbi_requerir(['administrador']);

require_once __DIR__ . '/../servidor/fuentes.php';

$vistaFuentes = qbi_preparar_fuentes('detalle');

require __DIR__ . '/../servidor/vista-fuentes.php';