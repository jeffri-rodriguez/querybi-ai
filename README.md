# QueryBI AI

Proyecto académico del Grupo 04 para el curso de Aplicaciones Distribuidas I de la Universidad Nacional del Santa.

QueryBI AI es una aplicación web orientada a facilitar la consulta y el análisis de información empresarial mediante preguntas en lenguaje natural.

Actualmente se encuentra en desarrollo para el primer hito. Esta versión implementa la navegación, el acceso mediante sesiones PHP, las restricciones por rol y los principales flujos de interfaz con datos de demostración.

La autenticación y la gestión de fuentes ya utilizan MariaDB mediante PDO. Las preguntas, reportes y los otros catálogos de la interfaz siguen siendo locales hasta sus bloques de integración. El procesamiento mediante inteligencia artificial está pendiente.

## 1. Estado actual del proyecto

| Componente | Estado actual |
|---|---|
| Páginas públicas | Implementadas |
| Inicio y cierre de sesión | Implementados en PHP con usuarios consultados en MariaDB |
| Restricción de acceso por rol | Aplicada en PHP; cuenta, empresa y rol se revisan en cada solicitud protegida |
| Panel empresarial | Implementado con indicadores de registros locales |
| Registro de preguntas libres | Implementado; las preguntas quedan pendientes |
| Ejemplos de consultas | Resultados, gráficos y SQL preparados previamente |
| Historial y detalle | Implementados con almacenamiento local |
| Reportes | Vista previa y exportación CSV de datos de ejemplo |
| Fuentes de datos | Registro, listado, filtros, detalle y edición persistentes en MariaDB, por empresa |
| Empresas y administradores | Catálogos locales para el superadministrador |
| Usuarios de empresa | Catálogo local para el administrador empresarial |
| Esquema y permisos | Selección local de tablas y campos por fuente; aplicación de permisos en el servidor pendiente |
| Perfil | Datos de sesión y nombre de presentación local |
| Recuperación y cambio de contraseña | Pendientes; se informa su disponibilidad |
| Base de datos (MariaDB en XAMPP) | Autenticación y fuentes conectadas; flujo de registro y consulta persistente implementado |
| Generación de SQL mediante IA | Pendiente |
| Ejecución de consultas contra bases empresariales | Pendiente |

Este README describe el estado del código. La entrega completa del primer hito debe verificarse también contra los documentos del curso y sus entregables.

## 2. Arquitectura

La aplicación utiliza una arquitectura cliente-servidor.

### Cliente

El navegador se encarga de:

- Mostrar las páginas HTML y los estilos CSS.
- Ejecutar JavaScript.
- Gestionar formularios, filtros, tablas y gráficos.
- Mantener los registros de demostración en localStorage.
- Mostrar mensajes de validación, estados vacíos y resultados preparados.

### Servidor

PHP se encarga de:

- Consultar las cuentas de MariaDB y verificar sus contraseñas con password_verify.
- Comprobar que el usuario y su empresa estén activos.
- Validar, registrar y editar fuentes mediante consultas preparadas y protección CSRF.
- Limitar el acceso a las fuentes a la empresa de la sesión.
- Crear y mantener la sesión.
- Comprobar el rol antes de entregar una página protegida.
- Generar la navegación correspondiente al usuario.
- Procesar el cierre de sesión.

El acceso a una página protegida no depende únicamente de que su enlace aparezca en el menú. La comprobación también se realiza en PHP cuando se solicita la dirección directamente.

### Almacenamiento actual

Se utilizan estos mecanismos:

| Mecanismo | Finalidad |
|---|---|
| Sesión PHP | Mantener la identidad autenticada y su rol |
| MariaDB | Cuentas, empresas y fuentes utilizadas por la aplicación; metadatos iniciales del SQL |
| Archivos de datos de prueba | Proporcionar ejemplos y catálogos locales iniciales |
| localStorage | Conservar registros de demostración en el navegador |

localStorage proporciona almacenamiento local entre visitas, pero no sustituye una base de datos compartida en el servidor.

La conexión PDO consulta `querybi_ai_pagina` para autenticar cuentas. El catálogo de fuentes ya utiliza MariaDB. Los demás módulos se integrarán progresivamente.

