<?php
require_once __DIR__ . '/../compartido/sesion.php';

qbi_requerir(['administrador']);

require_once __DIR__ . '/../compartido/fuentes.php';

$vistaFuentes = qbi_preparar_fuentes('detalle');

require __DIR__ . '/../compartido/vista-fuentes.php';