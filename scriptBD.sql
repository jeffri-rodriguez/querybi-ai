-- QueryBI AI - Grupo 04 - Instalacion inicial del primer hito.
-- Compatible con MariaDB 10.4.32 de XAMPP.
-- Crea querybi_ai_pagina y db_empresa_demo sin borrar bases existentes.
-- Usar solamente en una instalacion nueva. No reimportar sobre las bases actuales.
-- La estructura conserva las entidades previstas para el producto completo.
-- Solo se precargan cuentas, empresa, fuente, metadatos y datos sinteticos de tienda.
-- Historial, resultados y reportes quedan vacios: la web aun usa localStorage para ellos.

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET SESSION foreign_key_checks = 1;
SET SESSION check_constraint_checks = 1;
SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ONLY_FULL_GROUP_BY,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE DATABASE querybi_ai_pagina CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE db_empresa_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 1. PLATAFORMA
USE querybi_ai_pagina;

CREATE TABLE empresas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_empresa VARCHAR(80) NOT NULL,
    correo_contacto VARCHAR(120) NOT NULL,
    ruc CHAR(11) NULL,
    estado ENUM('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
    creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_empresas_nombre (nombre_empresa),
    UNIQUE KEY uq_empresas_ruc (ruc),
    CONSTRAINT ck_empresas_nombre CHECK (CHAR_LENGTH(TRIM(nombre_empresa)) BETWEEN 2 AND 80),
    -- Solo valida formato, no existencia ni validez fiscal del RUC.
    CONSTRAINT ck_empresas_ruc CHECK (ruc IS NULL OR ruc REGEXP '^[0-9]{11}$')
) ENGINE=InnoDB;

CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT UNSIGNED NULL,
    nombre VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL,
    password VARCHAR(255) NOT NULL COMMENT 'Hash producido por password_hash; nunca password legible',
    rol ENUM('superadministrador','administrador','usuario') NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuarios_email (email),
    UNIQUE KEY uq_usuarios_empresa_id (empresa_id,id),
    KEY ix_usuarios_empresa_rol (empresa_id,rol,activo),
    CONSTRAINT fk_usuarios_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id),
    CONSTRAINT ck_usuarios_empresa_rol CHECK (
        (rol = 'superadministrador' AND empresa_id IS NULL) OR
        (rol IN ('administrador','usuario') AND empresa_id IS NOT NULL)
    ),
    CONSTRAINT ck_usuarios_activo CHECK (activo IN (0,1)),
    CONSTRAINT ck_usuarios_nombre CHECK (CHAR_LENGTH(TRIM(nombre)) BETWEEN 2 AND 80)
) ENGINE=InnoDB;

CREATE TABLE fuentes_datos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT UNSIGNED NOT NULL,
    nombre_conexion VARCHAR(80) NOT NULL,
    motor ENUM('MySQL','MariaDB') NOT NULL DEFAULT 'MariaDB',
    host VARCHAR(255) NOT NULL DEFAULT 'localhost',
    puerto SMALLINT UNSIGNED NOT NULL DEFAULT 3306,
    nombre_bd VARCHAR(64) NOT NULL,
    usuario_db VARCHAR(128) NOT NULL,
    referencia_credencial VARCHAR(100) NULL COMMENT 'Referencia a configuracion protegida del servidor; no es el password',
    descripcion TEXT NULL,
    habilitada BOOLEAN NOT NULL DEFAULT TRUE,
    creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    actualizacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_fuentes_empresa_nombre (empresa_id,nombre_conexion),
    UNIQUE KEY uq_fuentes_empresa_id (empresa_id,id),
    CONSTRAINT fk_fuentes_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id),
    CONSTRAINT ck_fuentes_puerto CHECK (puerto BETWEEN 1 AND 65535),
    CONSTRAINT ck_fuentes_nombre CHECK (CHAR_LENGTH(TRIM(nombre_conexion)) BETWEEN 2 AND 80),
    CONSTRAINT ck_fuentes_habilitada CHECK (habilitada IN (0,1))
) ENGINE=InnoDB;