## 3. Tecnologías utilizadas

- HTML5 para la estructura de las interfaces.
- CSS3 en archivos externos para el diseño.
- JavaScript para las interacciones del navegador.
- PHP para las sesiones y el control de acceso.
- localStorage para los datos locales de demostración.
- Git para el control de versiones.
- GitHub para el repositorio y la colaboración.

El proyecto se desarrolla sin un framework de aplicación y no necesita instalar dependencias con npm o Composer para ejecutarse en su estado actual.

## 4. Roles y acceso

### Superadministrador

Administra el catálogo de la plataforma:

- Panel general.
- Empresas.
- Administradores de empresa.
- Su perfil.

No tiene acceso automático al espacio de consultas ni a la administración interna de las empresas.

### Administrador de empresa

Accede al espacio de trabajo empresarial y a sus pantallas administrativas:

- Panel empresarial.
- Consultas.
- Ejemplos.
- Historial.
- Reportes.
- Fuentes de datos.
- Esquema y permisos.
- Usuarios de empresa.
- Su perfil.

Los registros creados desde Usuarios tienen el rol Usuario y se asocian a la empresa de la sesión.

### Usuario

Accede a las funciones de consulta:

- Panel empresarial.
- Consultas.
- Ejemplos.
- Historial.
- Reportes.
- Su perfil.

No puede acceder a las pantallas de administración de fuentes, esquema, usuarios o plataforma.

### Matriz de acceso

| Área | Superadministrador | Administrador | Usuario |
|---|---|---|---|
| Páginas públicas | Sí | Sí | Sí |
| Panel de plataforma | Sí | No | No |
| Empresas | Sí | No | No |
| Administradores de empresa | Sí | No | No |
| Panel empresarial | No | Sí | Sí |
| Consultas y ejemplos | No | Sí | Sí |
| Historial y reportes | No | Sí | Sí |
| Fuentes de datos | No | Sí | No |
| Esquema y permisos | No | Sí | No |
| Usuarios de empresa | No | Sí | No |
| Mi perfil | Sí | Sí | Sí |

Las páginas públicas también se pueden visitar sin iniciar sesión.

## 5. Estructura del proyecto

Las rutas siguientes son relativas a la raíz del proyecto:

