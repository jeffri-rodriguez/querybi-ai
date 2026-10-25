<?php
require_once __DIR__ . '/../compartido/sesion.php';

qbi_requerir(['superadministrador']);

$tipoGestion = 'empresas';
$tituloGestion = 'Empresas';
$descripcionGestion = 'Organizaciones registradas en el catálogo de la plataforma.';

require __DIR__ . '/../compartido/vista-gestion.php';