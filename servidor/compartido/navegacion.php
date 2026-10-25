<?php
if (!defined('QBI_SERVIDOR') || qbi_usuario() === null) {
    http_response_code(403);
    exit;
}

$cuenta = qbi_usuario();

$contexto = $cuenta;
$contexto['nombre_rol'] = qbi_nombre_rol($cuenta['rol']);

$grupos = [];

if ($cuenta['rol'] === 'superadministrador') {
    $grupos['Plataforma'] = [
        ['plataforma/panel.php', 'Panel general'],
        ['plataforma/empresas.php', 'Empresas'],
        ['plataforma/administradores.php', 'Administradores']
    ];
} else {
    $grupos['Análisis'] = [
        ['aplicacion/panel.php', 'Panel'],
        ['aplicacion/consultas.php', 'Consultas'],
        ['aplicacion/ejemplos.php', 'Ejemplos'],
        ['aplicacion/historial.php', 'Historial'],
        ['aplicacion/reportes.php', 'Reportes']
    ];
}

if ($cuenta['rol'] === 'administrador') {
    $grupos['Administración'] = [
        ['administracion/fuentes.php', 'Fuentes de datos'],
        ['administracion/esquema.php', 'Esquema y permisos'],
        ['administracion/usuarios.php', 'Usuarios']
    ];
}

$grupos['Cuenta'] = [
    ['aplicacion/perfil.php', 'Mi perfil'],
    ['paginas/ayuda.html', 'Ayuda']
];
?>

<script>
    window.QBI_SESION = <?= json_encode(
        $contexto,
        JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
        | JSON_UNESCAPED_UNICODE
    ) ?>;

        window.QBI_FUENTES = <?= json_encode(
        qbi_fuentes_contexto(),
        JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
        | JSON_UNESCAPED_UNICODE
    ) ?>;
</script>

<header class="encabezado-app">
    <a href="<?= qbi_texto(qbi_url(qbi_inicio())) ?>">
        <img
            class="logo"
            src="<?= qbi_texto(qbi_url('assets/imagenes/logo.svg')) ?>"
            alt="QueryBI AI"
            width="155"
            height="40"
        >
    </a>

    <nav class="enlaces-cuenta" aria-label="Cuenta">
        <span class="etiqueta">
            <?= qbi_texto(qbi_nombre_rol($cuenta['rol'])) ?>
        </span>

        <a
            href="<?= qbi_texto(qbi_url(qbi_inicio())) ?>"
            title="Ir al panel"
        >
            <?= qbi_texto($cuenta['empresa_nombre']) ?>
        </a>

        <a href="<?= qbi_texto(qbi_url('aplicacion/perfil.php')) ?>">
            Mi perfil
        </a>

        <form
            action="<?= qbi_texto(qbi_url('acceso/salir.php')) ?>"
            method="post"
        >
            <input
                type="hidden"
                name="token"
                value="<?= qbi_texto(qbi_token()) ?>"
            >

            <button
                class="boton boton-secundario boton-pequeno"
                type="submit"
            >
                Cerrar sesión
            </button>
        </form>
    </nav>
</header>

<div class="estructura-app">
    <nav class="menu-lateral" aria-label="Menú de la aplicación">
        <?php foreach ($grupos as $titulo => $enlaces): ?>
            <h2><?= qbi_texto($titulo) ?></h2>

            <div class="grupo-menu">
                <?php foreach ($enlaces as [$ruta, $nombre]): ?>
                    <a
                        href="<?= qbi_texto(qbi_url($ruta)) ?>"
                        <?= $_SERVER['SCRIPT_NAME'] === qbi_url($ruta)
                            ? 'aria-current="page"'
                            : '' ?>
                    >
                        <?= qbi_texto($nombre) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </nav>