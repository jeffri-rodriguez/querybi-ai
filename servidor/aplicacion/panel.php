<?php
require_once __DIR__ . '/../compartido/sesion.php';
qbi_requerir(['administrador', 'usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="QueryBI AI: prototipo académico de consulta y análisis de datos empresariales."
    >

    <title>Panel | QueryBI AI</title>

    <link
        rel="icon"
        href="../../cliente/assets/imagenes/favicon.svg"
        type="image/svg+xml"
    >

    <link rel="stylesheet" href="../../cliente/assets/css/estilos.css">
    <link rel="stylesheet" href="../../cliente/assets/css/aplicacion.css">

    <script src="../../cliente/assets/datos/datos-demo.js" defer></script>
    <script src="../../cliente/assets/js/principal.js" defer></script>
    <script src="../../cliente/assets/js/validaciones.js" defer></script>
    <script src="../../cliente/assets/js/graficos.js" defer></script>
    <script src="../../cliente/assets/js/panel.js" defer></script>
</head>

<body>
    <noscript>
        <p class="aviso">
            Activa JavaScript para usar las consultas de ejemplo,
            los filtros y los formularios.
            Los enlaces de navegación siguen disponibles.
        </p>
    </noscript>

    <a class="saltar-contenido" href="#contenido">
        Saltar al contenido
    </a>

    <?php require __DIR__ . '/../compartido/navegacion.php'; ?>

    <main id="contenido" class="contenido-app">
        <span class="rotulo">
            Espacio de trabajo / resumen
        </span>

        <div class="cabecera-seccion">
            <div>
                <h1>Una pregunta para empezar.</h1>

                <p class="texto-secundario">
                    Tus registros y reportes de demostración.
                </p>
            </div>
        </div>

        <section class="tarjeta resumen-panel">
            <h2 id="saludo">Bienvenido</h2>

            <p>
                Registra preguntas pendientes
                o explora resultados preparados.
            </p>

            <div class="acciones">
                <a href="consultas.php" class="boton">
                    Nueva pregunta
                </a>

                <a
                    href="ejemplos.php"
                    class="boton boton-secundario"
                >
                    Explorar ejemplos
                </a>
            </div>
        </section>

        <div class="tres-columnas espacio-superior">
            <section class="tarjeta">
                <span class="rotulo">
                    01 / Registros
                </span>

                <p id="cantidad-consultas" class="importe">
                    0
                </p>

                <a href="historial.php">
                    Abrir historial
                </a>
            </section>

            <section class="tarjeta">
                <span class="rotulo">
                    02 / Pendientes
                </span>

                <p id="cantidad-pendientes" class="importe">
                    0
                </p>

                <a href="historial.php?estado=Pendiente">
                    Ver preguntas pendientes
                </a>
            </section>

            <section class="tarjeta">
                <span class="rotulo">
                    03 / Reportes de ejemplo
                </span>

                <p id="cantidad-reportes" class="importe">
                    0
                </p>

                <a href="reportes.php">
                    Abrir reportes
                </a>
            </section>
        </div>

        <section class="tarjeta espacio-superior">
            <h2>Actividad reciente</h2>

            <div id="actividad"></div>
        </section>
    </main>
</div>

<footer class="pie">
    <p>
        QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I
    </p>

    <small>
        Datos ficticios · Acceso y fuentes en MariaDB · Ejemplos sin IA ·
        Los cambios locales no se comparten con otros usuarios.
    </small>
</footer>
</body>
</html>