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
        <title>Configurar fuente | QueryBI AI</title>
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
                <span class="rotulo">Administración / conexión de ejemplo</span>
                <div class="cabecera-seccion">
                    <div>
                        <h1>Configura una fuente.</h1>
                        <p class="texto-secundario">Formulario local; sin comunicación con bases de datos.</p>
                    </div>
                </div>
                <section class="tarjeta formulario-administracion">
                    <h2 id="titulo-fuente">Nueva fuente de ejemplo</h2>
                    <p class="aviso">Usa datos ficticios. La contraseña no se almacena ni se transmite.</p>
                    <form id="form-fuente" action="#">
                        <div class="dos-columnas">
                            <div class="campo">
                                <label for="nombre">Nombre visible</label>
                                <input type="text" id="nombre" name="nombre" value="" placeholder="">
                            </div>
                            <div class="campo">
                                <label for="motor">Motor</label>
                                <select id="motor" name="motor">
                                    <option>MySQL</option>
                                </select>
                            </div>
                            <div class="campo">
                                <label for="servidor">Servidor ficticio</label>
                                <input type="text" id="servidor" name="servidor" value="" placeholder="db.ejemplo.test">
                            </div>
                            <div class="campo">
                                <label for="puerto">Puerto</label>
                                <input type="number" id="puerto" name="puerto" value="3306" placeholder="">
                            </div>
                            <div class="campo">
                                <label for="base-datos">Base de datos ficticia</label>
                                <input type="text" id="base-datos" name="base-datos" value="" placeholder="">
                            </div>
                            <div class="campo">
                                <label for="usuario">Usuario de ejemplo</label>
                                <input type="text" id="usuario" name="usuario" value="" placeholder="">
                            </div>
                        </div>
                        <div class="campo">
                            <label for="clave">Contraseña de prueba (opcional)</label>
                            <input type="password" id="clave" name="clave" value="" placeholder="">
                        </div>
                        <label>
                            <input id="habilitada" type="checkbox" checked> Habilitar fuente en la demostración</label>
                        <div class="acciones">
                            <button id="probar-conexion" type="button" class="boton boton-secundario">Revisar datos del formulario</button>
                            <button type="submit" class="boton">Guardar fuente local</button>
                        </div>
                    </form>
                    <p id="mensaje-fuente" class="mensaje" role="status" hidden></p>
                    <div class="acciones">
                        <a href="fuentes.php">Volver a fuentes</a>
                    </div>
                </section>
            </main>
        </div>
        <footer class="pie">
            <p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p>
            <small>Datos ficticios · Sin IA ni base de datos · Los cambios locales no se comparten con otros usuarios.</small>
        </footer>
    </body>
</html>