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
        <title>Usuarios y roles | QueryBI AI</title>
        <link rel="icon" href="../assets/imagenes/favicon.svg" type="image/svg+xml">
        <link rel="stylesheet" href="../assets/css/estilos.css">
        <link rel="stylesheet" href="../assets/css/aplicacion.css">
        <link rel="stylesheet" href="../assets/css/administracion.css">
        <script src="../assets/datos/datos-demo.js" defer></script>
        <script src="../assets/js/principal.js" defer></script>
        <script src="../assets/js/validaciones.js" defer></script>
        <script src="../assets/js/graficos.js" defer></script>
        <script src="../assets/js/usuarios.js" defer></script>
    </head>
    <body>
        <noscript>
            <p class="aviso">Activa JavaScript para usar las consultas de ejemplo, los filtros y los formularios. Los enlaces de navegación siguen disponibles.</p>
        </noscript>
        <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>
        <?php require __DIR__ . '/../servidor/navegacion.php'; ?>
            <main id="contenido" class="contenido-app">
                <span class="rotulo">Administración / equipo</span>
                <div class="cabecera-seccion">
                    <div>
                        <h1>Un lugar para cada rol.</h1>
                        <p class="texto-secundario">Gestiona usuarios ficticios de esta demostración.</p>
                    </div>
                </div>
                <section class="tarjeta">
                    <div class="acciones">
                        <button id="nuevo-usuario" class="boton" type="button">Añadir usuario</button>
                    </div>
                    <div id="lista-usuarios" class="espacio-superior"></div>
                    <p id="mensaje-usuarios" class="mensaje" role="status" hidden></p>
                </section>
                <div class="dos-columnas espacio-superior">
                    <section class="tarjeta">
                        <h2>Usuario</h2>
                        <p>Consulta ejemplos y revisa el historial y los reportes.</p>
                    </section>
                    <section class="tarjeta">
                        <h2>Administrador</h2>
                        <p>También configura fuentes, esquemas y usuarios ficticios.</p>
                    </section>
                </div>
                <p class="texto-secundario">Los roles son demostrativos. Todas las páginas siguen siendo públicas; no hay autorización real.</p>
                <dialog id="dialogo-usuario" aria-labelledby="titulo-usuario">
                    <h2 id="titulo-usuario">Nuevo usuario</h2>
                    <form id="form-usuario" action="#">
                        <div class="campo">
                            <label for="nombre-usuario">Nombre</label>
                            <input type="text" id="nombre-usuario" name="nombre-usuario" value="" placeholder="">
                        </div>
                        <div class="campo">
                            <label for="correo-usuario">Correo de ejemplo</label>
                            <input type="email" id="correo-usuario" name="correo-usuario" value="" placeholder="">
                        </div>
                        <div class="campo">
                            <label for="rol-usuario">Rol</label>
                            <select id="rol-usuario" name="rol-usuario">
                                <option>Usuario</option>
                                <option>Administrador</option>
                            </select>
                        </div>
                        <label>
                            <input id="activo-usuario" type="checkbox" checked> Usuario activo</label>
                        <div class="acciones">
                            <button class="boton" type="submit">Guardar usuario</button>
                            <button id="cancelar-usuario" class="boton boton-secundario" type="button">Cancelar</button>
                        </div>
                    </form>
                    <p id="mensaje-usuario" class="mensaje" role="status" hidden></p>
                </dialog>
            </main>
        </div>
        <footer class="pie">
            <p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p>
            <small>Datos ficticios · Sin IA ni base de datos · Los cambios locales no se comparten con otros usuarios.</small>
        </footer>
    </body>
</html>