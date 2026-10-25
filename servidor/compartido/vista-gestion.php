<?php
if (!defined('QBI_SERVIDOR') || !isset($tipoGestion)
    || !in_array($tipoGestion, ['empresas', 'administradores', 'usuarios'], true)) {
    http_response_code(403);
    exit;
}

qbi_requerir(
    $tipoGestion === 'usuarios'
        ? ['administrador']
        : ['superadministrador']
);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= qbi_texto($tituloGestion) ?> | QueryBI AI</title>

    <link rel="icon" href="../../cliente/assets/imagenes/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../../cliente/assets/css/estilos.css">
    <link rel="stylesheet" href="../../cliente/assets/css/aplicacion.css">
    <link rel="stylesheet" href="../../cliente/assets/css/administracion.css">

    <script src="../../cliente/assets/datos/datos-demo.js" defer></script>
    <script src="../../cliente/assets/js/principal.js" defer></script>
    <script src="../../cliente/assets/js/validaciones.js" defer></script>
    <script src="../../cliente/assets/js/gestion.js" defer></script>
</head>
<body>
    <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>

    <?php require __DIR__ . '/navegacion.php'; ?>

    <main
        id="contenido"
        class="contenido-app"
        data-gestion="<?= qbi_texto($tipoGestion) ?>"
    >
        <div class="cabecera-seccion">
            <div>
                <span class="rotulo">Gestión administrativa</span>
                <h1><?= qbi_texto($tituloGestion) ?></h1>
                <p class="texto-secundario">
                    <?= qbi_texto($descripcionGestion) ?>
                </p>
            </div>

            <button class="boton" id="nuevo-registro" type="button">
                Nuevo registro
            </button>
        </div>

        <p class="aviso">
            Catálogo de demostración guardado en este navegador y separado
            por cuenta de ingreso. Crear, editar o desactivar un registro aquí
            no crea cuentas de acceso ni modifica las credenciales, los permisos
            o las sesiones del servidor. Las cuentas de ingreso siguen siendo
            las de prueba.
        </p>

        <?php if ($tipoGestion === 'usuarios'): ?>
            <p>
                <strong>Empresa:</strong>
                <?= qbi_texto(qbi_usuario()['empresa_nombre']) ?>.
                Los registros de esta pantalla tienen el rol Usuario.
            </p>
        <?php endif; ?>

        <section class="tarjeta" aria-label="Listado de registros">
            <div class="dos-columnas">
                <div class="campo">
                    <label for="buscar-registro">
                        Buscar por nombre o correo
                    </label>
                    <input
                        id="buscar-registro"
                        type="search"
                        maxlength="100"
                    >
                </div>

                <div class="campo">
                    <label for="filtro-activo">Estado</label>
                    <select id="filtro-activo">
                        <option value="todos">Todos</option>
                        <option value="activos">Activos</option>
                        <option value="inactivos">Inactivos</option>
                    </select>
                </div>
            </div>

            <p id="resumen-gestion" role="status"></p>

            <p
                id="mensaje-gestion"
                class="mensaje"
                role="status"
                hidden
            ></p>

            <div id="lista-gestion"></div>
        </section>

        <dialog
            id="dialogo-registro"
            aria-labelledby="titulo-registro"
        >
            <h2 id="titulo-registro">Nuevo registro</h2>

            <form id="form-registro">
                <div class="campo">
                    <label for="nombre-registro">Nombre</label>
                    <input
                        id="nombre-registro"
                        required
                        minlength="2"
                        maxlength="80"
                    >
                </div>

                <div class="campo">
                    <label id="etiqueta-correo" for="correo-registro">
                        Correo
                    </label>
                    <input
                        id="correo-registro"
                        type="email"
                        required
                        maxlength="120"
                    >
                </div>

                <div class="campo" id="campo-empresa" hidden>
                    <label for="empresa-registro">Empresa</label>
                    <select id="empresa-registro"></select>
                </div>

                <p
                    id="rol-registro"
                    class="texto-secundario"
                ></p>

                <p>
                    <label>
                        <input
                            id="activo-registro"
                            type="checkbox"
                            checked
                        >
                        Registro activo en el catálogo
                    </label>
                </p>

                <p
                    id="mensaje-registro"
                    class="mensaje"
                    role="alert"
                    hidden
                ></p>

                <div class="acciones">
                    <button class="boton" type="submit">
                        Guardar registro
                    </button>

                    <button
                        class="boton boton-secundario"
                        id="cancelar-registro"
                        type="button"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </dialog>

        <dialog
            id="dialogo-detalle"
            aria-labelledby="titulo-detalle"
        >
            <h2 id="titulo-detalle">Detalle del registro</h2>

            <div id="contenido-detalle"></div>

            <div class="acciones">
                <button
                    class="boton"
                    id="editar-detalle"
                    type="button"
                >
                    Editar
                </button>

                <button
                    class="boton boton-secundario"
                    id="cerrar-detalle"
                    type="button"
                >
                    Cerrar
                </button>
            </div>
        </dialog>
    </main>
    </div>

    <footer class="pie">
        <?php require dirname(__DIR__) . '/compartido/pie-marca.php'; ?>
    </footer>
</body>
</html>