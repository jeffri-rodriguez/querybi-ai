# QueryBI AI

Proyecto académico del Grupo 04 para el curso de Aplicaciones Distribuidas I de la Universidad Nacional del Santa.

QueryBI AI es una aplicación web orientada a consultar y analizar información empresarial mediante preguntas en lenguaje natural. Su propuesta comprende fuentes de datos autorizadas, configuración del esquema, generación de SQL mediante IA, validación en el servidor, visualizaciones, historial y reportes.

Este repositorio corresponde al primer hito. La aplicación utiliza una arquitectura cliente-servidor con HTML, CSS, JavaScript, PHP y MariaDB. La autenticación, las restricciones de acceso por rol y la gestión persistente de fuentes están implementadas. Otras funciones conservan almacenamiento local o resultados preparados.

La web pública presenta la experiencia del producto completo, con una identidad de marca y textos orientados a empresas. Este README documenta el estado técnico real del avance: la generación de SQL mediante IA y la ejecución sobre bases empresariales todavía están pendientes.

## 1. Estado actual del proyecto

| Componente | Estado técnico del primer hito |
|---|---|
| Sitio público principal | Inicio, Producto, Cómo funciona, Nosotros y Ayuda renovados |
| Navegación pública | Enlaces, menú móvil y accesos al login |
| Ejemplo público de Inicio | Tres ejemplos ilustrativos: ventas, productos y ciudades; resultados y SQL preparados |
| Centro de ayuda | 20 preguntas por temas, búsqueda local, coincidencias sin distinguir tildes o mayúsculas y mensaje sin resultados |
| Inicio y cierre de sesión | Sesiones PHP con cuentas verificadas en MariaDB |
| Restricción de acceso por rol | Validación en PHP; cuenta, rol y estado de la empresa revisados en cada solicitud protegida |
| Panel empresarial | Indicadores y actividad correspondientes a los registros locales de la cuenta |
| Apariencia del acceso y los paneles | Colores y tipografía alineados con la web pública; avisos técnicos conservados |
| Preguntas libres | Registro local; quedan con estado Pendiente |
| Ejemplos internos | Resultados, gráficos y SQL preparados; sujetos a la disponibilidad de la fuente inicial |
| Historial y detalle | Almacenamiento local en el navegador |
| Reportes | Guardado local, vista previa y exportación CSV de ejemplos |
| Fuentes de datos | Registro, listado, filtros, detalle, edición y estado persistentes en MariaDB, por empresa |
| Esquema | Metadatos leídos desde MariaDB; selección de tablas y campos guardada localmente |
| Permisos sobre consultas SQL | Aplicación durante la ejecución pendiente; el control de acceso a las páginas por rol sí funciona |
| Empresas y administradores | Catálogos locales del superadministrador; no modifican las cuentas reales de ingreso |
| Usuarios empresariales | Catálogo local del administrador; no crea cuentas de acceso en MariaDB |
| Mi perfil | Datos de sesión y preferencias locales de presentación |
| Recuperación y cambio de contraseña | Pendientes; la página de recuperación informa sobre su disponibilidad |
| Registro autónomo de empresas | Pendiente |
| Conexión efectiva a fuentes empresariales | Pendiente; guardar una fuente registra su configuración |
| Generación de SQL con IA y validación en el servidor | Pendientes de integración funcional |
| Ejecución SQL contra la tienda u otras bases empresariales | Pendiente |

El rediseño público no cambia las funciones de los paneles ni añade por sí mismo integración con IA. La entrega completa del hito debe incluir además el informe, el diseño de datos y las evidencias exigidas por el curso.

## 2. Arquitectura cliente-servidor

Se mantiene la arquitectura cliente-servidor indicada por el docente para el primer avance. La organización de carpetas distingue los recursos estáticos del código procesado en el servidor.

### Cliente

El navegador:

- Muestra el HTML y los estilos CSS.
- Ejecuta JavaScript para menús, formularios, filtros, gráficos y búsqueda de ayuda.
- Mantiene en `localStorage` los registros de los módulos que todavía son locales.
- Presenta los resultados preparados de demostración.

### Servidor

PHP:

- Consulta las cuentas de MariaDB y verifica sus contraseñas con `password_verify`.
- Crea, mantiene y cierra la sesión.
- Comprueba la identidad, el rol y el estado de la empresa antes de entregar páginas protegidas.
- Valida, registra y edita fuentes mediante PDO, consultas preparadas y protección CSRF.
- Restringe las fuentes y sus metadatos a la empresa de la sesión.
- Genera la navegación y el contexto de las pantallas internas.

Las páginas PHP están dentro de `servidor/`: se procesan allí y entregan HTML al navegador. Su ubicación no impide que formen parte de la interfaz visible.

Las comprobaciones por rol se realizan también al solicitar directamente una URL; ocultar un enlace del menú no es el mecanismo de autorización.

### Almacenamiento

| Mecanismo | Uso actual |
|---|---|
| Sesión PHP | Identidad y contexto de la cuenta autenticada |
| MariaDB de la plataforma | Cuentas, estado de empresas, fuentes y metadatos registrados |
| Archivos JavaScript de datos | Conjuntos de ejemplo y catálogos locales iniciales |
| `localStorage` | Preguntas, historial, reportes, catálogos locales, selección del esquema y preferencias |

`localStorage` conserva información entre visitas en un mismo navegador, pero no proporciona almacenamiento compartido entre dispositivos ni constituye una autoridad para autenticar o autorizar usuarios.

