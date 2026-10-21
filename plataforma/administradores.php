<?php
require_once __DIR__ . '/../servidor/sesion.php';

qbi_requerir(['superadministrador']);

$tipoGestion = 'administradores';
$tituloGestion = 'Administradores de empresa';
$descripcionGestion = 'Responsables asociados a las empresas del catálogo.';

require __DIR__ . '/../servidor/vista-gestion.php';