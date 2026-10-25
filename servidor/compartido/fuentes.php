<?php
if (!defined('QBI_SERVIDOR')) {
    http_response_code(403);
    exit;
}

function qbi_fuentes_lista(string $buscar = '', string $estado = 'todos'): array
{
    $sql = 'SELECT id, nombre_conexion AS nombre, motor, nombre_bd AS base, habilitada
            FROM fuentes_datos WHERE empresa_id = :empresa';
    $parametros = ['empresa' => qbi_usuario()['empresa_id']];
    if ($buscar !== '') {
        $texto = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $buscar) . '%';
        $sql .= " AND (nombre_conexion LIKE :b1 ESCAPE '!'
                   OR host LIKE :b2 ESCAPE '!' OR nombre_bd LIKE :b3 ESCAPE '!')";
        $parametros += ['b1' => $texto, 'b2' => $texto, 'b3' => $texto];
    }
    if ($estado !== 'todos') {
        $sql .= ' AND habilitada = :habilitada';
        $parametros['habilitada'] = $estado === 'habilitadas' ? 1 : 0;
    }
    $consulta = qbi_bd()->prepare($sql . ' ORDER BY nombre_conexion, id');
    $consulta->execute($parametros);
    return $consulta->fetchAll();
}

function qbi_fuentes_buscar(int $id): ?array
{
    $consulta = qbi_bd()->prepare(
        'SELECT id, nombre_conexion AS nombre, motor, host AS servidor, puerto,
                nombre_bd AS base, usuario_db AS usuario, descripcion, habilitada,
                referencia_credencial, creacion, actualizacion
         FROM fuentes_datos WHERE id = :id AND empresa_id = :empresa'
    );
    $consulta->execute(['id' => $id, 'empresa' => qbi_usuario()['empresa_id']]);
    $fuente = $consulta->fetch();
    return $fuente ?: null;
}

function qbi_fuentes_texto(array $entrada, string $campo): string
{
    return is_string($entrada[$campo] ?? null) ? trim($entrada[$campo]) : '';
}

function qbi_fuentes_largo(string $texto): int
{
    $cantidad = preg_match_all('/./us', $texto);
    return $cantidad === false ? -1 : $cantidad;
}

function qbi_fuentes_validar(array $datos): string
{
    $nombre = qbi_fuentes_largo($datos['nombre']);
    if ($nombre < 2 || $nombre > 80) return 'El nombre debe tener entre 2 y 80 caracteres.';
    if (!in_array($datos['motor'], ['MySQL', 'MariaDB'], true)) return 'Selecciona un motor válido.';
    if (!preg_match('/^[a-zA-Z0-9_.:\[\]-]{1,255}$/D', $datos['servidor'])) {
        return 'Escribe un servidor válido, sin espacios, rutas ni punto y coma.';
    }
    if (filter_var($datos['puerto'], FILTER_VALIDATE_INT,
        ['options' => ['min_range' => 1, 'max_range' => 65535]]) === false) {
        return 'El puerto debe ser un número entero entre 1 y 65535.';
    }
    if (!preg_match('/^[a-zA-Z0-9_]{1,64}$/D', $datos['base'])) {
        return 'La base debe tener entre 1 y 64 letras, números o guiones bajos.';
    }
    $usuario = qbi_fuentes_largo($datos['usuario']);
    if ($usuario < 1 || $usuario > 128 || preg_match('/[\x00-\x1F\x7F]/', $datos['usuario'])) {
        return 'Escribe un usuario de conexión válido de hasta 128 caracteres.';
    }
    $descripcion = qbi_fuentes_largo($datos['descripcion']);
    if ($descripcion < 0 || $descripcion > 400) return 'La descripción admite hasta 400 caracteres.';
    return '';
}