## 3. Tecnologías

- HTML5 para las páginas y la estructura de las interfaces.
- CSS3 en archivos externos para la presentación.
- JavaScript para las interacciones y el almacenamiento local.
- PHP para las sesiones, las páginas protegidas y las operaciones del servidor.
- PDO y `pdo_mysql` para acceder a MariaDB.
- MariaDB 10.4.32, utilizado en el entorno XAMPP del proyecto.
- Git y GitHub para el control de versiones y la integración del código.

La versión del primer hito se ejecuta sin un framework de aplicación y no requiere instalar dependencias mediante npm o Composer. Laravel/MVC se contempla para el segundo avance.

## 4. Roles y acceso

### Superadministrador

Accede al panel general, Empresas, Administradores, Mi perfil y Ayuda. Su función es la gestión de la plataforma; no recibe acceso automático al espacio de análisis ni a la administración interna de las empresas.

Empresas y Administradores son catálogos locales de demostración. Su creación o edición no modifica las tablas de cuentas ni el estado de las empresas en MariaDB.

### Administrador de empresa

Accede al panel empresarial, Consultas, Ejemplos, Historial, Reportes, Fuentes de datos, Esquema y permisos, Usuarios, Mi perfil y Ayuda.

Las fuentes se gestionan con persistencia y separación por empresa. Los registros de Usuarios son locales, tienen el rol Usuario y se asocian a la empresa de la sesión; todavía no generan cuentas reales de ingreso.

### Usuario de empresa

Accede al panel empresarial, Consultas, Ejemplos, Historial, Reportes, Mi perfil y Ayuda. No puede abrir las páginas administrativas de fuentes, esquema, usuarios o plataforma.

### Matriz de acceso

| Área | Superadministrador | Administrador | Usuario |
|---|---|---|---|
| Páginas públicas y Ayuda | Sí | Sí | Sí |
| Panel de plataforma | Sí | No | No |
| Empresas | Sí | No | No |
| Administradores de empresa | Sí | No | No |
| Panel empresarial | No | Sí | Sí |
| Consultas y Ejemplos | No | Sí | Sí |
| Historial y Reportes | No | Sí | Sí |
| Fuentes de datos | No | Sí | No |
| Esquema y permisos | No | Sí | No |
| Usuarios de empresa | No | Sí | No |
| Mi perfil | Sí | Sí | Sí |

Las páginas públicas también están disponibles sin sesión. El rol de la cuenta autenticada se obtiene desde MariaDB, no desde un selector del formulario de ingreso ni desde `localStorage`.

## 5. Estructura de carpetas

Las rutas siguientes se interpretan desde la raíz del proyecto:

```text
querybi-ai/
    index.php
    README.md
    .gitignore
    QueryBI_AI_hito1_XAMPP.sql

    cliente/
        index.html

        paginas/
            producto.html
            como-funciona.html
            nosotros.html
            ayuda.html
            recuperar-contrasena.html

        assets/
            css/
                sitio-publico.css
                estilos.css
                publico.css
                acceso.css
                aplicacion.css
                consultas.css
                administracion.css

            js/
                sitio-publico.js
                principal.js
                panel.js
                consultas.js
                detalle-consulta.js
                ejemplos.js
                graficos.js
                historial.js
                reportes.js
                perfil.js
                esquema.js
                gestion.js
                plataforma.js
                validaciones.js

            datos/
                datos-demo.js
                esquema-demo.js

            imagenes/
                logo.svg
                favicon.svg

    servidor/
        configuracion.local.php

        compartido/
            sesion.php
            base-datos.php
            fuentes.php
            navegacion.php
            vista-fuentes.php
            vista-gestion.php
            pie-marca.php

        acceso/
            login.php
            salir.php

        aplicacion/
            panel.php
            consultas.php
            detalle-consulta.php
            ejemplos.php
            historial.php
            reportes.php
            perfil.php

        administracion/
            fuentes.php
            configurar-fuente.php
            detalle-fuente.php
            esquema.php
            usuarios.php

        plataforma/
            panel.php
            empresas.php
            administradores.php
```

### Responsabilidades

| Ubicación | Responsabilidad |
|---|---|
| `index.php` | Punto de entrada que redirige a `cliente/index.html` |
| `cliente/index.html` | Portada pública |
| `cliente/paginas/` | Producto, funcionamiento, empresa, ayuda y recuperación informativa |
| `cliente/assets/` | CSS, JavaScript, datos ilustrativos e imágenes |
| `servidor/acceso/` | Procesamiento del ingreso y la salida |
| `servidor/aplicacion/` | Páginas PHP del espacio empresarial y del perfil |
| `servidor/administracion/` | Administración de fuentes, esquema y usuarios de empresa |
| `servidor/plataforma/` | Páginas del superadministrador |
| `servidor/compartido/` | Sesiones, conexión PDO, operaciones de fuentes y vistas compartidas |
| `servidor/configuracion.local.php` | Configuración única de la conexión de la plataforma |

### Archivos principales

