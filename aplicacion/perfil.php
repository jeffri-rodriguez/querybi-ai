<?php
require_once __DIR__ . '/../servidor/sesion.php';

qbi_requerir(['superadministrador', 'administrador', 'usuario']);

$cuenta = qbi_usuario();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mi perfil | QueryBI AI</title>

    <link rel="icon" href="../assets/imagenes/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../assets/css/estilos.css">
    <link rel="stylesheet" href="../assets/css/aplicacion.css">

    <script src="../assets/js/principal.js" defer></script>
    <script src="../assets/js/perfil.js" defer></script>
</head>
<body>
    <noscript>
        <p class="aviso">
            Activa JavaScript para modificar las preferencias locales.
            Los datos de tu cuenta siguen disponibles.
        </p>
    </noscript>

    <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>

    <?php require __DIR__ . '/../servidor/navegacion.php'; ?>

    <main id="contenido" class="contenido-app">
        <div class="cabecera-seccion">
            <div>
                <span class="rotulo">Cuenta</span>
                <h1>Mi perfil</h1>
                <p class="texto-secundario">
                    Información de tu sesión y preferencias de este navegador.
                </p>
            </div>
        </div>

        <div class="dos-columnas">
            <section class="tarjeta">
                <h2>Cuenta autenticada</h2>

                <p>
                    <strong>Nombre:</strong>
                    <?= qbi_texto($cuenta['nombre']) ?>
                </p>

                <p>
                    <strong>Correo:</strong>
                    <?= qbi_texto($cuenta['correo']) ?>
                </p>

                <p>
                    <strong>Rol:</strong>
                    <?= qbi_texto(qbi_nombre_rol($cuenta['rol'])) ?>
                </p>

                <p>
                    <strong>Ámbito:</strong>
                    <?= qbi_texto($cuenta['empresa_nombre']) ?>
                </p>

                <p class="texto-secundario">
                    Estos datos corresponden a la cuenta con la que
                    iniciaste sesión.
                </p>
            </section>

            <section class="tarjeta">
                <h2>Nombre de presentación</h2>

                <p>
                    Personaliza el nombre utilizado en el saludo de tu panel.
                    Se guarda para esta cuenta en este navegador.
                    No cambia los datos de ingreso.
                </p>

                <form id="form-perfil">
                    <div class="campo">
                        <label for="nombre">Nombre de presentación</label>

                        <input
                            id="nombre"
                            type="text"
                            required
                            minlength="2"
                            maxlength="80"
                            value="<?= qbi_texto($cuenta['nombre']) ?>"
                            disabled
                        >
                    </div>

                    <button
                        id="guardar-perfil"
                        class="boton"
                        type="submit"
                        disabled
                    >
                        Guardar nombre
                    </button>
                </form>

                <p
                    id="mensaje-perfil"
                    class="mensaje"
                    role="status"
                    hidden
                ></p>
            </section>
        </div>

        <section class="tarjeta espacio-superior">
            <h2>Contraseña y recuperación</h2>

            <p>
                Esta versión utiliza cuentas de demostración registradas en MariaDB.
                El cambio de contraseña y la recuperación por correo
                estarán disponibles cuando se integre la gestión de cuentas.
            </p>

            <a href="../acceso/recuperar-contrasena.html">
                Consultar información de acceso
            </a>
        </section>

        <section class="tarjeta espacio-superior">
            <h2>Restablecer mi demostración</h2>

            <p>
                Elimina los registros locales de esta cuenta:
                preguntas, historial, reportes, catálogos,
                selecciones de esquema y preferencias.
                Los datos de otras cuentas se conservan.
            </p>

            <p class="texto-secundario">
                Se recuperan los valores iniciales.
                Tu sesión y tus credenciales no cambian.
            </p>

            <button
                id="restablecer"
                class="boton boton-secundario"
                type="button"
                disabled
            >
                Restablecer mis datos locales
            </button>

            <p
                id="mensaje-restablecer"
                class="mensaje"
                role="status"
                hidden
            ></p>
        </section>
    </main>
    </div>

    <footer class="pie">
        <p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p>
    </footer>
</body>
</html>