function qbi_fuentes_guardar(array $datos, ?array $actual): int
{
    $parametros = [
        'empresa' => qbi_usuario()['empresa_id'], 'nombre' => $datos['nombre'],
        'motor' => $datos['motor'], 'servidor' => $datos['servidor'],
        'puerto' => (int) $datos['puerto'], 'base' => $datos['base'],
        'usuario' => $datos['usuario'], 'descripcion' => $datos['descripcion'],
        'habilitada' => $datos['habilitada'] ? 1 : 0
    ];
    if ($actual === null) {
        $sql = 'INSERT INTO fuentes_datos
                (empresa_id,nombre_conexion,motor,host,puerto,nombre_bd,usuario_db,descripcion,habilitada)
                VALUES (:empresa,:nombre,:motor,:servidor,:puerto,:base,:usuario,:descripcion,:habilitada)';
    } else {
        $referencia = $actual['referencia_credencial'];
        foreach (['motor', 'servidor', 'puerto', 'base', 'usuario'] as $campo) {
            if ((string) $actual[$campo] !== (string) $datos[$campo]) $referencia = null;
        }
        $parametros += ['id' => $actual['id'], 'referencia' => $referencia];
        $sql = 'UPDATE fuentes_datos SET nombre_conexion=:nombre,motor=:motor,host=:servidor,
                puerto=:puerto,nombre_bd=:base,usuario_db=:usuario,descripcion=:descripcion,
                habilitada=:habilitada,referencia_credencial=:referencia
                WHERE id=:id AND empresa_id=:empresa';
    }
    $consulta = qbi_bd()->prepare($sql);
    $consulta->execute($parametros);
    return $actual === null ? (int) qbi_bd()->lastInsertId() : (int) $actual['id'];
}

function qbi_fuentes_esquemas(): array
{
    $empresa = qbi_usuario()['empresa_id'];
    $consulta = qbi_bd()->prepare(
        'SELECT t.fuente_id, t.nombre_tabla, c.nombre_columna, c.descripcion
         FROM esquema_tablas t JOIN fuentes_datos f ON f.id=t.fuente_id
         LEFT JOIN esquema_columnas c ON c.tabla_id=t.id
         WHERE f.empresa_id=:empresa ORDER BY t.id,c.id'
    );
    $consulta->execute(['empresa' => $empresa]);
    $esquemas = [];
    foreach ($consulta->fetchAll() as $fila) {
        $id = (string) $fila['fuente_id'];
        if (!isset($esquemas[$id])) $esquemas[$id] = ['tablas' => [], 'relaciones' => []];
        $tabla = $fila['nombre_tabla'];
        if (!isset($esquemas[$id]['tablas'][$tabla])) $esquemas[$id]['tablas'][$tabla] = [];
        if ($fila['nombre_columna'] !== null) {
            $esquemas[$id]['tablas'][$tabla][] = [$fila['nombre_columna'], $fila['descripcion'] ?? ''];
        }
    }
    $consulta = qbi_bd()->prepare(
        'SELECT r.fuente_id, tr.nombre_tabla AS tabla_referencia, cr.nombre_columna AS columna_referencia,
                tf.nombre_tabla AS tabla_foranea, cf.nombre_columna AS columna_foranea
         FROM esquema_relaciones r JOIN fuentes_datos f ON f.id=r.fuente_id
         JOIN esquema_columnas cr ON cr.id=r.columna_referenciada_id
         JOIN esquema_columnas cf ON cf.id=r.columna_foranea_id
         JOIN esquema_tablas tr ON tr.id=cr.tabla_id JOIN esquema_tablas tf ON tf.id=cf.tabla_id
         WHERE f.empresa_id=:empresa ORDER BY r.nombre_restriccion,r.posicion'
    );
    $consulta->execute(['empresa' => $empresa]);
    foreach ($consulta->fetchAll() as $fila) {
        $esquemas[(string) $fila['fuente_id']]['relaciones'][] = [
            $fila['tabla_referencia'] . '.' . $fila['columna_referencia'],
            $fila['tabla_foranea'] . '.' . $fila['columna_foranea']
        ];
    }
    return $esquemas;
}