- `servidor/compartido/sesion.php`: sesión, comprobación de roles, contexto y utilidades de URL.
- `servidor/compartido/base-datos.php`: conexión PDO; carga la configuración desde la carpeta superior.
- `servidor/compartido/fuentes.php`: validaciones y operaciones persistentes de fuentes; lectura del esquema registrado.
- `servidor/compartido/navegacion.php`: encabezado y menú según el rol.
- `servidor/compartido/vista-fuentes.php`: listado, formulario y detalle de fuentes.
- `servidor/compartido/vista-gestion.php`: presentación de catálogos administrativos locales.
- `servidor/compartido/pie-marca.php`: fragmento de marca compartido por el pie del login y de los módulos internos; se inserta dentro del footer existente y conserva sus notas técnicas.
- `cliente/assets/css/sitio-publico.css`: diseño exclusivo de las cinco páginas públicas principales.
- `cliente/assets/js/sitio-publico.js`: menú público, ejemplos de Inicio y búsqueda en Ayuda.
- `cliente/assets/js/principal.js`: utilidades y almacenamiento de los módulos internos.
- `cliente/assets/datos/esquema-demo.js`: recibe los metadatos enviados por PHP para el editor del esquema.

`publico.css` se conserva porque la página de recuperación todavía utiliza ese diseño. Los estilos de acceso y de los paneles mantienen sus archivos propios.

## 6. Instalación y ejecución

### 6.1. Requisitos

- Un servidor que procese PHP y permita sesiones.
- Extensiones `PDO` y `pdo_mysql` habilitadas.
- MariaDB 10.4.32 para utilizar el SQL del entorno XAMPP preparado para el curso.
- Navegador con JavaScript, cookies y `localStorage` disponibles para los módulos que los utilizan.
- Git, si se obtiene o actualiza el código desde el repositorio.

### 6.2. Preparar las bases de datos

1. Iniciar el módulo MySQL de XAMPP, que en este entorno ejecuta MariaDB.
2. Abrir phpMyAdmin.
3. Si las dos bases todavía no existen, importar `scriptBD.sql` desde el nivel del servidor.
4. Comprobar que aparecen `querybi_ai_pagina` y `db_empresa_demo`.
5. Revisar las tablas, las cuentas iniciales, la fuente y los metadatos creados por el script.

El script es de instalación inicial. Crea las bases y tablas; no es una migración ni está diseñado para ejecutarse repetidamente. No contiene instrucciones de borrado de las bases existentes.

**Si las bases ya están creadas, continuar con la configuración y omitir la importación.** Actualizar el archivo SQL del repositorio no modifica automáticamente una base ya instalada ni elimina los registros que tenía.

### 6.3. Qué contiene y cómo se utiliza cada base

| Base | Contenido | Uso actual |
|---|---|---|
| `querybi_ai_pagina` | Empresas, cuentas, fuentes, esquema, historial, resultados y reportes | PHP utiliza cuentas, estado de empresas, fuentes y metadatos. La persistencia de historial, resultados y reportes aún no está integrada. |
| `db_empresa_demo` | Datos sintéticos de una tienda: sucursales, empleados, proveedores, clientes, productos, inventario, ventas y detalle | Se crea como conjunto de prueba; su esquema se registra durante la instalación y sus datos sirven como referencia de los ejemplos. La aplicación todavía no ejecuta consultas sobre sus ventas. |

La plataforma contiene nueve tablas:

- `empresas`
- `usuarios`
- `fuentes_datos`
- `esquema_tablas`
- `esquema_columnas`
- `esquema_relaciones`
- `historial_consultas`
- `resultados_consulta`
- `reportes`

La tienda contiene ocho tablas:

- `sucursales`
- `empleados`
- `proveedores`
- `clientes`
- `productos`
- `inventario`
- `ventas`
- `detalle_ventas`

El SQL actualizado precarga una empresa, tres cuentas web, una fuente, metadatos del esquema y datos ficticios de la tienda. En una instalación nueva, `historial_consultas`, `resultados_consulta` y `reportes` quedan vacías: las acciones de esos módulos todavía se conservan en el navegador.

Durante la importación, el script consulta `INFORMATION_SCHEMA` para registrar las tablas, columnas y relaciones de la tienda en la base de la plataforma. En ejecución, la pantalla Esquema lee ese catálogo registrado; no descubre automáticamente una base externa.

El valor inicial `querybi_lector_demo` de la fuente es un dato de catálogo. El script no crea con ese nombre una cuenta del servidor MariaDB ni configura una conexión empresarial efectiva.

### 6.4. Configuración única

Utilizar el archivo existente:

```text
servidor/configuracion.local.php
```

El proyecto mantiene este archivo incluido en Git y en la publicación por FTP, según el acuerdo del grupo. Debe contener los datos correspondientes al entorno donde se ejecuta PHP.

La estructura de configuración es la siguiente; sustituir los valores de usuario y contraseña por los de la instalación:

```php
<?php
if (!defined('QBI_SERVIDOR')) {
    http_response_code(403);
    exit;
}

return [
    'host' => '127.0.0.1',
    'puerto' => 3306,
    'nombre' => 'querybi_ai_pagina',
    'usuario' => 'USUARIO_DE_MARIADB',
    'clave' => 'CONTRASENA_DE_MARIADB'
];
```

- `host`: servidor de la base de la plataforma, visto desde PHP.
- `puerto`: entero, sin comillas, correspondiente al servicio MariaDB.
- `nombre`: base interna de QueryBI; localmente `querybi_ai_pagina`.
- `usuario` y `clave`: credenciales del servidor de bases de datos, distintas de las cuentas web de demostración.

`nombre` no debe cambiarse a `db_empresa_demo` para realizar las pruebas actuales. La conexión de la plataforma y la futura conexión a una fuente empresarial tienen funciones diferentes.

Los valores incluidos en Git deben corresponder al entorno académico. No incorporar credenciales de servicios reales ajenos al proyecto.