```text
querybi-ai/
    index.php
    README.md
    .gitignore
    QueryBI_AI_hito1_XAMPP.sql

    cliente/
        index.html

        paginas/
            nosotros.html
            como-funciona.html
            ayuda.html
            recuperar-contrasena.html

        assets/
            css/
            js/
            datos/
            imagenes/

    servidor/
        configuracion.local.php

        compartido/
            sesion.php
            base-datos.php
            fuentes.php
            navegacion.php
            vista-fuentes.php
            vista-gestion.php

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

### Responsabilidad de las carpetas

| Ruta | Responsabilidad |
|---|---|
| `index.php` | Entrada del proyecto; redirige a la portada del cliente |
| `cliente/index.html` | Portada pública |
| `cliente/paginas` | Páginas HTML públicas e información de recuperación de acceso |
| `cliente/assets/css` | Estilos de las interfaces |
| `cliente/assets/js` | Comportamiento ejecutado en el navegador |
| `cliente/assets/datos` | Ejemplos preparados y datos iniciales de demostración |
| `cliente/assets/imagenes` | Logo, favicon y recursos gráficos |
| `servidor/acceso` | Inicio y cierre de sesión |
| `servidor/aplicacion` | Páginas PHP del espacio de consultas y análisis |
| `servidor/administracion` | Páginas PHP del administrador empresarial |
| `servidor/plataforma` | Páginas PHP del superadministrador |
| `servidor/compartido` | Funciones y vistas PHP compartidas |

Las páginas PHP se ejecutan en el servidor y generan HTML para el navegador.
Por eso se encuentran dentro de `servidor/`, aunque presenten formularios,
listados y otras partes de la interfaz.

La arquitectura es cliente-servidor: el navegador presenta la interfaz y
ejecuta JavaScript; PHP procesa sesiones, permisos, validaciones y operaciones
con MariaDB.

### Archivos compartidos principales

- `servidor/compartido/sesion.php`: sesiones, comprobación de roles,
  generación de URL y funciones auxiliares.
- `servidor/compartido/base-datos.php`: conexión PDO a la base de la plataforma.
- `servidor/configuracion.local.php`: único archivo de configuración.
  Se incluye en Git y FTP por acuerdo del grupo y se ajusta al entorno.
- `servidor/compartido/fuentes.php`: validaciones, operaciones persistentes
  de fuentes y lectura de metadatos del esquema.
- `servidor/compartido/vista-fuentes.php`: listado, formulario y detalle de fuentes.
- `servidor/compartido/navegacion.php`: encabezado y menú según el rol.
- `servidor/compartido/vista-gestion.php`: presentación de los catálogos
  locales de empresas, administradores y usuarios.
- `cliente/assets/js/principal.js`: utilidades de tablas, mensajes
  y almacenamiento local.
- `cliente/assets/js/gestion.js`: comportamiento de los catálogos locales.
- `cliente/assets/datos/datos-demo.js`: resultados preparados y datos ficticios.
- `cliente/assets/datos/esquema-demo.js`: recibe los metadatos enviados por PHP.

## 6. Ejecución local

### Requisitos

- PHP con sesiones, PDO y pdo_mysql habilitados.
- MariaDB 10.4.32, utilizado en XAMPP para este proyecto.
- Un navegador con JavaScript, cookies y localStorage habilitados.
- Git, si se trabajará con el repositorio.

### Configuración de MariaDB

1. Iniciar el módulo MySQL de XAMPP, que ejecuta MariaDB.
2. Si las bases todavía no existen, importar `QueryBI_AI_hito1_XAMPP.sql` desde phpMyAdmin. Si ya se importó, no ejecutarlo otra vez.
3. Utilizar el único archivo `servidor/configuracion.local.php` incluido en el proyecto.
4. Configurar host, puerto, nombre de base, usuario y contraseña en el archivo local.
5. El valor de `nombre` corresponde a la base de la plataforma: localmente `querybi_ai_pagina`. La tienda `db_empresa_demo` es una fuente independiente.
6. El grupo incluye esta configuración en Git y FTP. En el servidor del profesor se deben colocar los datos de conexión que corresponden a esa instalación.

MariaDB debe estar iniciado tanto al ingresar como al abrir páginas protegidas.

### Uso actual de las dos bases de datos

| Base | Uso actual |
|---|---|
| `querybi_ai_pagina` | Autenticación, consulta del estado de la empresa, gestión persistente de fuentes y lectura de metadatos |
| `db_empresa_demo` | Dataset sintético preparado para consultas empresariales posteriores |

La conexión PDO de la aplicación utiliza `querybi_ai_pagina`.

Durante la instalación, el SQL consulta la estructura de `db_empresa_demo`
y registra sus metadatos en la base de la plataforma. La aplicación todavía
no abre una conexión para consultar las ventas de la tienda.

Los resultados de Ejemplos están preparados en JavaScript y coinciden con
los datos del script inicial. No se actualizan automáticamente al modificar
la tienda.

En una instalación nueva con el SQL revisado, las tablas
`historial_consultas`, `resultados_consulta` y `reportes` quedan vacías.
Estos módulos todavía almacenan sus registros en el navegador.

El script es de instalación inicial. Actualizar su archivo en el repositorio
no modifica las bases ya creadas y no requiere reimportarlo.

### Opción A: servidor integrado de PHP

Abre una terminal en la raíz del proyecto, donde están `index.php`,
`cliente/`, `servidor/` y `README.md`, y ejecuta:

```bash
php -S localhost:8000
```

Luego abre:

```text
http://localhost:8000/
```

El archivo `index.php` de la raíz redirige a `cliente/index.html`.

El servidor debe iniciarse desde la raíz del proyecto para que estén
disponibles tanto los recursos del cliente como las páginas PHP del servidor.

Para detener el servidor, presiona `Ctrl + C` en la terminal.

El servidor integrado se utiliza para desarrollo local.

### Opción B: Apache con PHP

1. Coloca la carpeta del proyecto en el directorio de publicación de Apache.
2. Comprueba que Apache tenga PHP habilitado.
3. Inicia el servidor.
4. Accede a la dirección correspondiente.

Por ejemplo, si la carpeta se llama `querybi-ai`:

```text
http://localhost/querybi-ai/
```

### Consideraciones

- No ejecutes la aplicación abriendo los archivos mediante `file://`.
- Live Server por sí solo no procesa las páginas PHP.
- Mantén el mismo host y puerto durante las pruebas: localStorage depende del origen utilizado.
- Si cambias archivos JavaScript o CSS y no ves los cambios, realiza una recarga forzada del navegador.

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