CREATE TABLE esquema_tablas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fuente_id INT UNSIGNED NOT NULL,
    nombre_tabla VARCHAR(64) NOT NULL,
    descripcion_tabla TEXT NULL,
    habilitada BOOLEAN NOT NULL DEFAULT TRUE,
    UNIQUE KEY uq_tablas_fuente_nombre (fuente_id,nombre_tabla),
    UNIQUE KEY uq_tablas_fuente_id (fuente_id,id),
    CONSTRAINT fk_esquema_tablas_fuente FOREIGN KEY (fuente_id) REFERENCES fuentes_datos(id),
    CONSTRAINT ck_tablas_habilitada CHECK (habilitada IN (0,1))
) ENGINE=InnoDB;

CREATE TABLE esquema_columnas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fuente_id INT UNSIGNED NOT NULL,
    tabla_id INT UNSIGNED NOT NULL,
    nombre_columna VARCHAR(64) NOT NULL,
    tipoDato VARCHAR(100) NOT NULL,
    permite_nulos BOOLEAN NOT NULL DEFAULT FALSE,
    es_clave_primaria BOOLEAN NOT NULL DEFAULT FALSE,
    es_clave_foranea BOOLEAN NOT NULL DEFAULT FALSE,
    descripcion TEXT NULL,
    habilitada BOOLEAN NOT NULL DEFAULT TRUE,
    UNIQUE KEY uq_columnas_tabla_nombre (tabla_id,nombre_columna),
    UNIQUE KEY uq_columnas_fuente_id (fuente_id,id),
    CONSTRAINT fk_columnas_fuente_tabla FOREIGN KEY (fuente_id,tabla_id)
        REFERENCES esquema_tablas(fuente_id,id),
    CONSTRAINT ck_columnas_booleanos CHECK (
        permite_nulos IN (0,1) AND es_clave_primaria IN (0,1)
        AND es_clave_foranea IN (0,1) AND habilitada IN (0,1)
    )
) ENGINE=InnoDB;

-- Referenciado -> foraneo: clientes.id -> ventas.cliente_id.
-- Agrupar por nombre_restriccion y ordenar por posicion para una FK compuesta.
CREATE TABLE esquema_relaciones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fuente_id INT UNSIGNED NOT NULL,
    nombre_restriccion VARCHAR(64) NOT NULL,
    posicion SMALLINT UNSIGNED NOT NULL DEFAULT 1,
    columna_referenciada_id INT UNSIGNED NOT NULL,
    columna_foranea_id INT UNSIGNED NOT NULL,
    cardinalidad ENUM('1:1','1:N') NOT NULL DEFAULT '1:N',
    habilitada BOOLEAN NOT NULL DEFAULT TRUE,
    UNIQUE KEY uq_relaciones_fuente_nombre_posicion (fuente_id,nombre_restriccion,posicion),
    CONSTRAINT fk_relaciones_referenciada FOREIGN KEY (fuente_id,columna_referenciada_id)
        REFERENCES esquema_columnas(fuente_id,id),
    CONSTRAINT fk_relaciones_foranea FOREIGN KEY (fuente_id,columna_foranea_id)
        REFERENCES esquema_columnas(fuente_id,id),
    CONSTRAINT ck_relaciones_posicion CHECK (posicion >= 1),
    CONSTRAINT ck_relaciones_habilitada CHECK (habilitada IN (0,1))
) ENGINE=InnoDB;