### 6.5. Ejecutar con Apache de XAMPP

1. Colocar la carpeta completa del proyecto en el directorio de publicación de Apache. En XAMPP para Windows, por ejemplo: `C:/xampp/htdocs/querybi-ai/`.
2. Iniciar Apache y MySQL/MariaDB.
3. Ajustar `servidor/configuracion.local.php`.
4. Abrir la raíz del proyecto:

```text
http://localhost/querybi-ai/
```

El `index.php` de la raíz redirige a `cliente/index.html`.

Para acceder directamente al ingreso:

```text
http://localhost/querybi-ai/servidor/acceso/login.php
```

Los ejemplos de URL suponen que la carpeta se llama `querybi-ai`; adaptar ese segmento al nombre real.

### 6.6. Ejecutar con el servidor integrado de PHP

Con MariaDB iniciado y la configuración preparada, abrir una terminal en la raíz que contiene `index.php`, `cliente/`, `servidor/` y `README.md`:

```bash
php -S localhost:8000
```

Abrir:

```text
http://localhost:8000/
```

Detener con `Ctrl + C`. Esta alternativa requiere que PHP esté disponible en la terminal y se utiliza para desarrollo local.

### 6.7. Consideraciones de ejecución

- Ejecutar el proyecto completo desde su raíz, conservando ambas carpetas principales.
- Abrir la aplicación mediante HTTP en el servidor PHP; `file://` no ejecuta PHP.
- Live Server por sí solo no procesa el login ni las páginas protegidas.
- Mantener el mismo host y puerto durante las pruebas: `localStorage` depende del origen utilizado.
- Realizar una recarga forzada cuando se actualicen CSS o JavaScript y el navegador conserve versiones anteriores.
- MariaDB debe estar disponible al ingresar y al abrir páginas protegidas. Las páginas públicas estáticas no requieren consultar la base.

## 7. Cuentas de prueba

| Rol | Correo | Contraseña |
|---|---|---|
| Superadministrador | superadmin@querybi.test | SuperDemo2026! |
| Administrador | admin@tienda.test | AdminDemo2026! |
| Usuario | usuario@tienda.test | UsuarioDemo2026! |

Estas credenciales corresponden únicamente al entorno académico de demostración.

Las cuentas iniciales se insertan mediante el SQL y se leen desde `usuarios`, junto con el estado de `empresas`. PHP verifica el hash de la contraseña y obtiene el rol desde MariaDB. No existe un acceso alternativo mediante cuentas fijas en archivos.

El usuario no selecciona ni modifica su rol desde el formulario de ingreso.

Crear registros en los catálogos de administradores o usuarios no crea nuevas cuentas de acceso.

## 8. Funcionalidades

### 8.1. Sitio público

La presentación principal comprende cinco páginas:

| Página | Archivo | Contenido |
|---|---|---|
| Inicio | `cliente/index.html` | Propuesta del producto, ejemplo interactivo, funciones, recorrido y casos de uso |
| Producto | `cliente/paginas/producto.html` | Fuentes, esquema, IA, SQL, visualizaciones, reportes y responsabilidades del equipo |
| Cómo funciona | `cliente/paginas/como-funciona.html` | Conectar, configurar, preguntar y explorar resultados |
| Nosotros | `cliente/paginas/nosotros.html` | Propósito de la marca, principios y enfoque de trabajo |
| Ayuda | `cliente/paginas/ayuda.html` | 20 preguntas en cinco categorías y búsqueda local |

Se utiliza una presentación de producto completo, según el enfoque del frontend solicitado para el curso. La descripción comercial de una función no acredita que su integración de servidor esté terminada en el primer hito; su estado real aparece en este README.

Las cinco páginas cargan `sitio-publico.css` y `sitio-publico.js`, sin depender de los estilos ni de los scripts de los paneles.

El ejemplo de Inicio alterna entre ventas mensuales, productos y ciudades. Utiliza datos y SQL preparados en el JavaScript público; no ejecuta consultas ni depende de la fuente habilitada en el panel. Es independiente de los ejemplos internos sujetos al catálogo empresarial.

Ayuda permite buscar en preguntas y respuestas sin distinguir mayúsculas ni tildes. Filtra las categorías, muestra las coincidencias, despliega las respuestas encontradas, informa cuando no hay resultados y permite limpiar la búsqueda. Este proceso se realiza en el navegador, sin modificar datos empresariales.

La navegación y las preguntas frecuentes se pueden consultar sin JavaScript; el buscador y los selectores interactivos se muestran al habilitar sus funciones.

El acceso y los paneles utilizan sus propias hojas de estilo, ajustadas a la misma identidad visual de la web pública. La actualización conserva la estructura funcional, los formularios y los avisos sobre funciones pendientes. El pie de marca compartido sustituye el crédito académico y mantiene las notas técnicas existentes.

### 8.2. Autenticación y sesiones

El ingreso se valida en PHP mediante PDO y consultas preparadas sobre MariaDB. Las cuentas iniciales son de demostración, pero su autenticación utiliza los registros de la base de datos.

Las páginas protegidas comprueban la sesión y el rol:

- Sin sesión, se redirige al formulario de ingreso.
- Con un rol no autorizado, se responde con acceso denegado.
- Cada solicitud protegida vuelve a comprobar la cuenta, su rol y el estado de su empresa.
- Si la base no está disponible, se responde con un mensaje controlado y código 503.
- El cierre de sesión se procesa mediante una solicitud POST y sigue disponible aunque falle la BD.
- Los formularios de ingreso y salida utilizan un token de protección CSRF.

