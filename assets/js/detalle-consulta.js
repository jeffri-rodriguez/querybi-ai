const parametros =
    new URLSearchParams(window.location.search);

const consulta = QBI.historial().find(function (item) {
    return item.id === parametros.get('consulta');
});

if (!consulta) {
    QBI.mensaje(
        'mensaje-detalle',
        'No se encontró el registro en el historial de esta cuenta y navegador.',
        true
    );
} else {
    document.getElementById('detalle-consulta').hidden = false;

    document.getElementById('pregunta-detalle').textContent =
        consulta.pregunta;

    document.getElementById('fuente-detalle').textContent =
        consulta.fuenteNombre;

    document.getElementById('fecha-detalle').textContent =
        QBI.fecha(consulta.fecha);

    document.getElementById('tipo-detalle').textContent =
        consulta.tipo === 'ejemplo'
            ? 'Ejemplo'
            : 'Pregunta propia';

    document.getElementById('estado-detalle').textContent =
        consulta.estado;

    const explicacion =
        document.getElementById('explicacion-detalle');

    if (consulta.tipo === 'ejemplo') {
        const disponible = Object.prototype.hasOwnProperty.call(
            datosDemo.ejemplos,
            consulta.ejemploId
        );

        explicacion.textContent = disponible
            ? 'Este registro corresponde a un ejemplo con resultados preparados, sin IA ni ejecución de SQL.'
            : 'El ejemplo asociado ya no está disponible.';

        if (disponible) {
            const enlace =
                document.getElementById('abrir-ejemplo');

            enlace.hidden = false;
            enlace.href = 'ejemplos.php?consulta='
                + encodeURIComponent(consulta.id);
        }
    } else {
        explicacion.textContent =
            'La pregunta está registrada y pendiente. Esta versión todavía no genera SQL ni obtiene resultados para preguntas propias.';
    }

    if (
        parametros.get('guardada') === '1'
        && consulta.tipo === 'pregunta'
    ) {
        QBI.mensaje(
            'mensaje-detalle',
            'Pregunta registrada como pendiente en este navegador.'
        );
    }
}