CREATE TABLE historial_consultas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    fuente_id INT UNSIGNED NOT NULL,
    preguntaUsuario VARCHAR(1000) NOT NULL,
    tipo ENUM('pregunta','ejemplo') NOT NULL DEFAULT 'pregunta',
    sqlGenerado TEXT NULL,
    estado ENUM('Pendiente','Completada','Sin resultados','Rechazada','Error') NOT NULL DEFAULT 'Pendiente',
    estado_validacion ENUM('Pendiente','Valida','Rechazada','No aplica') NOT NULL DEFAULT 'Pendiente',
    mensaje_error TEXT NULL,
    duracion_ms INT UNSIGNED NULL COMMENT 'Medir al procesar; NULL si no se midio',
    fechaConsulta DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_finalizacion DATETIME NULL,
    KEY ix_historial_usuario_fecha (empresa_id,usuario_id,fechaConsulta,id),
    KEY ix_historial_fuente_estado (fuente_id,estado),
    CONSTRAINT fk_historial_empresa_usuario FOREIGN KEY (empresa_id,usuario_id)
        REFERENCES usuarios(empresa_id,id),
    CONSTRAINT fk_historial_empresa_fuente FOREIGN KEY (empresa_id,fuente_id)
        REFERENCES fuentes_datos(empresa_id,id),
    CONSTRAINT ck_historial_pregunta CHECK (CHAR_LENGTH(TRIM(preguntaUsuario)) BETWEEN 5 AND 1000),
    CONSTRAINT ck_historial_validacion_sql CHECK (
        estado_validacion <> 'Valida' OR
        (sqlGenerado IS NOT NULL AND CHAR_LENGTH(TRIM(sqlGenerado)) > 0)
    ),
    CONSTRAINT ck_historial_fechas CHECK (fecha_finalizacion IS NULL OR fecha_finalizacion >= fechaConsulta)
) ENGINE=InnoDB;

CREATE TABLE resultados_consulta (
    consulta_id INT UNSIGNED PRIMARY KEY,
    columnas LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    filas LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    filas_guardadas INT UNSIGNED NOT NULL,
    truncado BOOLEAN NOT NULL DEFAULT FALSE,
    creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_resultados_consulta FOREIGN KEY (consulta_id) REFERENCES historial_consultas(id),
    CONSTRAINT ck_resultados_json_columnas CHECK (JSON_VALID(columnas)),
    CONSTRAINT ck_resultados_json_filas CHECK (JSON_VALID(filas)),
    CONSTRAINT ck_resultados_arrays CHECK (JSON_TYPE(columnas) = 'ARRAY' AND JSON_TYPE(filas) = 'ARRAY'),
    CONSTRAINT ck_resultados_cantidad CHECK (filas_guardadas = JSON_LENGTH(filas)),
    CONSTRAINT ck_resultados_truncado CHECK (truncado IN (0,1))
) ENGINE=InnoDB;

-- La empresa y el propietario se obtienen mediante la consulta.
-- La FK hacia resultados impide guardar reportes sin resultado disponible.
CREATE TABLE reportes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    consulta_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    creacion DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_reportes_consulta (consulta_id),
    CONSTRAINT fk_reportes_resultado FOREIGN KEY (consulta_id) REFERENCES resultados_consulta(consulta_id),
    CONSTRAINT ck_reportes_titulo CHECK (CHAR_LENGTH(TRIM(titulo)) BETWEEN 2 AND 150)
) ENGINE=InnoDB;

-- Todas las FK de plataforma omiten acciones: InnoDB restringe los borrados
-- y cambios de identificadores referenciados. Usar activacion/desactivacion.
-- PHP debe validar roles, pertenencia, estados activos y campos habilitados.
-- Desactivar una empresa con administradores activos requiere una transaccion
-- en el servidor; no es una regla que estas FK comprueben automaticamente.
-- La tabla de metadatos no restringe por si sola la ejecucion de SQL.

-- 2. EMPRESA SINTETICA
USE db_empresa_demo;

CREATE TABLE sucursales (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_sucursal VARCHAR(150) NOT NULL,
    direccion VARCHAR(255) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    activa BOOLEAN NOT NULL DEFAULT TRUE,
    CONSTRAINT ck_sucursales_activa CHECK (activa IN (0,1))
) ENGINE=InnoDB;

CREATE TABLE empleados (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    cargo VARCHAR(100) NOT NULL DEFAULT 'Vendedor',
    sucursal_id INT UNSIGNED NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    UNIQUE KEY uq_empleados_sucursal_id (sucursal_id,id),
    CONSTRAINT fk_empleados_sucursal FOREIGN KEY (sucursal_id) REFERENCES sucursales(id),
    CONSTRAINT ck_empleados_activo CHECK (activo IN (0,1))
) ENGINE=InnoDB;

