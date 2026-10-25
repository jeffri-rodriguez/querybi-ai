<?php
require_once __DIR__ . '/../compartido/sesion.php';
qbi_requerir(['administrador', 'usuario']);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="QueryBI AI: prototipo académico de consulta y análisis de datos empresariales.">
        <title>Reportes | QueryBI AI</title>
        <link rel="icon" href="../../cliente/assets/imagenes/favicon.svg" type="image/svg+xml">
        <link rel="stylesheet" href="../../cliente/assets/css/estilos.css">
        <link rel="stylesheet" href="../../cliente/assets/css/aplicacion.css">
        <link rel="stylesheet" href="../../cliente/assets/css/consultas.css">
        <script src="../../cliente/assets/datos/datos-demo.js" defer></script>
        <script src="../../cliente/assets/js/principal.js" defer></script>
        <script src="../../cliente/assets/js/validaciones.js" defer></script>
        <script src="../../cliente/assets/js/graficos.js" defer></script>
        <script src="../../cliente/assets/js/reportes.js" defer></script>
    </head>
    <body>
        <noscript>
            <p class="aviso">Activa JavaScript para usar las consultas de ejemplo, los filtros y los formularios. Los enlaces de navegación siguen disponibles.</p>
        </noscript>
        <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>
        <?php require __DIR__ . '/../compartido/navegacion.php'; ?>
            <main id="contenido" class="contenido-app">
                <span class="rotulo">Archivo / reportes</span>
                <div class="cabecera-seccion">
                    <div>
                        <h1>Análisis para compartir.</h1>
                        <p class="texto-secundario">Guarda un resultado de ejemplo y descárgalo como CSV.</p>
                    </div>
                </div>
                <section class="tarjeta">
                    <h2>Reportes de ejemplo guardados</h2>
                    <div id="lista-reportes"></div>
                    <p id="mensaje-reportes" class="mensaje" role="status" hidden></p>
                </section>
                <section id="vista-previa" class="tarjeta espacio-superior" hidden>
                    <span class="rotulo">Vista previa</span>
                    <h2 id="titulo-reporte"></h2>
                    <div id="tabla-reporte"></div>
                    <div id="grafico-reporte" class="grafico"></div>
                    <button id="descargar-reporte" class="boton" type="button">Descargar CSV ↓</button>
                </section>
            </main>
        </div>
        <footer class="pie">
            <?php require dirname(__DIR__) . '/compartido/pie-marca.php'; ?>
            <small>Datos ficticios · Acceso y fuentes en MariaDB · Ejemplos sin IA · Los cambios locales no se comparten con otros usuarios.</small>
        </footer>
    </body>
</html>