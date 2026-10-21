<?php
require_once __DIR__ . '/../servidor/sesion.php';
qbi_requerir(['administrador']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Fuentes de datos | QueryBI AI</title>

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
        <span class="rotulo">Administración / fuentes</span>

        <div class="cabecera-seccion">
            <div>
                <h1>Fuentes de datos</h1>

                <p class="texto-secundario">
                    Organiza los orígenes que utilizará tu empresa.
                </p>
            </div>

            <a href="configurar-fuente.php" class="boton">
                Nueva fuente
            </a>
        </div>

        <section class="tarjeta">
            <h2>Catálogo de fuentes</h2>

            <div class="dos-columnas">
                <div class="campo">
                    <label for="buscar-fuente">
                        Buscar fuente
                    </label>

                    <input
                        id="buscar-fuente"
                        type="search"
                        placeholder="Nombre, base de datos o servidor"
                    >
                </div>

                <div class="campo">
                    <label for="filtrar-estado">Estado</label>

                    <select id="filtrar-estado">
                        <option value="todos">Todas</option>
                        <option value="habilitadas">Habilitadas</option>
                        <option value="deshabilitadas">
                            Deshabilitadas
                        </option>
                    </select>
                </div>
            </div>

            <p
                id="cantidad-fuentes"
                class="texto-secundario"
                role="status"
            ></p>

            <div id="lista-fuentes"></div>

            <p class="texto-secundario espacio-superior">
                Los cambios de este catálogo se guardan únicamente
                en el navegador de esta cuenta. No se comprueban conexiones.
            </p>
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