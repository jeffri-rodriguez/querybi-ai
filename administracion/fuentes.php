<?php
require_once __DIR__ . '/../servidor/sesion.php';
qbi_requerir(['administrador']);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="QueryBI AI: prototipo académico de consulta y análisis de datos empresariales.">
        <title>Fuentes de datos | QueryBI AI</title>
        <link rel="icon" href="../assets/imagenes/favicon.svg" type="image/svg+xml">
        <link rel="stylesheet" href="../assets/css/estilos.css">
        <link rel="stylesheet" href="../assets/css/aplicacion.css">
        <link rel="stylesheet" href="../assets/css/administracion.css">
        <script src="../assets/datos/datos-demo.js" defer></script>
        <script src="../assets/js/principal.js" defer></script>
        <script src="../assets/js/validaciones.js" defer></script>
        <script src="../assets/js/graficos.js" defer></script>
        <script src="../assets/js/fuentes.js" defer></script>
    </head>
    <body>
        <noscript>
            <p class="aviso">Activa JavaScript para usar las consultas de ejemplo, los filtros y los formularios. Los enlaces de navegación siguen disponibles.</p>
        </noscript>
        <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>
        <?php require __DIR__ . '/../servidor/navegacion.php'; ?>
            <main id="contenido" class="contenido-app">
                <span class="rotulo">Administración / fuentes</span>
                <div class="cabecera-seccion">
                    <div>
                        <h1>El origen de los datos.</h1>
                        <p class="texto-secundario">Catálogo local de fuentes ficticias.</p>
                    </div>
                </div>
                <section class="tarjeta">
                    <h2>Fuentes de demostración</h2>
                    <div id="lista-fuentes"></div>
                    <div class="acciones">
                        <a href="configurar-fuente.php" class="boton">Añadir fuente de ejemplo</a>
                        <a href="esquema.php" class="boton boton-secundario">Ver esquema de Tienda Demo</a>
                    </div>
                    <p class="texto-secundario">Solo Tienda Demo tiene resultados preparados. Crear otra fuente no conecta ninguna base de datos.</p>
                </section>
            </main>
        </div>
        <footer class="pie">
            <p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p>
            <small>Datos ficticios · Sin IA ni base de datos · Los cambios locales no se comparten con otros usuarios.</small>
        </footer>
    </body>
</html>