<?php
require_once __DIR__ . '/../servidor/compartido/sesion.php';

qbi_requerir(['administrador']);

$tipoGestion = 'usuarios';
$tituloGestion = 'Usuarios de empresa';
$descripcionGestion = 'Usuarios asociados a tu empresa en esta demostración.';

require __DIR__ . '/../servidor/compartido/vista-gestion.php';