# QueryBI AI

Proyecto académico del Grupo 04 para el curso de Aplicaciones Distribuidas I de la Universidad Nacional del Santa.

QueryBI AI es una aplicación web orientada a facilitar la consulta y el análisis de información empresarial mediante preguntas en lenguaje natural.

Actualmente se encuentra en desarrollo para el primer hito. Esta versión implementa la navegación, el acceso mediante sesiones PHP, las restricciones por rol y los principales flujos de interfaz con datos de demostración.

La integración con MySQL y el procesamiento de preguntas mediante inteligencia artificial todavía están pendientes.

## 1. Estado actual del proyecto

| Componente | Estado actual |
|---|---|
| Páginas públicas | Implementadas |
| Inicio y cierre de sesión | Implementados en PHP con cuentas fijas de prueba |
| Restricción de acceso por rol | Aplicada en el servidor |
| Panel empresarial | Implementado con indicadores de registros locales |
| Registro de preguntas libres | Implementado; las preguntas quedan pendientes |
| Ejemplos de consultas | Resultados, gráficos y SQL preparados previamente |
| Historial y detalle | Implementados con almacenamiento local |
| Reportes | Vista previa y exportación CSV de datos de ejemplo |
| Fuentes de datos | Catálogo local con creación, edición y detalle |
| Empresas y administradores | Catálogos locales para el superadministrador |
| Usuarios de empresa | Catálogo local para el administrador empresarial |
| Esquema y permisos | Selección local de tablas y campos por fuente; aplicación de permisos en el servidor pendiente |
| Perfil | Nombre de presentación guardado localmente |
| Recuperación y cambio de contraseña | Demostración de interfaz |
| Base de datos MySQL | Pendiente de diseño e integración |
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

- Validar las credenciales de las cuentas de prueba.
- Crear y mantener la sesión.
- Comprobar el rol antes de entregar una página protegida.
- Generar la navegación correspondiente al usuario.
- Procesar el cierre de sesión.

El acceso a una página protegida no depende únicamente de que su enlace aparezca en el menú. La comprobación también se realiza en PHP cuando se solicita la dirección directamente.

### Almacenamiento actual

Se utilizan tres mecanismos diferentes:

| Mecanismo | Finalidad |
|---|---|
| Sesión PHP | Mantener la identidad autenticada y su rol |
| Archivos de datos de prueba | Proporcionar cuentas y ejemplos iniciales |
| localStorage | Conservar registros de demostración en el navegador |

localStorage proporciona almacenamiento local entre visitas, pero no sustituye una base de datos compartida en el servidor.

