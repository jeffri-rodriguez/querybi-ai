// Catálogo local de demostración.
// No realiza conexiones ni ejecuta SQL.

function leerFuentesLocales() {
    const fuentes = QBI.fuentes();

    if (!Array.isArray(fuentes)) return [];

    return fuentes.filter(function (fuente) {
        return fuente
            && typeof fuente.id === 'string'
            && typeof fuente.nombre === 'string';
    }).map(function (fuente) {
        return { ...fuente };
    });
}

function guardarFuentesLocales(fuentes) {
    const anteriores = leerFuentesLocales();

    if (QBI.guardar('fuentes', fuentes)) return true;

    // Evita mostrar como guardado un cambio rechazado.
    QBI.temporal.fuentes = anteriores;

    return false;
}

function mostrarFuentes() {
    const fuentes = leerFuentesLocales();

    const texto = Validaciones.normalizar(
        document.getElementById('buscar-fuente').value
    );

    const estado =
        document.getElementById('filtrar-estado').value;

    const filtradas = fuentes.filter(function (fuente) {
        const contenido = [
            fuente.nombre,
            fuente.base,
            fuente.servidor
        ].join(' ');

        const coincideTexto =
            Validaciones.normalizar(contenido).includes(texto);

        const coincideEstado = estado === 'todos'
            || (estado === 'habilitadas' && fuente.habilitada)
            || (estado === 'deshabilitadas' && !fuente.habilitada);

        return coincideTexto && coincideEstado;
    });

    document.getElementById('cantidad-fuentes').textContent =
        filtradas.length + ' de ' + fuentes.length + ' fuentes';

    const contenedor =
        document.getElementById('lista-fuentes');

    if (!filtradas.length) {
        contenedor.textContent = fuentes.length
            ? 'No hay fuentes que coincidan. Revisa la búsqueda o el estado.'
            : 'Todavía no hay fuentes. Puedes registrar la primera.';

        return;
    }

    const filas = filtradas.map(function (fuente) {
        const estadoLocal = document.createElement('span');

        estadoLocal.className = 'etiqueta '
            + (fuente.habilitada
                ? 'estado-activo'
                : 'estado-inactivo');

        estadoLocal.textContent = fuente.habilitada
            ? 'Habilitada'
            : 'Deshabilitada';

        const acciones = document.createElement('div');
        acciones.className = 'acciones-tabla';

        const id = encodeURIComponent(fuente.id);

        acciones.append(
            QBI.enlace(
                'Ver detalle',
                'detalle-fuente.php?id=' + id
            ),
            QBI.enlace(
                'Editar',
                'configurar-fuente.php?id=' + id
            )
        );

        return [
            fuente.nombre,
            fuente.motor,
            fuente.base,
            estadoLocal,
            acciones
        ];
    });

    QBI.tabla(
        contenedor,
        ['Nombre', 'Motor', 'Base de datos', 'Estado local', 'Acciones'],
        filas,
        'Fuentes del catálogo local'
    );
}

