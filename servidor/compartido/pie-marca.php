<?php
// Fragmento visual para insertar dentro del <footer class="pie"> existente.
if (!defined('QBI_SERVIDOR')) {
    http_response_code(403);
    exit;
}
?>
<div class="pie-marca-contenido">
    <div class="pie-marca-principal">
        <a href="<?= qbi_texto(qbi_url('cliente/index.html')) ?>" aria-label="QueryBI AI, inicio">
            <img class="logo" src="<?= qbi_texto(qbi_url('cliente/assets/imagenes/logo.svg')) ?>" alt="QueryBI AI" width="190" height="44">
        </a>
        <p>Inteligencia de negocios en tus propias palabras. Conecta, pregunta y comprende.</p>
    </div>
    <div class="pie-marca-columna">
        <h2>Plataforma</h2>
        <nav class="pie-marca-enlaces" aria-label="Plataforma">
            <a href="<?= qbi_texto(qbi_url('cliente/index.html')) ?>">Inicio</a>
            <a href="<?= qbi_texto(qbi_url('cliente/paginas/producto.html')) ?>">Producto</a>
            <a href="<?= qbi_texto(qbi_url('cliente/paginas/como-funciona.html')) ?>">Cómo funciona</a>
        </nav>
    </div>
    <div class="pie-marca-columna">
        <h2>Conoce QueryBI</h2>
        <nav class="pie-marca-enlaces" aria-label="Información y ayuda">
            <a href="<?= qbi_texto(qbi_url('cliente/paginas/nosotros.html')) ?>">Nosotros</a>
            <a href="<?= qbi_texto(qbi_url('cliente/paginas/ayuda.html')) ?>">Centro de ayuda</a>
            <a href="<?= qbi_texto(qbi_url('cliente/index.html#ejemplo')) ?>">Ver un ejemplo</a>
        </nav>
    </div>
</div>
<div class="pie-marca-final">
    <p>© <?= date('Y') ?> QueryBI AI. Todos los derechos reservados.</p>
    <p>Hecho para acercar los datos a las personas.</p>
</div>