Actualmente no existe conexión con MySQL.

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
    index.html
    README.md
    .gitignore

    paginas/
        nosotros.html
        como-funciona.html
        ayuda.html

    acceso/
        login.php
        salir.php
        recuperar-contrasena.html

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

    servidor/
        sesion.php
        cuentas-demo.php
        navegacion.php
        vista-gestion.php

    assets/
        css/
            estilos.css
            publico.css
            acceso.css
            aplicacion.css
            consultas.css
            administracion.css

        js/
            principal.js
            acceso.js
            panel.js
            consultas.js
            detalle-consulta.js
            ejemplos.js
            graficos.js
            historial.js
            reportes.js
            perfil.js
            fuentes.js
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
```

### Responsabilidad de las carpetas

| Carpeta | Contenido |
|---|---|
| paginas | Información pública del proyecto |
| acceso | Inicio de sesión, cierre y recuperación de demostración |
| aplicacion | Espacio de consultas y análisis |
| administracion | Funciones del administrador empresarial |
| plataforma | Funciones del superadministrador |
| servidor | Código PHP compartido |
| assets/css | Estilos separados por área |
| assets/js | Comportamiento de las interfaces |
| assets/datos | Datos ficticios iniciales |
| assets/imagenes | Recursos gráficos |

### Archivos compartidos principales

- `servidor/sesion.php`: manejo de sesiones, comprobación de roles y funciones auxiliares.
- `servidor/cuentas-demo.php`: cuentas fijas utilizadas para probar el ingreso.
- `servidor/navegacion.php`: encabezado y menú según el rol autenticado.
- `servidor/vista-gestion.php`: presentación compartida de empresas, administradores y usuarios.
- `assets/js/principal.js`: utilidades de almacenamiento, tablas, mensajes y navegación.
- `assets/js/gestion.js`: formularios, filtros y detalles de los catálogos administrativos.
- `assets/datos/datos-demo.js`: ejemplos y registros iniciales.

## 6. Ejecución local

### Requisitos

- PHP con soporte de sesiones. Las pruebas de desarrollo se realizaron con PHP 8.3.
- Un navegador con JavaScript, cookies y localStorage habilitados.
- Git, si se trabajará con el repositorio.

Para esta versión no es necesario iniciar MySQL.

### Opción A: servidor integrado de PHP

Abre una terminal en la carpeta que contiene `index.html` y ejecuta:

```bash
php -S localhost:8000
```

Luego abre:

```text
http://localhost:8000/
```

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

Las cuentas se definen en `servidor/cuentas-demo.php`. El servidor comprueba la contraseña y obtiene el rol desde esa configuración.

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

El ingreso se valida en PHP utilizando las cuentas de prueba.

Las páginas protegidas comprueban la sesión y el rol:

- Sin sesión, se redirige al formulario de ingreso.
- Con un rol no autorizado, se responde con acceso denegado.
- El cierre de sesión se procesa mediante una solicitud POST.
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

1. Seleccionar una fuente habilitada en el catálogo local.
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

Los datos y el SQL ya están definidos en el proyecto. Mostrar un ejemplo no implica ejecutar ese SQL contra una base de datos.

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

El administrador puede:

- Consultar el catálogo.
- Buscar y filtrar fuentes.
- Crear una fuente.
- Editar sus datos.
- Consultar su detalle.
- Habilitarla o deshabilitarla en el catálogo local.

El formulario valida los datos de configuración, incluidos el nombre y el puerto.

Esta funcionalidad registra una configuración de demostración. No abre conexiones, no verifica credenciales de MySQL ni descubre tablas.

La contraseña de prueba introducida en el formulario no se conserva.

La vista de esquema disponible corresponde al ejemplo preparado de Tienda Demo. Crear otra fuente no genera automáticamente un esquema para ella.

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

Estas reglas afectan al catálogo local. Sus cambios no bloquean sesiones ni modifican las cuentas fijas de ingreso.

### 8.10. Usuarios de empresa

El administrador empresarial puede listar, buscar, filtrar, crear, consultar y editar registros de usuarios.

En esta pantalla:

- El rol del registro es Usuario.
- La empresa se toma del contexto de la sesión.
- Se rechazan correos duplicados dentro del catálogo de usuarios de esa empresa.
- No se ofrece la creación de superadministradores ni administradores.

La pantalla está protegida por PHP. Los registros que muestra siguen siendo datos de demostración almacenados en el navegador.

### 8.11. Esquema y permisos

El administrador puede seleccionar una fuente del catálogo y consultar
su esquema de demostración cuando esté disponible.

La pantalla permite:

- Seleccionar tablas y campos.
- Consultar las relaciones de referencia.
- Ver la cantidad de tablas y campos seleccionados.
- Guardar la selección localmente por fuente, tabla y campo.
- Descartar cambios sin guardar.
- Recuperar las selecciones guardadas en la versión anterior.

Al desmarcar una tabla, sus campos quedan deshabilitados en el formulario
y dejan de contar en el resumen. Sus marcas se conservan para cuando
la tabla vuelva a seleccionarse.

Una fuente deshabilitada permite consultar el esquema de ejemplo, pero
no editar su selección.

Se muestran mensajes específicos cuando la fuente no existe, no tiene
un esquema disponible o el catálogo está vacío.

Los metadatos ilustrativos se encuentran en
`assets/datos/esquema-demo.js`. Actualmente corresponden al ejemplo
de Tienda Demo y no constituyen el diseño definitivo de la base de datos.

La selección no modifica estructuras de MySQL ni aplica restricciones
a las consultas. Su validación y aplicación en el servidor están pendientes.

### 8.12. Perfil y recuperación de acceso

El perfil muestra el correo y el rol de la cuenta autenticada.

Permite guardar un nombre de presentación en el navegador. Esto no modifica la identidad de la cuenta en el servidor.

Los formularios de cambio y recuperación de contraseña son demostraciones:

- No cambian las credenciales de ingreso.
- No envían correos.
- No generan enlaces reales de recuperación.

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

No modifica las cuentas fijas de acceso ni elimina los datos locales correspondientes a otras cuentas.

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
| Crear y editar una fuente | Los cambios aparecen en su catálogo |
| Crear una empresa y su administrador | Ambos registros quedan asociados |
| Repetir un nombre de empresa | Se rechaza el duplicado |
| Desactivar una empresa con administradores activos | Se impide la operación |
| Crear un usuario empresarial | Se asignan el rol Usuario y la empresa de la sesión |
| Buscar un registro inexistente | Se muestra el estado sin coincidencias |
| Recargar después de guardar | Los datos permanecen si localStorage está disponible |
| Consultar desde una pantalla pequeña | La navegación, formularios y tablas siguen siendo utilizables |

También deben revisarse los mensajes de error, los botones de cancelar, los enlaces de retorno y la consola del navegador.

## 11. Trabajo pendiente

### Aplicación e interfaces

- Revisar los textos de perfil y recuperación.
- Completar la revisión de navegación y adaptación a pantallas pequeñas.
- Actualizar la documentación conforme se incorporen cambios.

### Persistencia e integración

- Diseñar la base de datos.
- Crear e integrar el esquema en MySQL.
- Guardar y recuperar registros mediante PHP y MySQL.
- Incorporar validaciones del lado del servidor para las operaciones de datos.
- Aplicar la separación de datos por empresa en el servidor.
- Integrar la gestión de usuarios y administradores con las cuentas de acceso.
- Vincular la activación y desactivación de cuentas con la autenticación.
- Implementar el cambio y la recuperación reales de contraseña.
- Guardar la configuración de tablas y campos en MySQL.
- Validar y aplicar en el servidor los permisos sobre los datos consultados.

### Procesamiento de consultas

- Integrar el procesamiento de lenguaje natural.
- Generar y validar SQL.
- Ejecutar consultas de lectura autorizadas.
- Aplicar restricciones sobre las fuentes, tablas y campos.
- Obtener resultados y reportes a partir de datos reales.

La planificación de estos trabajos debe ajustarse al alcance de cada hito del curso.

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

- Credenciales reales.
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

La publicación no incorpora automáticamente la base de datos ni convierte los catálogos locales en datos compartidos.

## 14. Equipo

- Universidad: Universidad Nacional del Santa.
- Escuela profesional: Ingeniería de Sistemas e Informática.
- Curso: Aplicaciones Distribuidas I.
- Grupo: 04.
- Proyecto: QueryBI AI.