function prepararFormularioFuente() {
    const formulario =
        document.getElementById('form-fuente');

    const parametros =
        new URLSearchParams(window.location.search);

    const original = leerFuentesLocales().find(function (fuente) {
        return fuente.id === parametros.get('id');
    });

    if (parametros.has('id') && !original) {
        document.getElementById('titulo-fuente').textContent =
            'Fuente no disponible';

        formulario.hidden = true;

        QBI.mensaje(
            'mensaje-fuente',
            'No se encontró la fuente solicitada en este navegador.',
            true
        );

        return;
    }

    if (original) {
        document.getElementById('titulo-fuente').textContent =
            'Editar fuente';

        document.getElementById('nombre').value =
            original.nombre;

        document.getElementById('servidor').value =
            original.servidor;

        document.getElementById('puerto').value =
            original.puerto;

        document.getElementById('base-datos').value =
            original.base;

        document.getElementById('usuario').value =
            original.usuario;

        document.getElementById('descripcion').value =
            original.descripcion || '';

        document.getElementById('habilitada').checked =
            original.habilitada;
    }

    function revisarDatos() {
        const datos = {
            id: original ? original.id : '',
            nombre: document.getElementById('nombre').value.trim(),
            motor: 'MySQL',
            servidor: document.getElementById('servidor').value.trim(),
            puerto: Number(document.getElementById('puerto').value),
            base: document.getElementById('base-datos').value.trim(),
            usuario: document.getElementById('usuario').value.trim(),
            descripcion: document.getElementById('descripcion').value.trim(),
            habilitada: document.getElementById('habilitada').checked
        };

        let error = '';
        let campo = 'nombre';

        if (datos.nombre.length < 2 || datos.nombre.length > 80) {
            error = 'El nombre debe tener entre 2 y 80 caracteres.';
        } else if (leerFuentesLocales().some(function (fuente) {
            return fuente.id !== datos.id
                && Validaciones.normalizar(fuente.nombre)
                    === Validaciones.normalizar(datos.nombre);
        })) {
            error = 'Ya existe una fuente con ese nombre.';
        } else if (!datos.servidor || /\s/.test(datos.servidor)) {
            campo = 'servidor';
            error = 'Escribe un servidor sin espacios.';
        } else if (
            !Number.isInteger(datos.puerto)
            || datos.puerto < 1
            || datos.puerto > 65535
        ) {
            campo = 'puerto';
            error = 'El puerto debe ser un número entero entre 1 y 65535.';
        } else if (!datos.base) {
            campo = 'base-datos';
            error = 'Escribe el nombre de la base de datos de ejemplo.';
        } else if (!datos.usuario) {
            campo = 'usuario';
            error = 'Escribe el usuario de conexión de ejemplo.';
        }

        if (error) {
            QBI.mensaje('mensaje-fuente', error, true);
            document.getElementById(campo).focus();

            return null;
        }

        if (!formulario.reportValidity()) return null;

        return datos;
    }

    document.getElementById('revisar-fuente')
        .addEventListener('click', function () {
            if (revisarDatos()) {
                QBI.mensaje(
                    'mensaje-fuente',
                    'Los datos del formulario son válidos. No se ha probado ninguna conexión.'
                );
            }
        });

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        document.getElementById('clave').value = '';

        const datos = revisarDatos();

        if (!datos) return;

        const fuentes = leerFuentesLocales();

        if (original) {
            const indice = fuentes.findIndex(function (fuente) {
                return fuente.id === original.id;
            });

            if (indice < 0) {
                QBI.mensaje(
                    'mensaje-fuente',
                    'La fuente ya no está disponible. Vuelve al listado.',
                    true
                );

                return;
            }

            fuentes[indice] = {
                ...fuentes[indice],
                ...datos
            };
        } else {
            datos.id = QBI.crearId();
            fuentes.push(datos);
        }

        if (guardarFuentesLocales(fuentes)) {
            window.location.href = 'detalle-fuente.php?id='
                + encodeURIComponent(datos.id)
                + '&guardada=1';
        } else {
            QBI.mensaje(
                'mensaje-fuente',
                'No se pudo guardar la fuente. Revisa el almacenamiento del navegador.',
                true
            );
        }
    });
}

function mostrarDetalleFuente() {
    const parametros =
        new URLSearchParams(window.location.search);

    const fuente = leerFuentesLocales().find(function (item) {
        return item.id === parametros.get('id');
    });

    if (!fuente) {
        QBI.mensaje(
            'mensaje-detalle',
            'No se encontró la fuente solicitada en este navegador.',
            true
        );

        return;
    }

    document.getElementById('detalle-fuente').hidden = false;

    document.getElementById('nombre-fuente').textContent =
        fuente.nombre;

    const campos = {
        motor: fuente.motor,
        servidor: fuente.servidor,
        puerto: fuente.puerto,
        base: fuente.base,
        usuario: fuente.usuario,
        descripcion: fuente.descripcion || 'Sin descripción.'
    };

    Object.entries(campos).forEach(function ([campo, valor]) {
        document.getElementById('detalle-' + campo).textContent =
            valor;
    });

    const estado =
        document.getElementById('detalle-estado');

    estado.textContent = fuente.habilitada
        ? 'Habilitada'
        : 'Deshabilitada';

    estado.className = 'etiqueta '
        + (fuente.habilitada
            ? 'estado-activo'
            : 'estado-inactivo');

    document.getElementById('editar-fuente').href =
        'configurar-fuente.php?id='
        + encodeURIComponent(fuente.id);

    document.getElementById('seccion-esquema').hidden = false;

    document.getElementById('descripcion-esquema').textContent =
        fuente.id === 'tienda'
            ? 'Esta fuente tiene un esquema de ejemplo preparado para recorrer la interfaz.'
            : 'Todavía no hay un esquema de demostración registrado para esta fuente.';

    document.getElementById('ver-esquema').hidden =
        fuente.id !== 'tienda';

    if (parametros.get('guardada') === '1') {
        QBI.mensaje(
            'mensaje-detalle',
            'Fuente guardada en este navegador.'
        );
    }
}

if (document.getElementById('lista-fuentes')) {
    document.getElementById('buscar-fuente')
        .addEventListener('input', mostrarFuentes);

    document.getElementById('filtrar-estado')
        .addEventListener('change', mostrarFuentes);

    mostrarFuentes();
}

if (document.getElementById('form-fuente')) {
    prepararFormularioFuente();
}

if (document.getElementById('detalle-fuente')) {
    mostrarDetalleFuente();
}