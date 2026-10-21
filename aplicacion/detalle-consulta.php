<?php
require_once __DIR__ . '/../servidor/sesion.php';
qbi_requerir(['administrador', 'usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detalle de consulta | QueryBI AI</title>

    <link
        rel="icon"
        href="../assets/imagenes/favicon.svg"
        type="image/svg+xml"
    >

    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link rel="stylesheet" href="../assets/css/aplicacion.css">
    <link rel="stylesheet" href="../assets/css/consultas.css">

    <script src="../assets/datos/datos-demo.js" defer></script>
    <script src="../assets/js/principal.js" defer></script>
    <script src="../assets/js/detalle-consulta.js" defer></script>
</head>

<body>
    <noscript>
        <p class="aviso">
            Activa JavaScript para utilizar los registros
            locales de demostración.
        </p>
    </noscript>

    <a class="saltar-contenido" href="#contenido">
        Saltar al contenido
    </a>

    <?php require __DIR__ . '/../servidor/navegacion.php'; ?>

    <main id="contenido" class="contenido-app">
        <a href="historial.php">Volver al historial</a>

        <div class="cabecera-seccion">
            <div>
                <h1>Detalle de consulta</h1>
            </div>
        </div>

        <p
            id="mensaje-detalle"
            class="mensaje"
            role="status"
            hidden
        ></p>

        <section id="detalle-consulta" class="tarjeta" hidden>
            <h2>Pregunta registrada</h2>

            <p
                id="pregunta-detalle"
                class="pregunta-registrada"
            ></p>

            <div class="dos-columnas">
                <div>
                    <h3>Fuente</h3>
                    <p id="fuente-detalle"></p>
                </div>

                <div>
                    <h3>Fecha</h3>
                    <p id="fecha-detalle"></p>
                </div>

                <div>
                    <h3>Tipo</h3>
                    <p id="tipo-detalle"></p>
                </div>

                <div>
                    <h3>Estado</h3>
                    <p id="estado-detalle"></p>
                </div>
            </div>

            <p id="explicacion-detalle" class="aviso"></p>

            <div class="acciones">
                <a
                    id="abrir-ejemplo"
                    href="ejemplos.php"
                    class="boton"
                    hidden
                >
                    Ver ejemplo preparado
                </a>

                <a
                    href="consultas.php"
                    class="boton boton-secundario"
                >
                    Nueva pregunta
                </a>
            </div>
        </section>
    </main>
</div>

<footer class="pie">
    <p>
        QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I
    </p>

    <small>
        Registros locales de demostración ·
        Sin procesamiento de IA ni conexión con MySQL.
    </small>
</footer>
</body>
</html>