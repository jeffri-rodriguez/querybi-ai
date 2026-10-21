<?php
require_once __DIR__ . '/../servidor/sesion.php';

qbi_requerir(['superadministrador']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Plataforma | QueryBI AI</title>

    <link rel="icon" href="../assets/imagenes/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link rel="stylesheet" href="../assets/css/aplicacion.css">

    <script src="../assets/datos/datos-demo.js" defer></script>
    <script src="../assets/js/principal.js" defer></script>
    <script src="../assets/js/plataforma.js" defer></script>
</head>
<body>
    <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>

    <?php require __DIR__ . '/../servidor/navegacion.php'; ?>

    <main id="contenido" class="contenido-app">
        <div class="cabecera-seccion">
            <div>
                <span class="rotulo">Administración de la plataforma</span>
                <h1>Panel general</h1>

                <p class="texto-secundario">
                    Bienvenido, <?= qbi_texto(qbi_usuario()['nombre']) ?>.
                </p>
            </div>
        </div>

        <p class="aviso">
            Los indicadores corresponden al catálogo de demostración de esta
            cuenta en este navegador. Sus cambios no crean ni bloquean cuentas
            de ingreso.
        </p>

        <div class="tres-columnas">
            <section class="tarjeta">
                <h2>Empresas</h2>
                <p class="importe" id="cantidad-empresas">0</p>
                <a href="empresas.php">Gestionar empresas</a>
            </section>

            <section class="tarjeta">
                <h2>Empresas activas</h2>
                <p class="importe" id="cantidad-empresas-activas">0</p>

                <p class="texto-secundario">
                    Estado dentro del catálogo local.
                </p>
            </section>

            <section class="tarjeta">
                <h2>Administradores de empresa</h2>
                <p class="importe" id="cantidad-administradores">0</p>
                <a href="administradores.php">Gestionar administradores</a>
            </section>
        </div>
    </main>
    </div>

    <footer class="pie">
        <p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p>
    </footer>
</body>
</html>