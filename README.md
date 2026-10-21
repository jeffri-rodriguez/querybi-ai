# QueryBI AI

Proyecto del Grupo 04 para el curso de Aplicaciones Distribuidas I.

QueryBI AI es una aplicación web orientada a consultar y analizar datos empresariales mediante preguntas en lenguaje natural. Su desarrollo se realiza por etapas.

## Estructura del proyecto
```text
querybi-ai/
│
├── index.html                       # Página pública de inicio
│
├── paginas/
│   ├── nosotros.html                # Quiénes somos, misión y visión
│   ├── como-funciona.html            # Explicación del funcionamiento
│   └── ayuda.html                    # Preguntas frecuentes y orientación
│
├── acceso/
│   ├── login.php                    # Interfaz de inicio de sesión
│   ├── salir.php                    
│   └── recuperar-contrasena.html     # Interfaz para recuperar acceso
│
├── aplicacion/
│   ├── panel.php                    # Inicio del espacio de trabajo
│   ├── consultas.php                # Preguntas, resultados, gráficos y SQL
│   ├── historial.php                # Consultas anteriores de ejemplo
│   ├── reportes.php                 # Listado y vista previa de reportes
│   └── perfil.php                   # Datos del usuario y contraseña
│
├── administracion/
│   ├── fuentes.php                  # Listado de fuentes de datos
│   ├── configurar-fuente.php        # Formulario de configuración de fuente
│   ├── esquema.php                  # Tablas, campos, relaciones y permisos
│   └── usuarios.php                 # Listado, creación y edición de usuarios
│
├── assets/
│   │
│   ├── css/
│   │   ├── estilos.css               # Base visual compartida
│   │   ├── publico.css               # Diseño de las páginas públicas
│   │   ├── acceso.css                # Diseño de login y recuperación
│   │   ├── aplicacion.css            # Menú lateral y estructura interna
│   │   ├── consultas.css             # Área de preguntas y resultados
│   │   └── administracion.css        # Diseño de administración
│   │
│   ├── js/
│   │   ├── principal.js              # Comportamientos generales y navegación
│   │   ├── acceso.js                 # Validaciones y acceso simulado
│   │   ├── panel.js                  # Presentación del resumen inicial
│   │   ├── consultas.js              # Consultas simuladas y sus estados
│   │   ├── graficos.js               # Representación de datos de ejemplo
│   │   ├── historial.js              # Búsqueda y detalle de consultas
│   │   ├── reportes.js               # Vista previa y acciones de reportes
│   │   ├── perfil.js                 # Validaciones del formulario de perfil
│   │   ├── fuentes.js                # Listado y formularios de fuentes
│   │   ├── esquema.js                # Selección visual de tablas y campos
│   │   ├── usuarios.js               # Formularios y roles simulados
│   │   └── validaciones.js           # Validaciones reutilizables
│   │
│   ├── datos/
│   │   └── datos-demo.js             # Datos ficticios compartidos
│   │
│   └── imagenes/
│       ├── logo.svg                  # Logo de QueryBI AI
│       └── favicon.svg               # Icono de la pestaña del navegador
│
├── plataforma/
│   └── panel.php
|
├── servidor/
│   └── sesion.php
│   └── cuentas-demo.php
│   └── navegacion.php.
|
├── README.md                         # Documentación del proyecto
└── .gitignore                        # Reglas de exclusión para Git
```

### Organización de las interfaces

- El proyecto combina páginas públicas HTML con pantallas protegidas PHP.
- La cabecera y la navegación interna se comparten desde `servidor/navegacion.php`.
- Misión y visión se incluyen en `paginas/nosotros.html`.
- Los resultados, gráficos y SQL se muestran en `aplicacion/consultas.php`.
- La creación y edición de usuarios se incluyen en `administracion/usuarios.php`.
- Los estados de carga, error y ausencia de resultados se presentan dentro de la pantalla correspondiente.
- Las reglas de adaptación a dispositivos móviles se incluyen en los archivos CSS de cada área.
- Los datos ficticios se centralizan en `assets/datos/datos-demo.js`.

## Arquitectura y estado del desarrollo

La aplicación utiliza arquitectura cliente-servidor.