La información disponible en JavaScript se utiliza como contexto de la interfaz. La autorización de las páginas se decide en el servidor.

### 8.3. Panel empresarial

Muestra:

- Cantidad de registros del historial.
- Cantidad de preguntas pendientes.
- Cantidad de reportes de ejemplo guardados.
- Actividad reciente.
- Accesos a preguntas y ejemplos.

Los indicadores corresponden a los datos locales de la cuenta que está utilizando el navegador.

### 8.4. Preguntas libres

La pantalla Consultas permite:

1. Seleccionar una fuente habilitada del catálogo de su empresa, obtenido desde MariaDB.
2. Escribir una pregunta de entre 5 y 1000 caracteres.
3. Guardarla.
4. Abrir su detalle.

La pregunta queda con estado Pendiente.

Actualmente no se interpreta mediante IA, no se convierte automáticamente a SQL y no produce resultados empresariales.

Si no existen fuentes habilitadas, se impide registrar la pregunta hasta disponer de una.

### 8.5. Ejemplos de consultas

La pantalla Ejemplos permite explorar preguntas preparadas previamente.

Según el ejemplo seleccionado, muestra:

- La pregunta.
- Una tabla de resultados.
- Un gráfico.
- El SQL de referencia.
- Un estado sin resultados cuando corresponde.

Los datos y el SQL ya están definidos en el proyecto y coinciden con el esquema y los datos sintéticos del script inicial. Mostrar un ejemplo no implica ejecutar ese SQL contra una base de datos. Si se modifica la tienda, estos resultados preparados no se actualizan automáticamente.

Los ejemplos utilizan la fuente inicial con ID `1`, incluida en el SQL. El catálogo recibido desde PHP debe contenerla y mostrarla habilitada para la empresa de la sesión. Otra fuente registrada no sustituye automáticamente a Tienda Demo en estos ejemplos.

### 8.6. Historial y detalle

El historial reúne:

- Preguntas libres pendientes.
- Consultas de ejemplo.

Permite revisar el tipo de registro, la fuente, el estado y la fecha, y acceder a su detalle.

El detalle de una pregunta pendiente no presenta resultados ni SQL generados.

El detalle de un ejemplo permite acceder a su demostración cuando el ejemplo correspondiente está disponible.

### 8.7. Reportes

Permite guardar reportes a partir de los resultados de ejemplo, revisar su contenido y exportar sus datos en formato CSV.

Los reportes se conservan localmente y no representan información obtenida de una base empresarial real.

### 8.8. Fuentes de datos

El administrador puede crear, listar, filtrar, consultar el detalle y editar
fuentes de su empresa. Estas operaciones guardan y leen datos de MariaDB.

- La empresa se obtiene de la sesión; no de un campo del formulario.
- El servidor valida nombre, motor, servidor, puerto, base, usuario y descripción.
- Los nombres de fuente no se repiten dentro de la misma empresa.
- Los formularios POST comprueban el token CSRF.
- Después de guardar se redirige al detalle para evitar reenviar el formulario al recargar.
- Los registros inaccesibles para la empresa responden con el mismo error 404 que un registro inexistente.
- El detalle muestra también el resumen de tablas y campos registrados en el esquema.
- El catálogo funciona sin JavaScript. JavaScript se mantiene para la navegación y otros módulos.

Guardar configura una fuente; no comprueba la conexión con la base empresarial,
no ejecuta SQL y no descubre automáticamente su esquema. La habilitación es
un estado del catálogo, no una prueba de conectividad.

El formulario no solicita contraseñas ni permite asignar referencias de credenciales.
Si cambian los datos de conexión, se desvincula su referencia de credencial
para revisarla antes de utilizar la fuente. El esquema registrado no se actualiza
automáticamente y tendrá que revisarse al integrar su módulo.

El selector de Nueva pregunta obtiene un resumen de las fuentes de la empresa
desde PHP y deja de utilizar el catálogo de fuentes de localStorage.
Las preguntas todavía se guardan localmente; su persistencia es otro bloque.

### 8.9. Empresas y administradores

El superadministrador dispone de dos catálogos separados:

- Empresas.
- Administradores de empresa.

Ambos permiten listar, buscar, filtrar por estado, crear, consultar detalles y editar.

Reglas del catálogo:

- No se permiten nombres de empresa duplicados, ignorando mayúsculas y tildes.
- No se permiten correos duplicados entre administradores.
- Cada administrador debe estar asociado a una empresa existente.
- Un administrador activo requiere una empresa activa.
- Para desactivar una empresa deben desactivarse primero sus administradores.
- La edición conserva el identificador del registro.
- Se utiliza activación y desactivación, sin eliminación de registros.

Estas reglas afectan al catálogo local. Sus cambios no bloquean sesiones ni modifican las cuentas de ingreso en MariaDB.

### 8.10. Usuarios de empresa

El administrador empresarial puede listar, buscar, filtrar, crear, consultar y editar registros de usuarios.

En esta pantalla:

- El rol del registro es Usuario.
- La empresa se toma del contexto de la sesión.
- Se rechazan correos duplicados dentro del catálogo de usuarios de esa empresa.
- No se ofrece la creación de superadministradores ni administradores.

La pantalla está protegida por PHP. Los registros que muestra siguen siendo datos de demostración almacenados en el navegador.

### 8.11. Esquema y permisos

