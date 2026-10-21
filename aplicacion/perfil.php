<?php
require_once __DIR__ . '/../servidor/sesion.php';
qbi_requerir(['superadministrador', 'administrador', 'usuario']);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="QueryBI AI: prototipo académico de consulta y análisis de datos empresariales.">
        <title>Mi perfil | QueryBI AI</title>
        <link rel="icon" href="../assets/imagenes/favicon.svg" type="image/svg+xml">
        <link rel="stylesheet" href="../assets/css/estilos.css">
        <link rel="stylesheet" href="../assets/css/aplicacion.css">
        <script src="../assets/datos/datos-demo.js" defer></script>
        <script src="../assets/js/principal.js" defer></script>
        <script src="../assets/js/validaciones.js" defer></script>
        <script src="../assets/js/graficos.js" defer></script>
        <script src="../assets/js/perfil.js" defer></script>
    </head>
    <body>
        <noscript>
            <p class="aviso">Activa JavaScript para usar las consultas de ejemplo, los filtros y los formularios. Los enlaces de navegación siguen disponibles.</p>
        </noscript>
        <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>
        <?php require __DIR__ . '/../servidor/navegacion.php'; ?>
            <main id="contenido" class="contenido-app">
                <span class="rotulo">Cuenta / demostración</span>
                <div class="cabecera-seccion">
                    <div>
                        <h1>Tu espacio, a tu manera.</h1>
                        <p class="texto-secundario">Datos locales para personalizar la demostración.</p>
                    </div>
                </div>
                <div class="dos-columnas">
                    <section class="tarjeta">
                        <h2>Datos de ejemplo</h2>
                        <form id="form-perfil" action="#">
                            <div class="campo">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" value="" placeholder="">
                            </div>
                            <div class="campo">
                                <label for="correo">Correo de la cuenta (solo lectura)</label>
                                <input type="email" id="correo" name="correo" value="" placeholder="">
                            </div>
                            <p id="rol-perfil"></p>
                            <button class="boton" type="submit">Guardar perfil local</button>
                        </form>
                        <p id="mensaje-perfil" class="mensaje" role="status" hidden></p>
                    </section>
                    <section class="tarjeta">
                        <h2>Prueba de contraseñas</h2>
                        <p>Solo comprueba coincidencia y longitud. No guarda ni cambia contraseñas. Usa valores ficticios.</p>
                        <form id="form-clave" action="#">
                            <div class="campo">
                                <label for="nueva">Nueva contraseña de prueba</label>
                                <input type="password" id="nueva" name="nueva" value="" placeholder="">
                            </div>
                            <div class="campo">
                                <label for="confirmacion">Repetir contraseña</label>
                                <input type="password" id="confirmacion" name="confirmacion" value="" placeholder="">
                            </div>
                            <button class="boton boton-secundario" type="submit">Comprobar campos</button>
                        </form>
                        <p id="mensaje-clave" class="mensaje" role="status" hidden></p>
                    </section>
                </div>
                <section class="tarjeta espacio-superior">
                    <h2>Reiniciar la demostración</h2>
                    <p>Elimina únicamente los datos locales de QueryBI AI de este navegador.</p>
                    <button id="restablecer" class="boton boton-secundario" type="button">Restablecer datos de ejemplo</button>
                </section>
            </main>
        </div>
        <footer class="pie">
            <p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p>
            <small>Datos ficticios · Sin IA ni base de datos · Los cambios locales no se comparten con otros usuarios.</small>
        </footer>
    </body>
</html>