- El navegador presenta HTML y CSS y ejecuta JavaScript.
- PHP comprueba las credenciales, mantiene la sesión y autoriza el acceso a las páginas.
- MySQL todavía no está integrado.

### Acceso y roles

Existen tres roles:

| Rol | Acceso actual |
|---|---|
| Superadministrador | Panel general de la plataforma y perfil. |
| Administrador | Panel empresarial, consultas, historial, reportes, fuentes, esquema, usuarios de ejemplo y perfil. |
| Usuario | Panel empresarial, consultas, historial, reportes y perfil. |

Cada página protegida comprueba los permisos en PHP antes de mostrar su contenido.

Una persona sin sesión es dirigida al ingreso. Una cuenta sin el rol autorizado recibe una respuesta HTTP 403.

El servidor determina el rol. Modificar localStorage o el JavaScript del navegador no concede permisos adicionales.

### Cuentas de prueba

Las cuentas están predefinidas en `servidor/cuentas-demo.php`. Sus contraseñas se verifican mediante hashes.

| Rol | Correo | Contraseña de prueba |
|---|---|---|
| Superadministrador | superadmin@querybi.test | SuperDemo2026! |
| Administrador | admin@tienda.test | AdminDemo2026! |
| Usuario | usuario@tienda.test | UsuarioDemo2026! |

Estas cuentas son exclusivamente de demostración.

El formulario local de usuarios todavía no crea ni modifica las cuentas de acceso.

### Funciones y datos de demostración

Las consultas siguen utilizando resultados preparados en JavaScript. No se genera SQL mediante IA ni se ejecuta contra una base de datos.

Las fuentes, los usuarios de ejemplo, los reportes y el historial utilizan almacenamiento local del navegador.

El espacio local se diferencia por cuenta. Esta separación no sustituye la autorización ni el filtrado de datos en el servidor.

El perfil permite personalizar un nombre de presentación local. El correo y el rol de la cuenta proceden de la sesión.

### Desarrollo pendiente

- Gestión de empresas y administradores desde el panel de plataforma.
- Integración de los módulos con datos y operaciones del servidor.
- Aplicación del ámbito de empresa al acceder a sus recursos.
- Diseño e implementación de MySQL.
- Persistencia real de registro y consulta.
- Generación de SQL mediante IA, validación y ejecución controlada.

La integración inicial de MySQL y un flujo persistente siguen siendo necesarios para completar la entrega grupal del primer avance.

## Ejecución de la versión actual

Requisito: PHP 8 o superior.

Desde la carpeta que contiene `index.html`, ejecuta:

```bash
php -S 127.0.0.1:8000 -t .
```

Abre:

```text
http://127.0.0.1:8000/
```

Para detener el servidor, utiliza `Ctrl+C`.

Las páginas PHP deben abrirse mediante este servidor o uno compatible con PHP. Live Server y el servidor HTTP de Python no ejecutan PHP.

### Comprobaciones del acceso

1. Sin sesión, abre una página interna: debe dirigir al ingreso.
2. Introduce una contraseña incorrecta: debe mostrar un error.
3. Ingresa como usuario: debe mostrar las funciones de consulta.
4. Intenta abrir administración mediante su dirección: debe responder con acceso denegado.
5. Ingresa como administrador: debe permitir las páginas de administración.
6. Intenta abrir el panel de plataforma: debe responder con acceso denegado.
7. Ingresa como superadministrador: debe abrir el panel de plataforma.
8. Cierra sesión: las páginas protegidas deben volver a solicitar ingreso.

## Flujo de trabajo con Git y GitHub

**Idea central:** `main` es la versión oficial del proyecto. **Nadie trabaja directamente en `main`.** Cada tarea se hace en una rama propia y se une a `main` mediante un Pull Request (PR).

### 1. Primera vez (una sola vez por persona)

1. Aceptar la invitación al repositorio (llega por correo o en las notificaciones de GitHub).
2. Clonar el repositorio:
```bash
   git clone ENLACE_DEL_REPO
   cd nombre-del-repo
```
3. Configurar nombre y correo (el mismo correo de tu cuenta de GitHub):
```bash
   git config user.name "Tu Nombre"
   git config user.email "tu-correo@ejemplo.com"
```
4. Ejecutar el proyecto siguiendo el apartado «Ejecución de la versión actual» y comprobar que la página de inicio carga correctamente.