CREATE TABLE proveedores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_proveedor VARCHAR(150) NOT NULL,
    telefono VARCHAR(20) NULL,
    correo VARCHAR(120) NULL
) ENGINE=InnoDB;

CREATE TABLE clientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    ciudad VARCHAR(100) NOT NULL COMMENT 'Ciudad del cliente, diferente de ciudad de sucursal',
    correo VARCHAR(120) NULL
) ENGINE=InnoDB;

CREATE TABLE productos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_producto VARCHAR(150) NOT NULL,
    precio_unitario DECIMAL(12,2) NOT NULL COMMENT 'Precio actual de catalogo en soles',
    proveedor_id INT UNSIGNED NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,
    CONSTRAINT fk_productos_proveedor FOREIGN KEY (proveedor_id) REFERENCES proveedores(id),
    CONSTRAINT ck_productos_precio CHECK (precio_unitario >= 0),
    CONSTRAINT ck_productos_activo CHECK (activo IN (0,1))
) ENGINE=InnoDB;

CREATE TABLE inventario (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    producto_id INT UNSIGNED NOT NULL,
    sucursal_id INT UNSIGNED NOT NULL,
    stock_actual INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 5,
    UNIQUE KEY uq_inventario_producto_sucursal (producto_id,sucursal_id),
    CONSTRAINT fk_inventario_producto FOREIGN KEY (producto_id) REFERENCES productos(id),
    CONSTRAINT fk_inventario_sucursal FOREIGN KEY (sucursal_id) REFERENCES sucursales(id),
    CONSTRAINT ck_inventario_stock CHECK (stock_actual >= 0 AND stock_minimo >= 0)
) ENGINE=InnoDB;

CREATE TABLE ventas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT UNSIGNED NULL,
    sucursal_id INT UNSIGNED NOT NULL,
    empleado_id INT UNSIGNED NULL,
    fecha_venta DATE NOT NULL,
    total_importe DECIMAL(12,2) NOT NULL COMMENT 'Total en soles; mantener igual a la suma del detalle',
    KEY ix_ventas_fecha (fecha_venta),
    CONSTRAINT fk_ventas_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    CONSTRAINT fk_ventas_sucursal FOREIGN KEY (sucursal_id) REFERENCES sucursales(id),
    CONSTRAINT fk_ventas_empleado_sucursal FOREIGN KEY (sucursal_id,empleado_id)
        REFERENCES empleados(sucursal_id,id),
    CONSTRAINT ck_ventas_total CHECK (total_importe >= 0)
) ENGINE=InnoDB;

CREATE TABLE detalle_ventas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venta_id INT UNSIGNED NOT NULL,
    producto_id INT UNSIGNED NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario_venta DECIMAL(12,2) NOT NULL COMMENT 'Precio historico, independiente del precio actual',
    subtotal DECIMAL(12,2) NOT NULL,
    UNIQUE KEY uq_detalle_venta_producto (venta_id,producto_id),
    CONSTRAINT fk_detalle_venta FOREIGN KEY (venta_id) REFERENCES ventas(id),
    CONSTRAINT fk_detalle_producto FOREIGN KEY (producto_id) REFERENCES productos(id),
    CONSTRAINT ck_detalle_cantidad CHECK (cantidad > 0),
    CONSTRAINT ck_detalle_precio CHECK (precio_unitario_venta >= 0),
    CONSTRAINT ck_detalle_subtotal CHECK (subtotal = ROUND(cantidad * precio_unitario_venta,2))
) ENGINE=InnoDB;

-- Ninguna FK borra detalles de ventas al eliminar productos.
-- Se modela una linea por producto y venta, sin descuentos/impuestos adicionales.
-- Los totales de cabecera y el stock requieren logica transaccional si se
-- implementan operaciones comerciales. Esta tienda se utiliza para lectura.
-- El stock de prueba es una fotografia sintetica; no hay kardex implementado.