function qbi_preparar_fuentes(string $pantalla): array
{
    qbi_requerir(['administrador']);
    $metodos = $pantalla === 'formulario' ? ['GET', 'POST'] : ['GET'];
    if (!in_array($_SERVER['REQUEST_METHOD'], $metodos, true)) {
        header('Allow: ' . implode(', ', $metodos));
        qbi_pagina_error(405, 'Método no permitido', 'Utiliza los enlaces y formularios de la página.', 'servidor/administracion/fuentes.php');
    }
    $vista = ['pantalla' => $pantalla, 'error' => '', 'guardada' => false];
    try {
        if ($pantalla === 'lista') {
            $buscar = qbi_fuentes_texto($_GET, 'buscar');
            $estado = qbi_fuentes_texto($_GET, 'estado') ?: 'todos';
            if (qbi_fuentes_largo($buscar) < 0 || qbi_fuentes_largo($buscar) > 100
                || !in_array($estado, ['todos', 'habilitadas', 'deshabilitadas'], true)) {
                qbi_pagina_error(400, 'Filtros no válidos', 'Revisa los filtros del listado.', 'servidor/administracion/fuentes.php');
            }
            $vista += ['titulo' => 'Fuentes de datos', 'buscar' => $buscar, 'estado' => $estado,
                'fuentes' => qbi_fuentes_lista($buscar, $estado)];
            $total = qbi_bd()->prepare('SELECT COUNT(*) FROM fuentes_datos WHERE empresa_id=:empresa');
            $total->execute(['empresa' => qbi_usuario()['empresa_id']]);
            $vista['total'] = (int) $total->fetchColumn();
            return $vista;
        }

        $id = null;
        if (array_key_exists('id', $_GET)) {
            $valor = is_string($_GET['id']) ? $_GET['id'] : '';
            $id = filter_var($valor, FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1, 'max_range' => 4294967295]]);
            if ($id === false) {
                qbi_pagina_error(400, 'Identificador no válido', 'Revisa la dirección solicitada.', 'servidor/administracion/fuentes.php');
            }
        }
        $actual = $id === null ? null : qbi_fuentes_buscar($id);
        if (($id !== null && $actual === null) || ($pantalla === 'detalle' && $id === null)) {
            qbi_pagina_error(404, 'Fuente no disponible', 'No se encontró una fuente accesible para tu empresa.', 'servidor/administracion/fuentes.php');
        }
        if ($pantalla === 'detalle') {
            $vista += ['titulo' => $actual['nombre'], 'datos' => $actual];
            $vista['guardada'] = (string) ($_SESSION['fuente_guardada'] ?? '') === (string) $id;
            if ($vista['guardada']) unset($_SESSION['fuente_guardada']);
            $consulta = qbi_bd()->prepare(
                'SELECT t.nombre_tabla, COUNT(c.id) AS campos FROM esquema_tablas t
                 JOIN fuentes_datos f ON f.id=t.fuente_id
                 LEFT JOIN esquema_columnas c ON c.tabla_id=t.id
                 WHERE f.id=:id AND f.empresa_id=:empresa
                 GROUP BY t.id,t.nombre_tabla ORDER BY t.nombre_tabla'
            );
            $consulta->execute(['id' => $id, 'empresa' => qbi_usuario()['empresa_id']]);
            $vista['tablas'] = $consulta->fetchAll();
            return $vista;
        }

        $datos = $actual ?? ['nombre' => '', 'motor' => 'MariaDB', 'servidor' => '127.0.0.1',
            'puerto' => 3306, 'base' => '', 'usuario' => '', 'descripcion' => '', 'habilitada' => true];
        $vista += ['titulo' => $actual === null ? 'Nueva fuente' : 'Editar fuente'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!qbi_token_valido($_POST['token'] ?? null)) {
                qbi_pagina_error(403, 'Solicitud no válida', 'Vuelve al formulario e inténtalo otra vez.', 'servidor/administracion/fuentes.php');
            }
            foreach (['nombre', 'motor', 'servidor', 'puerto', 'base', 'usuario', 'descripcion'] as $campo) {
                $datos[$campo] = qbi_fuentes_texto($_POST, $campo);
            }
            $datos['habilitada'] = ($_POST['habilitada'] ?? '') === '1';
            $vista['error'] = qbi_fuentes_validar($datos);
            if (isset($_POST['habilitada']) && $_POST['habilitada'] !== '1') $vista['error'] = 'Estado de fuente no válido.';
            if ($vista['error'] === '') {
                try {
                    $guardado = qbi_fuentes_guardar($datos, $actual);
                    $_SESSION['fuente_guardada'] = $guardado;
                    qbi_redirigir('servidor/administracion/detalle-fuente.php?id=' . $guardado);
                } catch (PDOException $error) {
                    if ((int) ($error->errorInfo[1] ?? 0) !== 1062) throw $error;
                    $vista['error'] = 'Ya existe una fuente con ese nombre en tu empresa.';
                }
            }
            http_response_code(422);
        }
        $vista['datos'] = $datos;
        $vista['id'] = $id;
        return $vista;
    } catch (Throwable $error) {
        qbi_registrar_error_bd($error);
        qbi_pagina_error(503, 'Servicio no disponible', 'No se pudo consultar o guardar la fuente. Inténtalo nuevamente.', 'servidor/administracion/fuentes.php');
    }
}
