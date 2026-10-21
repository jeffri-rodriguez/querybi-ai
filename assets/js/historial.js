function mostrarHistorial() {
    const historial = QBI.historial();

    const busqueda = Validaciones.normalizar(
        document.getElementById('buscar').value
    );

    const estado =
        document.getElementById('filtro-estado').value;

    const consultas = historial.filter(function (consulta) {
        return Validaciones.normalizar(consulta.pregunta)
            .includes(busqueda)
            && (
                estado === 'Todos'
                || consulta.estado === estado
            );
    });

    document.getElementById('cantidad-historial').textContent =
        consultas.length + ' de ' + historial.length + ' registros';

    const contenedor =
        document.getElementById('lista-historial');

    if (!consultas.length) {
        contenedor.textContent = historial.length
            ? 'No hay registros que coincidan con los filtros.'
            : 'Todavía no hay registros. Crea una pregunta o explora un ejemplo.';

        return;
    }

    const filas = consultas.map(function (consulta) {
        return [
            consulta.pregunta,
            consulta.fuenteNombre,
            consulta.tipo === 'ejemplo' ? 'Ejemplo' : 'Pregunta',
            consulta.estado,
            QBI.fecha(consulta.fecha),
            QBI.enlace(
                'Ver detalle',
                'detalle-consulta.php?consulta='
                    + encodeURIComponent(consulta.id)
            )
        ];
    });

    QBI.tabla(
        contenedor,
        ['Pregunta', 'Fuente', 'Tipo', 'Estado', 'Fecha', 'Detalle'],
        filas,
        'Historial local'
    );
}

const estadoInicial =
    new URLSearchParams(window.location.search).get('estado');

if (
    ['Pendiente', 'Ejemplo listo', 'Ejemplo sin resultados']
        .includes(estadoInicial)
) {
    document.getElementById('filtro-estado').value =
        estadoInicial;
}

document.getElementById('buscar')
    .addEventListener('input', mostrarHistorial);

document.getElementById('filtro-estado')
    .addEventListener('change', mostrarHistorial);

mostrarHistorial();