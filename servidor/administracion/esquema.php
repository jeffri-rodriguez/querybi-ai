<?php
require_once __DIR__ . '/../compartido/sesion.php';

qbi_requerir(['administrador']);
require_once __DIR__ . '/../compartido/fuentes.php';
try {
    $esquemas = qbi_fuentes_esquemas();
} catch (Throwable $error) {
    qbi_registrar_error_bd($error);
    qbi_pagina_error(503, 'Servicio no disponible', 'No se pudo cargar el esquema.', 'servidor/administracion/fuentes.php');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Esquema y permisos | QueryBI AI</title>

    <link rel="icon" href="../../cliente/assets/imagenes/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../../cliente/assets/css/estilos.css">
    <link rel="stylesheet" href="../../cliente/assets/css/aplicacion.css">
    <link rel="stylesheet" href="../../cliente/assets/css/administracion.css">

    <script src="../../cliente/assets/datos/datos-demo.js" defer></script>
    <script src="../../cliente/assets/datos/esquema-demo.js" defer></script>
    <script src="../../cliente/assets/js/principal.js" defer></script>
    <script src="../../cliente/assets/js/esquema.js" defer></script>
</head>
<body>
    <noscript>
        <p class="aviso">
            Activa JavaScript para consultar el esquema registrado.
        </p>
    </noscript>

    <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>

    <?php require __DIR__ . '/../compartido/navegacion.php'; ?>
    <script>
        window.QBI_ESQUEMAS = <?= json_encode((object) $esquemas,
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
    </script>

    <main id="contenido" class="contenido-app">
        <div class="cabecera-seccion">
            <div>
                <h1>Esquema y permisos</h1>
                <p class="texto-secundario">
                    Metadatos registrados; selección local de tablas y campos.
                </p>
            </div>

            <a href="fuentes.php">Volver a fuentes</a>
        </div>

        <p class="aviso">
            Esta selección se guarda en este navegador. No modifica la estructura
            de una base de datos ni aplica permisos a las consultas.
            Los resultados de Ejemplos siguen siendo datos preparados.
        </p>

        <form
            id="form-fuente-esquema"
            action="esquema.php"
            method="get"
            class="tarjeta"
        >
            <div class="campo">
                <label for="fuente-esquema">Fuente del catálogo</label>
                <select
                    id="fuente-esquema"
                    name="fuente"
                    required
                ></select>
            </div>

            <button
                id="abrir-esquema"
                class="boton boton-secundario"
                type="submit"
            >
                Ver esquema
            </button>
        </form>

        <p
            id="estado-esquema"
            class="mensaje"
            role="status"
            hidden
        ></p>

        <section
            id="editor-esquema"
            class="espacio-superior"
            hidden
        >
            <h2 id="nombre-fuente-esquema"></h2>

            <p id="estado-fuente-esquema"></p>

            <p class="texto-secundario">
                Al desmarcar una tabla, sus campos dejan de contar en la
                selección. Sus marcas se conservan para cuando vuelvas
                a seleccionarla.
            </p>

            <div id="tablas-esquema" class="dos-columnas"></div>

            <section class="tarjeta espacio-superior">
                <h3>Relaciones de referencia</h3>

                <div id="relaciones-esquema"></div>

                <p class="texto-secundario">
                    Estas relaciones describen el esquema registrado y no cambian
                    al marcar las casillas.
                </p>
            </section>

            <p id="resumen-esquema" role="status"></p>
            <p id="cambios-esquema" role="status"></p>

            <div class="acciones">
                <button
                    id="guardar-esquema"
                    class="boton"
                    type="button"
                >
                    Guardar selección local
                </button>

                <button
                    id="descartar-esquema"
                    class="boton boton-secundario"
                    type="button"
                >
                    Descartar cambios
                </button>
            </div>
        </section>
    </main>
    </div>

    <footer class="pie">
        <p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p>
    </footer>
</body>
</html>