### 2. Ciclo de cada tarea

**Paso 1: Actualizar `main`**
```bash
git switch main
git pull
```

**Paso 2: Crear tu rama** (un nombre corto que diga qué haces)
```bash
git switch -c feature/nombre-de-la-tarea
```

**Paso 3: Trabajar y guardar avances** (varios commits pequeños)
```bash
git status
git add .
git commit -m "Mensaje claro de lo que hiciste"
```
Siempre revisa `git status` antes de `git add .` para no subir archivos por error.

**Paso 4: Subir tu rama a GitHub**
```bash
git push -u origin feature/nombre-de-la-tarea
```
El `-u` solo se pone la primera vez en cada rama. Después basta con `git push`.

**Paso 5: Abrir el Pull Request**
1. Entrar al repo en GitHub y pulsar **Compare & pull request**.
2. Verificar que diga `base: main ← compare: tu-rama`.
3. Poner un título claro y una descripción corta (qué hiciste y cómo probarlo).
4. Pulsar **Create pull request** y avisar al grupo por el chat.

**Paso 6: Revisión**
Otro compañero revisa la pestaña **Files changed** y verifica que:
- No se subieron archivos innecesarios (comprimidos, archivos pesados, carpetas de tu editor).
- No hay código a medio hacer.
- Se entiende lo que hace.

Si hay que corregir algo, el autor hace los cambios en su misma rama (`add`, `commit`, `push`) y el PR se actualiza solo.

**Paso 7: Merge**
Cuando está aprobado, se pulsa **Merge pull request** y luego **Confirm merge**. Después se pulsa **Delete branch** en GitHub.

**Paso 8: Limpiar y empezar de nuevo**
```bash
git switch main
git pull
git branch -d feature/nombre-de-la-tarea
```
Y se repite desde el Paso 2 con la siguiente tarea. Nunca se reutiliza una rama vieja.

### 3. Si `main` avanzó mientras trabajabas

Trae lo nuevo a tu rama:
```bash
git switch main
git pull
git switch tu-rama
git merge main
```
Haz commit de tus cambios **antes** de este proceso. Después prueba que todo siga funcionando y haz `git push`.

### 4. Si hay conflicto

Pasa cuando dos personas cambiaron las mismas líneas. Git marca el archivo así:

    <<<<<<< HEAD
    lo que tú escribiste
    =======
    lo que escribió el otro
    >>>>>>> main

Para resolverlo:
1. Abre el archivo y decide qué queda (tu versión, la otra o una mezcla).
2. Borra las marcas `<<<<<<<`, `=======` y `>>>>>>>`.
3. Ejecuta:
```bash
git add .
git commit
git push
```

VS Code muestra botones sobre el conflicto (*Accept Current / Incoming / Both*). Si no estás seguro de qué conservar, **pregúntale a quien escribió la otra parte**. Para cancelar el merge y volver al estado anterior: `git merge --abort`.

### 5. Reglas del grupo

1. Nadie hace commit ni push directo a `main`.
2. Una rama por tarea, con nombre claro.
3. Siempre `git switch main` y `git pull` antes de crear una rama.
4. Siempre `git status` antes de `git add .`.
5. No subir archivos innecesarios ni pesados (`.zip`, videos, archivos temporales).
6. Repartir las tareas para no editar el mismo archivo a la vez.
7. Avisar en el chat antes de tocar archivos compartidos (CSS principal, etc.).
8. Revisar y unir los PR rápido, para que las ramas no se alejen de `main`.
9. Después de cada merge, hacer `git pull` en `main` y probar que la página sigue funcionando.

### Comandos útiles

| Comando | Para qué sirve |
|---------|----------------|
| `git status` | Ver en qué rama estás y qué cambió |
| `git branch` | Listar tus ramas locales |
| `git log --oneline` | Ver el historial resumido |
| `git diff` | Ver qué líneas cambiaste antes de hacer `add` |
| `git fetch` | Descargar novedades sin modificar tus archivos |