El selector de fuentes y los metadatos de tablas, campos y relaciones ya se
leen desde MariaDB, limitados a la empresa de la sesión. El detalle de una
fuente muestra un resumen de sus tablas y campos.

Las casillas del editor todavía se guardan localmente. Guardar y recuperar
sus marcas desde MariaDB y aplicarlas al ejecutar consultas quedan pendientes para un avance posterior.

La pantalla permite:

- Seleccionar tablas y campos.
- Consultar las relaciones de referencia.
- Ver la cantidad de tablas y campos seleccionados.
- Guardar la selección localmente por fuente, tabla y campo.
- Descartar cambios sin guardar.

Al desmarcar una tabla, sus campos quedan deshabilitados en el formulario
y dejan de contar en el resumen. Sus marcas se conservan para cuando
la tabla vuelva a seleccionarse.

Una fuente deshabilitada permite consultar el esquema de ejemplo, pero
no editar su selección.

Se muestran mensajes específicos cuando la fuente no existe, no tiene
un esquema disponible o el catálogo está vacío.

El archivo `cliente/assets/datos/esquema-demo.js` recibe los metadatos enviados por
PHP; ya no contiene un esquema fijo asociado al identificador ficticio tienda.

La selección no modifica estructuras de MySQL ni aplica restricciones
a las consultas. Su guardado persistente como permisos y su aplicación a consultas en el servidor están pendientes.

### 8.12. Perfil y recuperación de acceso

El perfil muestra desde PHP el nombre, correo, rol y ámbito de la cuenta
autenticada.

Permite guardar un nombre de presentación para el saludo del panel.
Esta preferencia pertenece a la cuenta actual en el navegador y no
modifica sus credenciales ni permisos.

El nombre debe tener entre 2 y 80 caracteres. Si el almacenamiento falla,
se conserva la preferencia anterior y se muestra un mensaje de error.

El usuario puede restablecer sus datos locales después de confirmar
la operación. Se eliminan únicamente las claves correspondientes
a su cuenta; se conservan las de otras cuentas y otras aplicaciones.

El cambio de contraseña y la recuperación por correo están pendientes
de integración. La página de recuperación explica esta limitación
y permite volver al ingreso o consultar la ayuda.

## 9. Datos locales y restablecimiento

Los registros de demostración se guardan utilizando claves separadas por el identificador de la cuenta.

Esto permite mantener separados los datos locales utilizados por las distintas cuentas de prueba en un mismo navegador.

Sin embargo:

- Los cambios no se sincronizan entre usuarios.
- Los cambios no se comparten entre dispositivos.
- Los cambios pueden perderse al borrar los datos del navegador.
- El almacenamiento puede estar bloqueado por la configuración del navegador.
- localStorage puede ser inspeccionado y modificado desde las herramientas del navegador.

Por ello, localStorage no se utiliza como autoridad para autenticar usuarios o autorizar páginas.

Las restricciones de acceso a páginas se comprueban mediante la sesión PHP.

### Restablecer la demostración

Desde Mi perfil se pueden restablecer los datos locales de la cuenta actual.

La acción elimina sus registros locales y permite volver a cargar los valores iniciales.

No modifica las cuentas ni las fuentes de MariaDB, ni elimina los datos locales correspondientes a otras cuentas.

## 10. Comprobación manual y evidencias

Realizar estas comprobaciones en la instalación local y repetir las pertinentes en el servidor del curso. Las filas indican el resultado esperado; no sustituyen la prueba real ni sus evidencias.

### Sitio público

| Prueba | Resultado esperado |
|---|---|
| Abrir la raíz del proyecto | Redirección a Inicio |
| Navegar por las cinco páginas | Enlaces, imágenes y estilos disponibles |
| Alternar Ventas, Productos y Ciudades en Inicio | Cambian pregunta, cifras, gráfico y SQL preparados |
| Desplegar el SQL de un ejemplo | Se muestra la consulta de referencia |
| Buscar `contraseña` y `contrasena` en Ayuda | Se encuentra la misma pregunta |
| Buscar un término inexistente | Se muestra el mensaje sin resultados |
| Limpiar la búsqueda | Reaparecen las 20 preguntas y las cinco categorías |
| Probar una pantalla estrecha | Menú público utilizable y contenido sin desbordamiento horizontal |
| Pulsar Acceder | Se abre el login actual |

### Acceso y persistencia en MariaDB

| Prueba | Resultado esperado |
|---|---|
| Ingresar con cada cuenta de demostración | Panel correspondiente a su rol |
| Ingresar con contraseña incorrecta | Acceso rechazado |
| Solicitar una página protegida sin sesión | Redirección al ingreso |
| Abrir una URL de otro rol | Respuesta de acceso denegado |
| Cerrar sesión y volver a una página protegida | Se requiere ingresar nuevamente |
| Registrar una fuente como administrador | Registro guardado en `fuentes_datos` y visible en su listado |
| Abrir otra sesión autorizada de la misma empresa | La fuente registrada continúa disponible |
| Editar la fuente y consultar su detalle | Cambios recuperados desde MariaDB |
| Repetir un nombre de fuente en la misma empresa | Rechazo del duplicado |
| Consultar una fuente ajena o inexistente | Respuesta que no expone datos de otras empresas |
| Abrir Esquema de la fuente inicial | Tablas, campos y relaciones registrados disponibles |