### 8.1. Páginas públicas

Incluyen:

- Presentación de QueryBI AI.
- Información del equipo, misión y visión.
- Explicación del funcionamiento previsto.
- Preguntas frecuentes y orientación.
- Acceso al inicio de sesión.

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

## 10. Comprobación manual

Antes de guardar un bloque de cambios, revisar lo siguiente:

| Prueba | Resultado esperado |
|---|---|
| Ingresar con una cuenta válida | Se abre el panel correspondiente a su rol |
| Ingresar con contraseña incorrecta | Se rechaza el acceso |
| Abrir una página protegida sin sesión | Se redirige al ingreso |
| Abrir una página de otro rol mediante su URL | Se muestra acceso denegado |
| Cerrar sesión | Las páginas protegidas requieren ingresar nuevamente |
| Registrar una pregunta válida | Se guarda como Pendiente y se abre su detalle |
| Registrar una pregunta vacía | Se rechaza |
| Abrir un ejemplo | Se presentan sus datos preparados |
| Guardar un reporte de ejemplo | Aparece en Reportes |
| Crear y editar una fuente | Los cambios se guardan en MariaDB y aparecen en otra sesión autorizada |
| Crear una empresa y su administrador | Ambos registros quedan asociados |
| Repetir un nombre de empresa | Se rechaza el duplicado |
| Desactivar una empresa con administradores activos | Se impide la operación |
| Crear un usuario empresarial | Se asignan el rol Usuario y la empresa de la sesión |
| Buscar un registro inexistente | Se muestra el estado sin coincidencias |
| Recargar después de guardar | Los datos permanecen si localStorage está disponible |
| Consultar desde una pantalla pequeña | La navegación, formularios y tablas siguen siendo utilizables |

También deben revisarse los mensajes de error, los botones de cancelar, los enlaces de retorno y la consola del navegador.

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

La rama `main` mantiene la versión integrada del proyecto.

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

Otro integrante revisa el cambio antes de integrarlo.

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

## 13. Publicación en el servidor del curso

El servidor de destino debe procesar PHP y permitir el uso de sesiones.

Para publicar:

1. Verificar que la versión local funcione.
2. Transferir los archivos mediante el mecanismo indicado por el docente.
3. Mantener la estructura de carpetas y los nombres de archivo.
4. Omitir los archivos internos de Git y los archivos temporales.
5. Probar el ingreso, la salida y el acceso por rol en el servidor.
6. Comprobar que se carguen correctamente CSS, JavaScript e imágenes.

GitHub se utiliza para el repositorio. GitHub Pages no ejecuta PHP y, por tanto, no permite utilizar por sí solo las funciones de sesión de esta versión.

El grupo subirá el proyecto mediante FTP, incluido `servidor/configuracion.local.php`,
y entregará al profesor el SQL completo de las dos bases. El SQL debe ejecutarse
mediante una herramienta de administración; transferirlo por FTP no lo ejecuta.
Se confirmará el motor y versión del servidor del curso antes de importar el script.
La configuración se ajustará con los nombres y credenciales de esa instalación.
Los catálogos que siguen en localStorage no se convierten automáticamente en datos compartidos.

## 14. Equipo

- Universidad: Universidad Nacional del Santa.
- Escuela profesional: Ingeniería de Sistemas e Informática.
- Curso: Aplicaciones Distribuidas I.
- Grupo: 04.
- Proyecto: QueryBI AI.