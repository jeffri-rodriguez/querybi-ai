<?php
require_once __DIR__ . '/../servidor/compartido/sesion.php';

qbi_requerir(['administrador']);

require_once __DIR__ . '/../servidor/compartido/fuentes.php';

$vistaFuentes = qbi_preparar_fuentes('detalle');

require __DIR__ . '/../servidor/compartido/vista-fuentes.php';