Para demostrar el flujo persistente mínimo, registrar una fuente con nombre de prueba, abrir su detalle, verificar el registro en MariaDB y consultarlo en una nueva sesión autorizada. Crear una fuente no carga automáticamente sus metadatos ni prueba la conexión a su base.

### Módulos con almacenamiento local

| Prueba | Resultado esperado |
|---|---|
| Registrar una pregunta válida con fuente habilitada | Registro local con estado Pendiente |
| Registrar una pregunta vacía | Mensaje de validación |
| Abrir un ejemplo interno disponible | Resultados preparados, tabla, gráfico y SQL |
| Guardar un reporte de ejemplo | Aparece en Reportes y permite exportar CSV |
| Crear empresa, administrador o usuario desde su catálogo | Registro guardado localmente; las cuentas de ingreso de MariaDB no cambian |
| Buscar un registro local inexistente | Mensaje sin coincidencias |
| Guardar selección del esquema y recargar | Se conservan las marcas en el navegador si el almacenamiento está disponible |
| Restablecer datos desde Mi perfil | Se eliminan los datos locales de esa cuenta, conservando cuentas y fuentes de MariaDB |

Revisar también validaciones, cancelación de formularios, enlaces de retorno, consola y solicitudes de recursos en el navegador.

### Evidencias de la entrega

Preparar capturas y archivos que documenten:

- Navegación pública y pantallas internas relacionadas con el dominio.
- Acceso por roles y rechazo de una ruta no autorizada.
- Registro y consulta persistente de una fuente.
- Bases y tablas creadas desde el SQL.
- ERD, diccionario de datos y relaciones.
- Validaciones y mensajes de los formularios.
- Historial de Git y forma de ejecución.

Las evidencias deben proceder de la ejecución real. Los ejemplos preparados de consultas y los catálogos locales deben identificarse con su alcance al explicar el producto. El informe y los mockups deben corresponder a las interfaces finales utilizadas en la demostración.

## 11. Cierre del primer hito y desarrollo posterior

### Para cerrar el primer hito

- Comprobar en XAMPP la navegación, los formularios y las restricciones reales por rol.
- Demostrar el registro y la consulta de una fuente persistida en MariaDB.
- Verificar el script inicial, sus tablas, relaciones y datos de prueba.
- Preparar las evidencias de navegación, validación y persistencia.
- Mantener el README y el repositorio actualizados.
- Completar el informe, ERD, diccionario de datos y propuesta de investigación
  con el alcance y los entregables exigidos por el curso.

La autenticación y las fuentes están conectadas a MariaDB. Los otros catálogos,
las preguntas, el historial y los reportes conservan el alcance de prototipo local.
La selección del esquema también se guarda localmente. Estas limitaciones deben
explicarse en el informe y en la demostración.

### Desarrollo posterior según la priorización de hitos

El primer hito requiere al menos un flujo de registro y consulta persistente;
no exige completar todos los módulos. Las siguientes tareas describen evolución
pendiente del producto y no una lista de requisitos obligatorios del primer hito:

- Integrar los catálogos de empresas, administradores y usuarios con MariaDB.
- Guardar preguntas, historial y reportes desde PHP con separación por empresa.
- Guardar la selección de tablas y campos y aplicarla al ejecutar consultas.
- Integrar el procesamiento de lenguaje natural, la generación y validación SQL
  y las consultas de lectura autorizadas en los hitos que exigen IA real.
- Incorporar resultados y reportes a partir de consultas ejecutadas.
- Desarrollar la gestión de contraseñas y recuperación conforme al alcance acordado.
- Adaptar la aplicación a Laravel/MVC para el segundo hito.

Se conserva la arquitectura cliente-servidor para el primer avance, según
la indicación oral del docente comunicada al grupo. El plan de investigación
debe proponer desde este hito cómo se evaluará la IA; su integración funcional
corresponde al segundo avance y su evaluación completa al trabajo final.

## 12. Trabajo con Git y GitHub

El repositorio mantiene el historial de cambios mediante Git.

Cada commit debe representar una mejora concreta y comprobable, por ejemplo:

- Incorporar sesiones y restricciones por rol.
- Completar la gestión de fuentes.
- Separar preguntas libres de ejemplos.
- Incorporar los catálogos administrativos.
- Renovar Inicio, Producto, Cómo funciona, Nosotros y Ayuda.
- Actualizar la documentación.

### Preparación inicial

Clonar el repositorio utilizando su dirección real:

```bash
git clone URL_DEL_REPOSITORIO
cd NOMBRE_DE_LA_CARPETA
```

Configurar la identidad utilizada en los commits:

```bash
git config user.name "Tu Nombre"
git config user.email "tu-correo@ejemplo.com"
```

Después, iniciar el servidor PHP como se indica en la sección de ejecución local.

### Ramas de trabajo

Los siguientes ejemplos utilizan `main` como rama principal. Si el repositorio utiliza otro nombre, adaptar los comandos.

Para comenzar una tarea, con el trabajo anterior ya guardado:

```bash
git switch main
git pull
git switch -c feature/nombre-de-la-tarea
```

Si ya se está trabajando en una rama para la tarea actual, se continúa en esa misma rama.

### Guardar avances

Revisar los cambios:

```bash
git status
git diff
```

Agregar únicamente los archivos del bloque:

```bash
git add ruta/al/archivo1 ruta/al/archivo2
```

Revisar lo preparado:

```bash
git diff --cached
```

Crear el commit:

```bash
git commit -m "feat: describir la funcionalidad incorporada"
```

Se pueden utilizar estos prefijos:

