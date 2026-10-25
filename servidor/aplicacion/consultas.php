<?php
require_once __DIR__ . '/../compartido/sesion.php';
qbi_requerir(['administrador', 'usuario']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Consultas | QueryBI AI</title>

    <link
        rel="icon"
        href="../../cliente/assets/imagenes/favicon.svg"
        type="image/svg+xml"
    >

    <link rel="stylesheet" href="../../cliente/assets/css/estilos.css">
    <link rel="stylesheet" href="../../cliente/assets/css/aplicacion.css">
    <link rel="stylesheet" href="../../cliente/assets/css/consultas.css">

    <script src="../../cliente/assets/datos/datos-demo.js" defer></script>
    <script src="../../cliente/assets/js/principal.js" defer></script>
    <script src="../../cliente/assets/js/consultas.js" defer></script>
</head>

<body>
    <noscript>
        <p class="aviso">
            Activa JavaScript para utilizar los registros
            locales de demostración.
        </p>
    </noscript>

    <a class="saltar-contenido" href="#contenido">
        Saltar al contenido
    </a>

    <?php require __DIR__ . '/../compartido/navegacion.php'; ?>

    <main id="contenido" class="contenido-app">
        <span class="rotulo">Consultas / nueva pregunta</span>

        <div class="cabecera-seccion">
            <div>
                <h1>¿Qué necesitas conocer?</h1>

                <p class="texto-secundario">
                    Registra una pregunta asociada a una fuente habilitada.
                </p>
            </div>

            <a href="historial.php" class="boton boton-secundario">
                Ver historial
            </a>
        </div>

        <section class="tarjeta">
            <h2>Tu pregunta</h2>

            <p>
                En este avance la pregunta se guarda como pendiente.
                Todavía no se genera SQL ni se obtienen resultados.
            </p>

            <form id="form-consulta" action="#" method="post">
                <div class="campo">
                    <label for="fuente">Fuente de datos</label>

                    <select
                        id="fuente"
                        name="fuente"
                        required
                    ></select>
                </div>

                <div class="campo">
                    <label for="pregunta">
                        ¿Qué deseas conocer?
                    </label>

                    <textarea
                        id="pregunta"
                        name="pregunta"
                        minlength="5"
                        maxlength="1000"
                        placeholder="Por ejemplo: ¿Cuál fue el total vendido durante marzo de 2025?"
                        required
                    ></textarea>
                </div>

                <button
                    id="guardar-pregunta"
                    class="boton"
                    type="submit"
                    disabled
                >
                    Registrar pregunta
                </button>
            </form>

            <p
                id="estado-consulta"
                class="mensaje"
                role="status"
                hidden
            ></p>
        </section>

        <section class="tarjeta espacio-superior">
            <h2>Explorar resultados de ejemplo</h2>

            <p>
                Recorre tablas, gráficos y SQL preparados
                con datos ficticios.
            </p>

            <a
                href="ejemplos.php"
                class="boton boton-secundario"
            >
                Abrir ejemplos
            </a>
        </section>
    </main>
</div>

<footer class="pie">
    <p>
        QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I
    </p>

    <small>
        Registros locales de demostración ·
        Acceso y fuentes en MariaDB · Sin procesamiento de IA.
    </small>
</footer>
</body>
</html>