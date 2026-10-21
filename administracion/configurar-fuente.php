<?php
require_once __DIR__ . '/../servidor/sesion.php';
qbi_requerir(['administrador']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Configurar fuente | QueryBI AI</title>

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
                    Administración / configuración
                </span>

                <h1 id="titulo-fuente">Nueva fuente</h1>

                <p class="texto-secundario">
                    Registra datos ficticios para preparar el catálogo.
                </p>
            </div>
        </div>

        <section class="tarjeta formulario-administracion">
            <h2>Datos de la fuente</h2>

            <form id="form-fuente" action="#" method="post">
                <div class="dos-columnas">
                    <div class="campo">
                        <label for="nombre">Nombre visible</label>

                        <input
                            id="nombre"
                            name="nombre"
                            type="text"
                            maxlength="80"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="motor">Motor</label>

                        <select id="motor" name="motor">
                            <option value="MySQL">MySQL</option>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="servidor">
                            Servidor de ejemplo
                        </label>

                        <input
                            id="servidor"
                            name="servidor"
                            type="text"
                            maxlength="255"
                            placeholder="db.ejemplo.test"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="puerto">Puerto</label>

                        <input
                            id="puerto"
                            name="puerto"
                            type="number"
                            min="1"
                            max="65535"
                            step="1"
                            value="3306"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="base-datos">
                            Base de datos de ejemplo
                        </label>

                        <input
                            id="base-datos"
                            name="base-datos"
                            type="text"
                            maxlength="64"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="usuario">
                            Usuario de conexión de ejemplo
                        </label>

                        <input
                            id="usuario"
                            name="usuario"
                            type="text"
                            maxlength="64"
                            required
                        >
                    </div>
                </div>

                <div class="campo">
                    <label for="descripcion">
                        Descripción (opcional)
                    </label>

                    <textarea
                        id="descripcion"
                        name="descripcion"
                        maxlength="400"
                    ></textarea>
                </div>

                <div class="campo">
                    <label for="clave">
                        Contraseña de prueba (opcional)
                    </label>

                    <input
                        id="clave"
                        type="password"
                        autocomplete="new-password"
                        aria-describedby="ayuda-clave"
                    >

                    <p id="ayuda-clave" class="texto-secundario">
                        Utiliza un valor ficticio. Este campo se descarta
                        y no se almacena ni se envía.
                    </p>
                </div>

                <label>
                    <input id="habilitada" type="checkbox" checked>
                    Habilitar fuente en el catálogo local
                </label>

                <div class="acciones">
                    <button
                        id="revisar-fuente"
                        class="boton boton-secundario"
                        type="button"
                    >
                        Revisar datos
                    </button>

                    <button class="boton" type="submit">
                        Guardar fuente
                    </button>

                    <a href="fuentes.php">Cancelar</a>
                </div>
            </form>

            <p
                id="mensaje-fuente"
                class="mensaje"
                role="status"
                hidden
            ></p>
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