-- 3. DATOS DE PRUEBA: todos son ficticios.
START TRANSACTION;
USE db_empresa_demo;
INSERT INTO sucursales (id,nombre_sucursal,direccion,ciudad) VALUES
(1,'Tienda Lima','Direccion ficticia 101','Lima'),
(2,'Tienda Arequipa','Direccion ficticia 202','Arequipa'),
(3,'Tienda Trujillo','Direccion ficticia 303','Trujillo');
INSERT INTO empleados (id,nombre,apellido,sucursal_id) VALUES
(1,'Ana','Demo',1),(2,'Luis','Demo',2),(3,'Rosa','Demo',3);
INSERT INTO proveedores (id,nombre_proveedor,correo) VALUES
(1,'Papeleria de prueba','proveedor1@ejemplo.test'),
(2,'Utiles de prueba','proveedor2@ejemplo.test');
INSERT INTO clientes (id,nombre,ciudad,correo) VALUES
(1,'Cliente Lima','Lima','cliente1@ejemplo.test'),
(2,'Cliente Arequipa','Arequipa','cliente2@ejemplo.test'),
(3,'Cliente Trujillo','Trujillo','cliente3@ejemplo.test');
INSERT INTO productos (id,nombre_producto,precio_unitario,proveedor_id) VALUES
(1,'Cuaderno',5.00,1),(2,'Lapicero',2.00,1),(3,'Carpeta',12.00,2),
(4,'Resaltador',4.00,2),(5,'Agenda',15.00,2);
INSERT INTO inventario (producto_id,sucursal_id,stock_actual,stock_minimo)
SELECT p.id,s.id,CASE WHEN p.id=5 AND s.id=3 THEN 2 ELSE 50 END,5
FROM productos p CROSS JOIN sucursales s;
INSERT INTO ventas (id,cliente_id,sucursal_id,empleado_id,fecha_venta,total_importe) VALUES
(1,1,1,1,'2025-01-05',21.00),
(2,2,2,2,'2025-01-15',60.00),
(3,3,3,3,'2025-01-25',128.00),
(4,1,1,1,'2025-01-31',138.00),
(5,2,2,2,'2025-02-03',260.00),
(6,3,3,3,'2025-02-14',106.00),
(7,1,1,1,'2025-02-20',150.00),
(8,2,2,2,'2025-02-28',328.00),
(9,3,3,3,'2025-03-02',273.00),
(10,1,1,1,'2025-03-12',510.00),
(11,2,2,2,'2025-03-20',191.00),
(12,3,3,3,'2025-03-28',240.00);
INSERT INTO detalle_ventas (venta_id,producto_id,cantidad,precio_unitario_venta,subtotal) VALUES
(1,1,3,5.00,15.00),
(1,2,3,2.00,6.00),
(2,2,6,2.00,12.00),
(2,3,4,12.00,48.00),
(3,3,9,12.00,108.00),
(3,4,5,4.00,20.00),
(4,4,12,4.00,48.00),
(4,5,6,15.00,90.00),
(5,5,15,15.00,225.00),
(5,1,7,5.00,35.00),
(6,1,18,5.00,90.00),
(6,2,8,2.00,16.00),
(7,2,21,2.00,42.00),
(7,3,9,12.00,108.00),
(8,3,24,12.00,288.00),
(8,4,10,4.00,40.00),
(9,4,27,4.00,108.00),
(9,5,11,15.00,165.00),
(10,5,30,15.00,450.00),
(10,1,12,5.00,60.00),
(11,1,33,5.00,165.00),
(11,2,13,2.00,26.00),
(12,2,36,2.00,72.00),
(12,3,14,12.00,168.00);

