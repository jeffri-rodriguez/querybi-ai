const consultas = QBI.historial();

document.getElementById('saludo').textContent =
    'Hola, ' + QBI.perfil().nombre;

document.getElementById('cantidad-consultas').textContent =
    consultas.length;

document.getElementById('cantidad-pendientes').textContent =
    consultas.filter(function (consulta) {
        return consulta.tipo === 'pregunta'
            && consulta.estado === 'Pendiente';
    }).length;

document.getElementById('cantidad-reportes').textContent =
    QBI.reportes().length;

const actividad = document.getElementById('actividad');

if (!consultas.length) {
    actividad.textContent =
        'Todavía no hay actividad. Registra una pregunta o explora un ejemplo.';
} else {
    const filas = consultas.slice(0, 5).map(function (consulta) {
        return [
            consulta.pregunta,
            consulta.fuenteNombre,
            consulta.estado,
            QBI.fecha(consulta.fecha),
            QBI.enlace(
                'Abrir',
                'detalle-consulta.php?consulta='
                    + encodeURIComponent(consulta.id)
            )
        ];
    });

    QBI.tabla(
        actividad,
        ['Pregunta', 'Fuente', 'Estado', 'Fecha', 'Detalle'],
        filas,
        'Actividad reciente'
    );
}