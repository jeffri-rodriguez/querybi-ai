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
        <title>Esquema y permisos | QueryBI AI</title>
        <link rel="icon" href="../assets/imagenes/favicon.svg" type="image/svg+xml">
        <link rel="stylesheet" href="../assets/css/estilos.css">
        <link rel="stylesheet" href="../assets/css/aplicacion.css">
        <link rel="stylesheet" href="../assets/css/administracion.css">
        <script src="../assets/datos/datos-demo.js" defer></script>
        <script src="../assets/js/principal.js" defer></script>
        <script src="../assets/js/validaciones.js" defer></script>
        <script src="../assets/js/graficos.js" defer></script>
        <script src="../assets/js/esquema.js" defer></script>
    </head>
    <body>
        <noscript>
            <p class="aviso">Activa JavaScript para usar las consultas de ejemplo, los filtros y los formularios. Los enlaces de navegación siguen disponibles.</p>
        </noscript>
        <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>
        <?php require __DIR__ . '/../servidor/navegacion.php'; ?>
            <main id="contenido" class="contenido-app">
                <div class="cabecera-seccion">
                    <div>
                        <h1>Esquema y permisos</h1>
                        <p class="texto-secundario">Fuente de ejemplo: Tienda Demo.</p>
                    </div>
                </div>
                <p class="aviso">Las selecciones se guardan en este navegador como ejemplo. No aplican permisos reales sobre los datos.</p>
                <div class="dos-columnas">
                    <section class="tarjeta">
                        <h2>Tablas disponibles</h2>
                        <ul class="lista-permisos">
                            <li>
                                <label>
                                    <input type="checkbox" checked> clientes</label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" checked> ventas</label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" checked> productos</label>
                            </li>
                            <li>
                                <label>
                                    <input type="checkbox" checked> detalle_venta</label>
                            </li>
                        </ul>
                    </section>
                    <section class="tarjeta">
                        <h2>Relaciones</h2>
                        <ul class="lista-relaciones">
                            <li>clientes.id → ventas.id_cliente</li>
                            <li>ventas.id → detalle_venta.id_venta</li>
                            <li>productos.id → detalle_venta.id_producto</li>
                        </ul>
                        <p>Un cliente puede tener varias ventas. Cada venta tiene uno o más detalles.</p>
                    </section>
                </div>
                <section class="tarjeta espacio-superior">
                    <h2>Campos autorizados</h2>
                    <div class="tabla-contenedor">
                        <table>
                            <caption>Tabla ventas</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Habilitar</th>
                                    <th scope="col">Campo</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="checkbox" checked aria-label="Habilitar id">
                                    </td>
                                    <td>id</td>
                                    <td>INT</td>
                                    <td>Identificador de venta</td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" checked aria-label="Habilitar fecha">
                                    </td>
                                    <td>fecha</td>
                                    <td>DATE</td>
                                    <td>Fecha de venta</td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" checked aria-label="Habilitar total">
                                    </td>
                                    <td>total</td>
                                    <td>DECIMAL</td>
                                    <td>Importe en soles</td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" checked aria-label="Habilitar id_cliente">
                                    </td>
                                    <td>id_cliente</td>
                                    <td>INT</td>
                                    <td>Cliente asociado</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="acciones">
                        <button id="guardar-esquema" class="boton" type="button">Guardar selección local</button>
                        <a href="fuentes.php">Volver a fuentes</a>
                    </div>
                </section>
                <p id="mensaje-esquema" class="mensaje" role="status" hidden></p>
            </main>
        </div>
        <footer class="pie">
            <p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p>
            <small>Datos ficticios · Sin IA ni base de datos · Los cambios locales no se comparten con otros usuarios.</small>
        </footer>
    </body>
</html>