USE querybi_ai_pagina;
INSERT INTO empresas (id,nombre_empresa,correo_contacto,ruc) VALUES
(1,'Tienda Demo','contacto@tienda.test',NULL);
-- Credenciales de acceso WEB (no cuentas del servidor MariaDB):
-- superadmin@querybi.test / SuperDemo2026!
-- admin@tienda.test / AdminDemo2026!
-- usuario@tienda.test / UsuarioDemo2026!
INSERT INTO usuarios (id,empresa_id,nombre,email,password,rol) VALUES
(1,NULL,'Superadministrador Demo','superadmin@querybi.test','$2y$10$17uWgpQN41FBKbd/IxRDG.Fm8RKGGgnSsgSSFqcgTcvrgccmYZn5G','superadministrador'),
(2,1,'Administrador Demo','admin@tienda.test','$2y$10$O3uNo1UaKMU.sbNaus97pe/1u9yYnRUekXIqxoLYWKhfq4cTszw.W','administrador'),
(3,1,'Usuario Demo','usuario@tienda.test','$2y$10$3SMLsJx3gBL/b0hMTYIvBOcW5gGSuklIBunhLYuyXgbCmBgtv0Hfy','usuario');

INSERT INTO fuentes_datos
(id,empresa_id,nombre_conexion,host,nombre_bd,usuario_db,descripcion)
VALUES (1,1,'Tienda Demo','127.0.0.1','db_empresa_demo','querybi_lector_demo',
'Fuente sintetica preparada para consultas futuras. Esta version registra su configuracion, pero no abre una conexion de lectura a la tienda.');
-- usuario_db es un dato del catalogo; no crea un usuario del servidor MariaDB.
-- referencia_credencial queda NULL hasta implementar y configurar la conexion externa.

-- Estos metadatos se registran durante la instalacion usando INFORMATION_SCHEMA.
-- No se almacenan filas comerciales en las tablas de metadatos.
INSERT INTO esquema_tablas (fuente_id,nombre_tabla,descripcion_tabla)
SELECT 1,TABLE_NAME,COALESCE(NULLIF(TABLE_COMMENT,''),CONCAT('Tabla sintetica ',TABLE_NAME))
FROM information_schema.TABLES
WHERE TABLE_SCHEMA='db_empresa_demo' AND TABLE_TYPE='BASE TABLE'
ORDER BY TABLE_NAME;

INSERT INTO esquema_columnas
(fuente_id,tabla_id,nombre_columna,tipoDato,permite_nulos,es_clave_primaria,es_clave_foranea,descripcion)
SELECT t.fuente_id,t.id,c.COLUMN_NAME,c.COLUMN_TYPE,c.IS_NULLABLE='YES',c.COLUMN_KEY='PRI',
       EXISTS (SELECT 1 FROM information_schema.KEY_COLUMN_USAGE k
               WHERE k.TABLE_SCHEMA=c.TABLE_SCHEMA AND k.TABLE_NAME=c.TABLE_NAME
                 AND k.COLUMN_NAME=c.COLUMN_NAME AND k.REFERENCED_TABLE_NAME IS NOT NULL),
       COALESCE(NULLIF(c.COLUMN_COMMENT,''),CONCAT('Campo ',c.TABLE_NAME,'.',c.COLUMN_NAME))
FROM information_schema.COLUMNS c
JOIN esquema_tablas t ON t.fuente_id=1 AND t.nombre_tabla=c.TABLE_NAME
WHERE c.TABLE_SCHEMA='db_empresa_demo'
ORDER BY t.id,c.ORDINAL_POSITION;

INSERT INTO esquema_relaciones
(fuente_id,nombre_restriccion,posicion,columna_referenciada_id,columna_foranea_id,cardinalidad)
SELECT 1,k.CONSTRAINT_NAME,k.ORDINAL_POSITION,cr.id,cf.id,'1:N'
FROM information_schema.KEY_COLUMN_USAGE k
JOIN esquema_tablas tf ON tf.fuente_id=1 AND tf.nombre_tabla=k.TABLE_NAME
JOIN esquema_columnas cf ON cf.tabla_id=tf.id AND cf.nombre_columna=k.COLUMN_NAME
JOIN esquema_tablas tr ON tr.fuente_id=1 AND tr.nombre_tabla=k.REFERENCED_TABLE_NAME
JOIN esquema_columnas cr ON cr.tabla_id=tr.id AND cr.nombre_columna=k.REFERENCED_COLUMN_NAME
WHERE k.TABLE_SCHEMA='db_empresa_demo' AND k.REFERENCED_TABLE_SCHEMA='db_empresa_demo'
ORDER BY k.CONSTRAINT_NAME,k.ORDINAL_POSITION;

