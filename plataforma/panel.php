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

    <link
        rel="icon"
        href="../assets/imagenes/favicon.svg"
        type="image/svg+xml"
    >

    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link rel="stylesheet" href="../assets/css/aplicacion.css">

    <script src="../assets/datos/datos-demo.js" defer></script>
    <script src="../assets/js/principal.js" defer></script>
</head>

<body>
    <a class="saltar-contenido" href="#contenido">
        Saltar al contenido
    </a>

    <?php require __DIR__ . '/../servidor/navegacion.php'; ?>

    <main id="contenido" class="contenido-app">
        <div class="cabecera-seccion">
            <div>
                <span class="rotulo">
                    Administración de la plataforma
                </span>

                <h1>Panel general</h1>

                <p class="texto-secundario">
                    Bienvenido,
                    <?= qbi_texto(qbi_usuario()['nombre']) ?>.
                </p>
            </div>
        </div>

        <div class="dos-columnas">
            <section class="tarjeta">
                <h2>Empresas</h2>

                <p>
                    La configuración de prueba contiene Tienda Demo.
                </p>

                <p class="texto-secundario">
                    La gestión de empresas se incorporará
                    en los próximos bloques.
                </p>
            </section>

            <section class="tarjeta">
                <h2>Administradores de empresa</h2>

                <p>
                    Cada administrador gestiona los recursos
                    autorizados de su organización.
                </p>

                <p class="texto-secundario">
                    Este panel no concede acceso automático
                    a sus datos empresariales.
                </p>
            </section>
        </div>
    </main>
</div>

<footer class="pie">
    <p>
        QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I
    </p>
</footer>
</body>
</html>