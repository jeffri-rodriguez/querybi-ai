<?php
require_once __DIR__ . '/../servidor/compartido/sesion.php';
qbi_requerir(['administrador', 'usuario']);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="QueryBI AI: prototipo académico de consulta y análisis de datos empresariales.">
        <title>Historial | QueryBI AI</title>
        <link rel="icon" href="../assets/imagenes/favicon.svg" type="image/svg+xml">
        <link rel="stylesheet" href="../assets/css/estilos.css">
        <link rel="stylesheet" href="../assets/css/aplicacion.css">
        <script src="../assets/datos/datos-demo.js" defer></script>
        <script src="../assets/js/principal.js" defer></script>
        <script src="../assets/js/validaciones.js" defer></script>
        <script src="../assets/js/graficos.js" defer></script>
        <script src="../assets/js/historial.js" defer></script>
    </head>
    <body>
        <noscript>
            <p class="aviso">Activa JavaScript para usar las consultas de ejemplo, los filtros y los formularios. Los enlaces de navegación siguen disponibles.</p>
        </noscript>
        <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>
        <?php require __DIR__ . '/../servidor/compartido/navegacion.php'; ?>
            <main id="contenido" class="contenido-app">
                <span class="rotulo">Archivo / historial</span>

                <div class="cabecera-seccion">
                    <div>
                        <h1>Vuelve a tus preguntas.</h1>

                        <p class="texto-secundario">
                            Preguntas pendientes y ejemplos guardados
                            en este navegador.
                        </p>
                    </div>
                </div>

                <section class="tarjeta">
                    <div class="dos-columnas">
                        <div class="campo">
                            <label for="buscar">Buscar por pregunta</label>

                            <input
                                id="buscar"
                                type="search"
                                placeholder="Escribe para filtrar"
                            >
                        </div>

                        <div class="campo">
                            <label for="filtro-estado">Estado</label>

                            <select id="filtro-estado">
                                <option value="Todos">Todos</option>
                                <option value="Pendiente">Pendiente</option>

                                <option value="Ejemplo listo">
                                    Ejemplo listo
                                </option>

                                <option value="Ejemplo sin resultados">
                                    Ejemplo sin resultados
                                </option>
                            </select>
                        </div>
                    </div>

                    <p
                        id="cantidad-historial"
                        class="texto-secundario"
                        role="status"
                    ></p>

                    <div id="lista-historial"></div>

                    <div class="acciones">
                        <a href="consultas.php" class="boton">
                            Nueva pregunta
                        </a>

                        <a href="ejemplos.php">Explorar ejemplos</a>
                    </div>
                </section>
            </main>
        </div>
        <footer class="pie">
            <p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p>
            <small>Datos ficticios · Acceso y fuentes en MariaDB · Ejemplos sin IA · Los cambios locales no se comparten con otros usuarios.</small>
        </footer>
    </body>
</html>