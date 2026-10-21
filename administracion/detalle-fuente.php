<?php
require_once __DIR__ . '/../servidor/sesion.php';
qbi_requerir(['administrador']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detalle de fuente | QueryBI AI</title>

    <link
        rel="icon"
        href="../assets/imagenes/favicon.svg"
        type="image/svg+xml"
    >

    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link rel="stylesheet" href="../assets/css/aplicacion.css">
    <link rel="stylesheet" href="../assets/css/administracion.css">

    <script src="../assets/datos/datos-demo.js" defer></script>
    <script src="../assets/js/principal.js" defer></script>
    <script src="../assets/js/validaciones.js" defer></script>
    <script src="../assets/js/fuentes.js" defer></script>
</head>

<body>
    <noscript>
        <p class="aviso">
            Activa JavaScript para utilizar el catálogo de demostración.
        </p>
    </noscript>

    <a class="saltar-contenido" href="#contenido">
        Saltar al contenido
    </a>

    <?php require __DIR__ . '/../servidor/navegacion.php'; ?>

    <main id="contenido" class="contenido-app">
        <a href="fuentes.php">Volver a fuentes</a>

        <div class="cabecera-seccion">
            <div>
                <span class="rotulo">
                    Administración / detalle
                </span>

                <h1 id="nombre-fuente">Detalle de fuente</h1>
            </div>
        </div>

        <p
            id="mensaje-detalle"
            class="mensaje"
            role="status"
            hidden
        ></p>

        <section id="detalle-fuente" class="tarjeta" hidden>
            <h2>Configuración registrada</h2>

            <dl class="detalle-fuente">
                <dt>Motor</dt>
                <dd id="detalle-motor"></dd>

                <dt>Servidor</dt>
                <dd id="detalle-servidor"></dd>

                <dt>Puerto</dt>
                <dd id="detalle-puerto"></dd>

                <dt>Base de datos</dt>
                <dd id="detalle-base"></dd>

                <dt>Usuario de conexión</dt>
                <dd id="detalle-usuario"></dd>

                <dt>Estado local</dt>
                <dd>
                    <span
                        id="detalle-estado"
                        class="etiqueta"
                    ></span>
                </dd>

                <dt>Descripción</dt>
                <dd id="detalle-descripcion"></dd>
            </dl>

            <div class="acciones">
                <a
                    id="editar-fuente"
                    class="boton"
                    href="configurar-fuente.php"
                >
                    Editar fuente
                </a>
            </div>

            <p class="texto-secundario">
                Estos datos describen la fuente.
                No se ha establecido una conexión real.
            </p>
        </section>

        <section
            id="seccion-esquema"
            class="tarjeta espacio-superior"
            hidden
        >
            <h2>Esquema de demostración</h2>

            <p id="descripcion-esquema"></p>

            <a
                id="ver-esquema"
                class="boton boton-secundario"
                href="esquema.php"
                hidden
            >
                Ver esquema
            </a>
        </section>
    </main>
</div>

<footer class="pie">
    <p>
        QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I
    </p>

    <small>
        Catálogo de demostración · Sin conexión con MySQL.
    </small>
</footer>
</body>
</html>