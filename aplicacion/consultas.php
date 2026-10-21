<?php
require_once __DIR__ . '/../servidor/sesion.php';
qbi_requerir(['administrador', 'usuario']);
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="QueryBI AI: prototipo académico de consulta y análisis de datos empresariales.">
        <title>Consultas | QueryBI AI</title>
        <link rel="icon" href="../assets/imagenes/favicon.svg" type="image/svg+xml">
        <link rel="stylesheet" href="../assets/css/estilos.css">
        <link rel="stylesheet" href="../assets/css/aplicacion.css">
        <link rel="stylesheet" href="../assets/css/consultas.css">
        <script src="../assets/datos/datos-demo.js" defer></script>
        <script src="../assets/js/principal.js" defer></script>
        <script src="../assets/js/validaciones.js" defer></script>
        <script src="../assets/js/graficos.js" defer></script>
        <script src="../assets/js/consultas.js" defer></script>
    </head>
    <body>
        <noscript>
            <p class="aviso">Activa JavaScript para usar las consultas de ejemplo, los filtros y los formularios. Los enlaces de navegación siguen disponibles.</p>
        </noscript>
        <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>
        <?php require __DIR__ . '/../servidor/navegacion.php'; ?>
            <main id="contenido" class="contenido-app">
                <span class="rotulo">Explorador / Tienda Demo</span>
                <div class="cabecera-seccion">
                    <div>
                        <h1>Pregunta. Explora. Comprende.</h1>
                        <p class="texto-secundario">Prueba uno de los escenarios preparados para esta demostración.</p>
                    </div>
                </div>
                <section class="tarjeta">
                    <h2>Tu pregunta</h2>
                    <form id="form-consulta" action="#">
                        <div class="dos-columnas">
                            <div class="campo">
                                <label for="fuente">Fuente de datos</label>
                                <select id="fuente">
                                    <option value="tienda">Tienda Demo</option>
                                </select>
                            </div>
                            <div class="campo">
                                <label for="ejemplo">Elegir ejemplo</label>
                                <select id="ejemplo">
                                    <option value="mensual">Ventas mensuales</option>
                                    <option value="productos">Productos más vendidos</option>
                                    <option value="ciudades">Ventas por ciudad</option>
                                    <option value="vacio">Ventas de 2024 (sin registros)</option>
                                </select>
                            </div>
                        </div>
                        <div class="campo">
                            <label for="pregunta">¿Qué deseas conocer?</label>
                            <textarea id="pregunta" required></textarea>
                        </div>
                        <button id="boton-consultar" class="boton" type="submit">Consultar ejemplo →</button>
                    </form>
                    <p class="texto-secundario">Se reconocen solo las preguntas de muestra. No se genera ni se ejecuta SQL real.</p>
                    <p id="estado-consulta" class="mensaje" role="status" hidden></p>
                </section>
                <section id="resultados" class="tarjeta espacio-superior" hidden>
                    <div class="resumen-resultado">
                        <div>
                            <span class="rotulo">Resultado / datos ficticios</span>
                            <h2 id="titulo-resultado"></h2>
                            <p id="total-resultado" class="importe"></p>
                        </div>
                        <button id="guardar-reporte" class="boton boton-secundario" type="button">Guardar reporte</button>
                    </div>
                    <div class="opciones-resultado" role="tablist" aria-label="Presentación del resultado">
                        <button id="tab-tabla" role="tab" aria-selected="true" aria-controls="tabla" type="button">Tabla</button>
                        <button id="tab-grafico" role="tab" aria-selected="false" aria-controls="grafico" tabindex="-1" type="button">Gráfico</button>
                        <button id="tab-sql" role="tab" aria-selected="false" aria-controls="sql" tabindex="-1" type="button">SQL</button>
                    </div>
                    <section id="tabla" role="tabpanel" aria-labelledby="tab-tabla"></section>
                    <section id="grafico" class="grafico" role="tabpanel" aria-labelledby="tab-grafico" hidden></section>
                    <section id="sql" role="tabpanel" aria-labelledby="tab-sql" hidden>
                        <pre class="sql">
                            <code id="codigo-sql"></code>
                        </pre>
                        <p>Consulta de referencia, sin ejecución.</p>
                        <button id="copiar-sql" class="boton boton-secundario" type="button">Copiar SQL</button>
                    </section>
                    <p id="mensaje-reporte" class="mensaje" role="status" hidden></p>
                </section>
            </main>
        </div>
        <footer class="pie">
            <p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p>
            <small>Datos ficticios · Sin IA ni base de datos · Los cambios locales no se comparten con otros usuarios.</small>
        </footer>
    </body>
</html>