(function () {
    const elemento = id => document.getElementById(id);
    const copiar = valor => JSON.parse(JSON.stringify(valor));

    const datos = QBI.fuentes();

    const fuentes = Array.isArray(datos)
        ? datos.filter(fuente =>
            fuente
            && typeof fuente.id === 'string'
            && typeof fuente.nombre === 'string'
        )
        : [];

    const selector = elemento('fuente-esquema');

    fuentes.forEach(fuente => {
        const opcion = document.createElement('option');

        opcion.value = fuente.id;
        opcion.textContent = fuente.nombre
            + (fuente.habilitada ? '' : ' (deshabilitada)');

        selector.append(opcion);
    });

    if (!fuentes.length) {
        selector.disabled = true;
        elemento('abrir-esquema').disabled = true;

        QBI.mensaje(
            'estado-esquema',
            'No hay fuentes registradas. Crea una desde Fuentes de datos.'
        );

        return;
    }

    const parametros = new URLSearchParams(window.location.search);

    const id = parametros.has('fuente')
        ? parametros.get('fuente')
        : (
            fuentes.some(fuente => fuente.id === 'tienda')
                ? 'tienda'
                : fuentes[0].id
        );

    const fuente = fuentes.find(item => item.id === id);

    selector.value = fuente ? fuente.id : fuentes[0].id;

    if (!fuente) {
        QBI.mensaje(
            'estado-esquema',
            'La fuente solicitada no existe. Selecciona otra y pulsa Ver esquema.',
            true
        );

        return;
    }

    if (!Object.prototype.hasOwnProperty.call(esquemasDemo, fuente.id)) {
        QBI.mensaje(
            'estado-esquema',
            'No hay un esquema registrado para '
                + fuente.nombre + '.'
        );

        return;
    }

    const esquema = esquemasDemo[fuente.id];
    const clave = 'esquema-v2-' + fuente.id;

    const anterior = QBI.leer(clave, null);

    const legado = fuente.id === 'tienda'
        ? QBI.leer('esquema', null)
        : null;

    let guardado = {};

    // Recupera por nombre y conserva las casillas de la versión anterior.
    Object.entries(esquema.tablas).forEach(([tabla, campos], indice) => {
        const registro = anterior && anterior[tabla];
        const migrar = anterior === null && Array.isArray(legado);

        guardado[tabla] = {
            activa: registro
                ? registro.activa !== false
                : !(migrar && legado[indice] === false),
            campos: {}
        };

        campos.forEach(([nombre], posicion) => {
            guardado[tabla].campos[nombre] =
                registro && registro.campos
                    ? registro.campos[nombre] !== false
                    : !(
                        migrar
                        && tabla === 'ventas'
                        && legado[4 + posicion] === false
                    );
        });
    });

    let borrador = copiar(guardado);

    function hayCambios() {
        return JSON.stringify(borrador) !== JSON.stringify(guardado);
    }

    function actualizarResumen() {
        const tablas = Object.values(borrador).filter(tabla =>
            tabla.activa
        );

        const cantidad = tablas.reduce(
            (total, tabla) =>
                total + Object.values(tabla.campos).filter(Boolean).length,
            0
        );

        elemento('resumen-esquema').textContent =
            tablas.length + ' tablas seleccionadas y '
            + cantidad + ' campos seleccionados dentro de ellas.';

        elemento('cambios-esquema').textContent = hayCambios()
            ? 'Tienes cambios sin guardar.'
            : 'No hay cambios sin guardar.';

        elemento('guardar-esquema').disabled =
            !fuente.habilitada || !hayCambios();

        elemento('descartar-esquema').disabled = !hayCambios();
    }

    function renderizar() {
        const contenedor = elemento('tablas-esquema');
        contenedor.replaceChildren();

        Object.entries(esquema.tablas).forEach(([tabla, campos]) => {
            const tarjeta = document.createElement('section');
            tarjeta.className = 'tarjeta';

            const titulo = document.createElement('h3');
            titulo.textContent = tabla;

            const etiqueta = document.createElement('label');

            const casilla = document.createElement('input');
            casilla.type = 'checkbox';
            casilla.dataset.tabla = tabla;
            casilla.checked = borrador[tabla].activa;
            casilla.disabled = !fuente.habilitada;

            etiqueta.append(casilla, ' Seleccionar tabla ' + tabla);

            const listado = document.createElement('div');
            const casillasCampos = [];

            const filas = campos.map(([nombre, descripcion]) => {
                const campo = document.createElement('input');

                campo.type = 'checkbox';
                campo.dataset.campo = tabla + '.' + nombre;

                campo.setAttribute(
                    'aria-label',
                    'Seleccionar campo ' + tabla + '.' + nombre
                );

                campo.checked = borrador[tabla].campos[nombre];

                campo.disabled =
                    !fuente.habilitada || !borrador[tabla].activa;

                campo.addEventListener('change', function () {
                    borrador[tabla].campos[nombre] = campo.checked;
                    elemento('estado-esquema').hidden = true;
                    actualizarResumen();
                });

                casillasCampos.push(campo);

                return [campo, nombre, descripcion];
            });

            casilla.addEventListener('change', function () {
                borrador[tabla].activa = casilla.checked;

                casillasCampos.forEach(campo => {
                    campo.disabled =
                        !fuente.habilitada || !casilla.checked;
                });

                elemento('estado-esquema').hidden = true;
                actualizarResumen();
            });

            QBI.tabla(
                listado,
                ['Seleccionar', 'Campo', 'Descripción'],
                filas,
                'Campos de ' + tabla
            );

            tarjeta.append(titulo, etiqueta, listado);
            contenedor.append(tarjeta);
        });

        actualizarResumen();
    }

    elemento('guardar-esquema').addEventListener('click', function () {
        if (!fuente.habilitada || !hayCambios()) return;

        const actual = QBI.fuentes();

        const disponible = Array.isArray(actual)
            && actual.some(item =>
                item
                && item.id === fuente.id
                && item.habilitada
            );

        if (!disponible) {
            QBI.mensaje(
                'estado-esquema',
                'La fuente ya no está habilitada o disponible. Recarga la página.',
                true
            );

            return;
        }

        const previo = QBI.leer(clave, null);

        if (!QBI.guardar(clave, copiar(borrador))) {
            QBI.temporal[clave] = previo;

            QBI.mensaje(
                'estado-esquema',
                'No se pudo guardar. Tus cambios siguen en pantalla para volver a intentarlo.',
                true
            );

            return;
        }

        guardado = copiar(borrador);
        actualizarResumen();

        QBI.mensaje(
            'estado-esquema',
            'Selección guardada en este navegador. Los permisos sobre consultas aún no se aplican.'
        );
    });

    elemento('descartar-esquema').addEventListener('click', function () {
        borrador = copiar(guardado);
        renderizar();

        QBI.mensaje(
            'estado-esquema',
            'Se descartaron los cambios sin guardar.'
        );
    });

    window.addEventListener('beforeunload', function (evento) {
        if (hayCambios()) {
            evento.preventDefault();
            evento.returnValue = '';
        }
    });

    elemento('editor-esquema').hidden = false;
    elemento('nombre-fuente-esquema').textContent = fuente.nombre;

    elemento('estado-fuente-esquema').textContent = fuente.habilitada
        ? 'Fuente habilitada en el catálogo local.'
        : 'Fuente deshabilitada: puedes consultar el ejemplo. Habilítala en Fuentes para editar la selección.';

    QBI.tabla(
        elemento('relaciones-esquema'),
        ['Referencia', 'Campo relacionado'],
        esquema.relaciones,
        'Relaciones del ejemplo'
    );

    renderizar();
})();