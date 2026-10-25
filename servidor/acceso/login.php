<?php
require_once __DIR__ . '/../compartido/sesion.php';

$error = '';
$correo = '';

try {
    if (qbi_usuario() !== null && qbi_refrescar_usuario()) {
        qbi_redirigir(qbi_inicio());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $correo = is_string($_POST['correo'] ?? null)
            ? trim($_POST['correo'])
            : '';

        $clave = is_string($_POST['clave'] ?? null)
            ? $_POST['clave']
            : '';

        if (!qbi_token_valido($_POST['token'] ?? null)) {
            http_response_code(400);

            $error = 'La solicitud no es válida. Vuelve a intentarlo.';
        } elseif (qbi_entrar($correo, $clave)) {
            qbi_redirigir(qbi_inicio());
        } else {
            $error = 'No se pudo iniciar sesión. Revisa tus credenciales y que tu cuenta esté habilitada.';
        }
    }
} catch (Throwable $errorBD) {
    qbi_registrar_error_bd($errorBD);

    http_response_code(503);

    $error = 'No se pudo conectar con el servicio de acceso. Inténtalo nuevamente en unos minutos.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ingresar | QueryBI AI</title>

    <link
        rel="icon"
        href="../../cliente/assets/imagenes/favicon.svg"
        type="image/svg+xml"
    >

    <link rel="stylesheet" href="../../cliente/assets/css/estilos.css">
    <link rel="stylesheet" href="../../cliente/assets/css/publico.css">
    <link rel="stylesheet" href="../../cliente/assets/css/acceso.css">
</head>

<body>
    <a class="saltar-contenido" href="#contenido">
        Saltar al contenido
    </a>

    <header class="encabezado-publico">
        <div class="contenedor barra-publica">
            <a href="../../cliente/index.html">
                <img
                    class="logo"
                    src="../../cliente/assets/imagenes/logo.svg"
                    alt="QueryBI AI"
                    width="155"
                    height="40"
                >
            </a>

            <a href="../../cliente/index.html">Volver al inicio</a>
        </div>
    </header>

    <main id="contenido" class="contenedor">
        <section class="tarjeta acceso">
            <span class="rotulo">Acceso a QueryBI AI</span>

            <h1>Bienvenido de nuevo.</h1>

            <p>
                Ingresa con tu cuenta. Las secciones disponibles
                dependen de tus permisos.
            </p>

            <form action="login.php" method="post">
                <input
                    type="hidden"
                    name="token"
                    value="<?= qbi_texto(qbi_token()) ?>"
                >

                <div class="campo">
                    <label for="correo">Correo</label>

                    <input
                        id="correo"
                        name="correo"
                        type="email"
                        autocomplete="username"
                        value="<?= qbi_texto($correo) ?>"
                        required
                    >
                </div>

                <div class="campo">
                    <label for="clave">Contraseña</label>

                    <input
                        id="clave"
                        name="clave"
                        type="password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <?php if ($error !== ''): ?>
                    <p class="mensaje error" role="alert">
                        <?= qbi_texto($error) ?>
                    </p>
                <?php endif; ?>

                <button class="boton" type="submit">
                    Ingresar
                </button>
            </form>

            <a
                href="../../cliente/paginas/recuperar-contrasena.html"
                class="enlace-regreso"
            >
                Recuperar acceso
            </a>
        </section>
    </main>
    <footer class="pie">
        <?php require dirname(__DIR__) . '/compartido/pie-marca.php'; ?>
    </footer>
</body>
</html>