-- Sin actividad operativa precargada:
-- historial_consultas, resultados_consulta y reportes permanecen vacios.
-- Los registros del navegador no se sincronizan con estas tablas en el primer hito.
COMMIT;

-- 4. COMPROBACIONES DE INSTALACION (no son evidencia de integracion web).
-- Deben aparecer 9 tablas de plataforma y 8 tablas empresariales.
SELECT TABLE_SCHEMA,COUNT(*) AS tablas
FROM information_schema.TABLES
WHERE TABLE_SCHEMA IN ('querybi_ai_pagina','db_empresa_demo') AND TABLE_TYPE='BASE TABLE'
GROUP BY TABLE_SCHEMA;

SELECT u.id,u.nombre,u.email,u.rol,u.activo,e.nombre_empresa
FROM querybi_ai_pagina.usuarios u
LEFT JOIN querybi_ai_pagina.empresas e ON e.id=u.empresa_id
ORDER BY u.id;

SELECT t.nombre_tabla,COUNT(c.id) AS columnas_registradas
FROM querybi_ai_pagina.esquema_tablas t
LEFT JOIN querybi_ai_pagina.esquema_columnas c ON c.tabla_id=t.id
GROUP BY t.id,t.nombre_tabla ORDER BY t.nombre_tabla;

-- Verificar las partes de las relaciones; agrupar las FK compuestas por nombre.
SELECT r.nombre_restriccion,r.posicion,
       CONCAT(tr.nombre_tabla,'.',cr.nombre_columna) AS referenciado,
       CONCAT(tf.nombre_tabla,'.',cf.nombre_columna) AS foraneo
FROM querybi_ai_pagina.esquema_relaciones r
JOIN querybi_ai_pagina.esquema_columnas cr ON cr.id=r.columna_referenciada_id
JOIN querybi_ai_pagina.esquema_columnas cf ON cf.id=r.columna_foranea_id
JOIN querybi_ai_pagina.esquema_tablas tr ON tr.id=cr.tabla_id
JOIN querybi_ai_pagina.esquema_tablas tf ON tf.id=cf.tabla_id
ORDER BY r.nombre_restriccion,r.posicion;

-- Debe devolver CERO filas: no hay cabeceras de venta inconsistentes.
SELECT v.id,v.total_importe,COALESCE(SUM(d.subtotal),0) AS suma_detalle
FROM db_empresa_demo.ventas v
LEFT JOIN db_empresa_demo.detalle_ventas d ON d.venta_id=v.id
GROUP BY v.id,v.total_importe
HAVING v.total_importe <> COALESCE(SUM(d.subtotal),0);

-- Comprobacion manual del dataset sintetico; no es una consulta ejecutada por la web.
SELECT DATE_FORMAT(fecha_venta,'%Y-%m') AS mes,SUM(total_importe) AS ventas
FROM db_empresa_demo.ventas
WHERE fecha_venta >= '2025-01-01' AND fecha_venta < '2025-04-01'
GROUP BY DATE_FORMAT(fecha_venta,'%Y-%m') ORDER BY mes;

SELECT p.nombre_producto,SUM(d.cantidad) AS unidades
FROM db_empresa_demo.productos p
JOIN db_empresa_demo.detalle_ventas d ON d.producto_id=p.id
JOIN db_empresa_demo.ventas v ON v.id=d.venta_id
WHERE v.fecha_venta >= '2025-01-01' AND v.fecha_venta < '2025-04-01'
GROUP BY p.id,p.nombre_producto ORDER BY unidades DESC,p.nombre_producto LIMIT 5;

-- Estas tres cantidades deben ser 0 al terminar una instalacion nueva.
SELECT 'historial_consultas' AS tabla,COUNT(*) AS registros
FROM querybi_ai_pagina.historial_consultas
UNION ALL
SELECT 'resultados_consulta',COUNT(*) FROM querybi_ai_pagina.resultados_consulta
UNION ALL
SELECT 'reportes',COUNT(*) FROM querybi_ai_pagina.reportes;