| Prefijo | Uso |
|---|---|
| feat | Funcionalidad nueva |
| fix | Corrección de un error |
| docs | Documentación |
| style | Cambios de formato sin modificar el comportamiento |
| refactor | Reorganización del código conservando su comportamiento |

### Publicar una rama

La primera vez:

```bash
git push -u origin feature/nombre-de-la-tarea
```

En las siguientes publicaciones de esa rama:

```bash
git push
```

En GitHub se abre un Pull Request hacia `main`, indicando:

- Qué problema o necesidad resuelve.
- Qué cambió.
- Cómo comprobarlo.
- Qué limitaciones permanecen.

Revisar el cambio y sus pruebas antes de integrarlo.

### Después de integrar una rama

```bash
git switch main
git pull
git branch -d feature/nombre-de-la-tarea
```

### Conflictos

Si Git informa de un conflicto:

1. Revisar los archivos afectados.
2. Comparar ambas versiones.
3. Conservar la combinación correcta.
4. Eliminar las marcas de conflicto.
5. Probar nuevamente el funcionamiento.
6. Agregar los archivos resueltos y finalizar el merge.

No se deben descartar cambios de otro integrante sin revisarlos.

### Archivos que no deben subirse

- Datos personales o empresariales reales.
- Archivos temporales.
- Registros de ejecución.
- Copias comprimidas del proyecto.
- Configuraciones particulares del editor que no necesite el equipo.

Las cuentas incluidas en este proyecto son exclusivamente de demostración.

## 13. Publicación por FTP en el servidor del curso

El servidor del profesor debe procesar PHP, permitir sesiones y disponer de un motor de bases de datos compatible con el script. La compatibilidad preparada corresponde a MariaDB 10.4.32; confirmar el motor y la versión del destino antes de importar.

### Preparación de datos

1. Entregar `QueryBI_AI_hito1_XAMPP.sql` al profesor para crear las dos bases en una instalación nueva.
2. Confirmar los nombres de las bases y las credenciales de conexión que se utilizarán en ese servidor.
3. Si los nombres difieren de los locales, mantener coherencia en todas las referencias del SQL, las consultas de metadatos, la configuración de la plataforma y los datos del catálogo de fuentes.
4. Ajustar `servidor/configuracion.local.php` con el host, puerto, base de la plataforma, usuario y contraseña asignados.

Transferir un archivo SQL por FTP no lo ejecuta. Su importación se realiza mediante la herramienta de administración de bases de datos que corresponda al servidor.

### Transferencia de la aplicación

1. Comprobar primero el funcionamiento local.
2. Subir el proyecto conservando `index.php`, `cliente/`, `servidor/` y sus subcarpetas.
3. Incluir `servidor/configuracion.local.php`, según el procedimiento acordado para el curso.
4. Conservar los nombres de archivo y sus mayúsculas o minúsculas.
5. Omitir el directorio interno de Git y los archivos temporales de trabajo.
6. Entregar el README y el script SQL junto con el código, según las indicaciones del docente.

### Verificación en el destino

- Abrir la raíz del proyecto y comprobar su redirección.
- Revisar las cinco páginas públicas y la carga de CSS, JavaScript e imágenes.
- Ingresar con las cuentas de demostración y comprobar los permisos por rol.
- Registrar y recuperar una fuente en la base del servidor.
- Probar el cierre de sesión.
- Comprobar las funciones locales desde el navegador utilizado en la demostración.

Los datos locales del navegador de desarrollo no viajan dentro de los archivos enviados por FTP. El navegador del servidor publicado constituye otro origen y mantiene su propio almacenamiento local.

GitHub almacena el código y su historial. GitHub Pages por sí solo no procesa PHP ni ejecuta el acceso y las operaciones de servidor de esta aplicación.

## 14. Problemas frecuentes

| Situación | Qué revisar |
|---|---|
| El login muestra servicio no disponible | MariaDB iniciado, extensión `pdo_mysql`, credenciales y nombre de base en la configuración |
| La raíz no abre correctamente | Servidor PHP iniciado desde la carpeta raíz y presencia de `index.php` |
| El navegador descarga PHP o muestra su código | El servidor utilizado no está procesando PHP |
| CSS, JavaScript o imágenes no aparecen | Estructura `cliente/assets/`, rutas relativas, nombres exactos y recarga forzada |
| Cambios de diseño no se reflejan | Caché del navegador; recargar con Ctrl + F5 |
| El SQL informa que una base o tabla ya existe | Se está intentando reimportar un script de instalación inicial |
| Una fuente nueva no muestra esquema | El registro de la fuente no descubre ni importa automáticamente sus metadatos |
| No aparecen preguntas o reportes en otro equipo | Esos módulos todavía utilizan almacenamiento local del navegador |
| Un usuario creado en el catálogo no puede ingresar | El catálogo local no crea cuentas de autenticación en MariaDB |
| Desactivar un registro local no bloquea el acceso | Los catálogos de demostración no actualizan el estado de las cuentas reales |
| La recuperación no envía un correo | El envío y el cambio de contraseña todavía no están integrados |
| Abrir archivos compartidos o de configuración devuelve acceso denegado | Son archivos internos; se utilizan a través de las páginas de la aplicación |

## 15. Equipo

- Universidad: Universidad Nacional del Santa.
- Escuela profesional: Ingeniería de Sistemas e Informática.
- Curso: Aplicaciones Distribuidas I.
- Grupo: 04.
- Proyecto: QueryBI AI.
