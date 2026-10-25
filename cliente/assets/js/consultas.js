const formulario =
    document.getElementById('form-consulta');

const selectorFuente =
    document.getElementById('fuente');

const botonGuardar =
    document.getElementById('guardar-pregunta');

function fuentesHabilitadas() {
    const fuentes = QBI.fuentes();

    return Array.isArray(fuentes)
        ? fuentes.filter(function (fuente) {
            return fuente
                && typeof fuente.id === 'string'
                && typeof fuente.nombre === 'string'
                && fuente.habilitada === true;
        })
        : [];
}

const fuentes = fuentesHabilitadas();

const aviso = document.createElement('option');
aviso.value = '';
aviso.textContent = fuentes.length
    ? 'Selecciona una fuente'
    : 'No hay fuentes habilitadas';

aviso.disabled = true;
aviso.selected = true;

selectorFuente.append(aviso);

fuentes.forEach(function (fuente) {
    const opcion = document.createElement('option');

    opcion.value = fuente.id;
    opcion.textContent = fuente.nombre;

    selectorFuente.append(opcion);
});

botonGuardar.disabled = fuentes.length === 0;

if (!fuentes.length) {
    QBI.mensaje(
        'estado-consulta',
        'No hay fuentes habilitadas para tu empresa.',
        true
    );
}

formulario.addEventListener('submit', function (evento) {
    evento.preventDefault();

    if (botonGuardar.disabled) return;

    const pregunta =
        document.getElementById('pregunta').value.trim();

    const fuente = fuentesHabilitadas().find(function (item) {
        return item.id === selectorFuente.value;
    });

    if (!fuente) {
        QBI.mensaje(
            'estado-consulta',
            'Selecciona una fuente que continúe habilitada.',
            true
        );

        return;
    }

    if (pregunta.length < 5 || pregunta.length > 1000) {
        QBI.mensaje(
            'estado-consulta',
            'Escribe una pregunta de entre 5 y 1000 caracteres.',
            true
        );

        return;
    }

    const registro = {
        id: QBI.crearId(),
        tipo: 'pregunta',
        pregunta: pregunta,
        fuenteId: fuente.id,
        fuenteNombre: fuente.nombre,
        fecha: new Date().toISOString(),
        estado: 'Pendiente'
    };

    const historial = QBI.historial();
    historial.unshift(registro);

    botonGuardar.disabled = true;

    if (QBI.guardarLista('historial', historial)) {
        window.location.href = 'detalle-consulta.php?consulta='
            + encodeURIComponent(registro.id)
            + '&guardada=1';
    } else {
        botonGuardar.disabled = false;

        QBI.mensaje(
            'estado-consulta',
            'No se pudo guardar la pregunta en este navegador.',
            true
        );
    }
});