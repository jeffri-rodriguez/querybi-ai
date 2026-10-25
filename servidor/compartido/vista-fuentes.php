<?php
if (!defined('QBI_SERVIDOR') || !isset($vistaFuentes)) {
    http_response_code(403);
    exit;
}
qbi_requerir(['administrador']);
$v = $vistaFuentes;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= qbi_texto($v['titulo']) ?> | QueryBI AI</title>
    <link rel="icon" href="../../cliente/assets/imagenes/favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="../../cliente/assets/css/estilos.css">
    <link rel="stylesheet" href="../../cliente/assets/css/aplicacion.css">
    <link rel="stylesheet" href="../../cliente/assets/css/administracion.css">
    <script src="../../cliente/assets/js/principal.js" defer></script>
</head>
<body>
    <a class="saltar-contenido" href="#contenido">Saltar al contenido</a>
    <?php require __DIR__ . '/navegacion.php'; ?>
    <main id="contenido" class="contenido-app">
        <div class="cabecera-seccion">
            <div><span class="rotulo">Administración / fuentes</span><h1><?= qbi_texto($v['titulo']) ?></h1></div>
            <a href="<?= $v['pantalla'] === 'lista' ? 'configurar-fuente.php' : 'fuentes.php' ?>">
                <?= $v['pantalla'] === 'lista' ? 'Nueva fuente' : 'Volver a fuentes' ?>
            </a>
        </div>
        <?php if ($v['pantalla'] === 'lista'): ?>
            <section class="tarjeta">
                <h2>Catálogo de tu empresa</h2>
                <form method="get" action="fuentes.php">
                    <div class="dos-columnas">
                        <div class="campo"><label for="buscar">Nombre, base o servidor</label>
                            <input id="buscar" name="buscar" type="search" maxlength="100" value="<?= qbi_texto($v['buscar']) ?>"></div>
                        <div class="campo"><label for="estado">Estado</label>
                            <select id="estado" name="estado">
                                <?php foreach (['todos' => 'Todas', 'habilitadas' => 'Habilitadas', 'deshabilitadas' => 'Deshabilitadas'] as $valor => $texto): ?>
                                    <option value="<?= $valor ?>"<?= $v['estado'] === $valor ? ' selected' : '' ?>><?= $texto ?></option>
                                <?php endforeach; ?>
                            </select></div>
                    </div>
                    <div class="acciones"><button class="boton" type="submit">Filtrar</button><a href="fuentes.php">Limpiar filtros</a></div>
                </form>
                <p><?= count($v['fuentes']) ?> de <?= $v['total'] ?> fuentes.</p>
                <?php if (!$v['fuentes']): ?>
                    <p><?= $v['total'] === 0 ? 'Todavía no hay fuentes registradas para tu empresa.' : 'No hay fuentes que coincidan con los filtros.' ?></p>
                <?php else: ?>
                    <div class="tabla-contenedor"><table>
                        <caption>Fuentes registradas en MariaDB</caption>
                        <thead><tr><th scope="col">Nombre</th><th scope="col">Motor</th><th scope="col">Base</th><th scope="col">Estado</th><th scope="col">Acciones</th></tr></thead>
                        <tbody>
                            <?php foreach ($v['fuentes'] as $fuente): ?>
                                <tr><td><?= qbi_texto($fuente['nombre']) ?></td><td><?= qbi_texto($fuente['motor']) ?></td><td><?= qbi_texto($fuente['base']) ?></td>
                                    <td><?= $fuente['habilitada'] ? 'Habilitada' : 'Deshabilitada' ?></td>
                                    <td><div class="acciones-tabla">
                                        <a href="detalle-fuente.php?id=<?= (int) $fuente['id'] ?>">Ver detalle</a>
                                        <a href="configurar-fuente.php?id=<?= (int) $fuente['id'] ?>">Editar</a>
                                    </div></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table></div>
                <?php endif; ?>
            </section>
        <?php elseif ($v['pantalla'] === 'formulario'): ?>
            <section class="tarjeta formulario-administracion">
                <h2>Configuración de la fuente</h2>
                <p>Guardar registra la configuración. No comprueba ni abre una conexión con la base empresarial.</p>
                <?php if ($v['error'] !== ''): ?><p class="mensaje error" role="alert"><?= qbi_texto($v['error']) ?></p><?php endif; ?>
                <form method="post" action="configurar-fuente.php<?= $v['id'] === null ? '' : '?id=' . $v['id'] ?>">
                    <input type="hidden" name="token" value="<?= qbi_texto(qbi_token()) ?>">
                    <div class="dos-columnas">
                        <?php foreach ([
                            'nombre' => ['Nombre visible', 80], 'servidor' => ['Servidor', 255],
                            'base' => ['Base de datos', 64], 'usuario' => ['Usuario de conexión', 128]
                        ] as $campo => [$etiqueta, $maximo]): ?>
                            <div class="campo"><label for="<?= $campo ?>"><?= $etiqueta ?></label>
                                <input id="<?= $campo ?>" name="<?= $campo ?>" maxlength="<?= $maximo ?>" required value="<?= qbi_texto((string) $v['datos'][$campo]) ?>"></div>
                        <?php endforeach; ?>
                        <div class="campo"><label for="motor">Motor</label><select id="motor" name="motor" required>
                            <?php foreach (['MariaDB', 'MySQL'] as $motor): ?>
                                <option value="<?= $motor ?>"<?= $v['datos']['motor'] === $motor ? ' selected' : '' ?>><?= $motor ?></option>
                            <?php endforeach; ?>
                        </select></div>
                        <div class="campo"><label for="puerto">Puerto</label>
                            <input id="puerto" name="puerto" type="number" min="1" max="65535" step="1" required value="<?= qbi_texto((string) $v['datos']['puerto']) ?>"></div>
                    </div>
                    <div class="campo"><label for="descripcion">Descripción (opcional)</label>
                        <textarea id="descripcion" name="descripcion" maxlength="400"><?= qbi_texto($v['datos']['descripcion'] ?? '') ?></textarea></div>
                    <p><label><input type="checkbox" name="habilitada" value="1"<?= $v['datos']['habilitada'] ? ' checked' : '' ?>> Habilitar fuente en el catálogo</label></p>
                    <p class="texto-secundario">Las contraseñas de conexión se configurarán en el servidor. Cambiar la conexión no actualiza el esquema registrado.</p>
                    <div class="acciones"><button class="boton" type="submit">Guardar fuente</button><a href="fuentes.php">Cancelar</a></div>
                </form>
            </section>
        <?php else: ?>
            <?php $fuente = $v['datos']; ?>
            <?php if ($v['guardada']): ?><p class="mensaje" role="status">Fuente guardada correctamente en MariaDB.</p><?php endif; ?>
            <section class="tarjeta">
                <h2>Configuración registrada</h2>
                <dl class="detalle-fuente">
                    <?php foreach (['motor' => 'Motor', 'servidor' => 'Servidor', 'puerto' => 'Puerto', 'base' => 'Base de datos', 'usuario' => 'Usuario de conexión', 'creacion' => 'Registro', 'actualizacion' => 'Última modificación'] as $campo => $etiqueta): ?>
                        <dt><?= $etiqueta ?></dt><dd><?= qbi_texto((string) $fuente[$campo]) ?></dd>
                    <?php endforeach; ?>
                    <dt>Estado</dt><dd><?= $fuente['habilitada'] ? 'Habilitada' : 'Deshabilitada' ?></dd>
                    <dt>Descripción</dt><dd><?= qbi_texto($fuente['descripcion'] ?: 'Sin descripción.') ?></dd>
                </dl>
                <a class="boton" href="configurar-fuente.php?id=<?= (int) $fuente['id'] ?>">Editar fuente</a>
                <p class="texto-secundario">Los datos están guardados. No se ha comprobado la conexión con la base empresarial.</p>
            </section>
            <section class="tarjeta espacio-superior">
                <h2>Tablas registradas del esquema</h2>
                <?php if (!$v['tablas']): ?><p>Esta fuente todavía no tiene tablas registradas.</p>
                <?php else: ?>
                    <div class="tabla-contenedor"><table><caption>Metadatos del esquema</caption>
                        <thead><tr><th scope="col">Tabla</th><th scope="col">Campos registrados</th></tr></thead>
                        <tbody><?php foreach ($v['tablas'] as $tabla): ?><tr><td><?= qbi_texto($tabla['nombre_tabla']) ?></td><td><?= (int) $tabla['campos'] ?></td></tr><?php endforeach; ?></tbody>
                    </table></div>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </main>
    </div>
    <footer class="pie"><p>QueryBI AI · Grupo 04 · Aplicaciones Distribuidas I</p><small>Catálogo persistente de fuentes.</small></footer>
</body>
</html>
