const consultas = QBI.historial();
const reportes = QBI.reportes();
document.getElementById('saludo').textContent = 'Hola, ' + QBI.perfil().nombre;
document.getElementById('cantidad-consultas').textContent = consultas.length;
document.getElementById('cantidad-reportes').textContent = reportes.length;
const actividad = document.getElementById('actividad');
if (consultas.length === 0) {
    actividad.textContent = 'Todavía no has realizado consultas. Abre uno de los ejemplos para comenzar.';
} else {
    const filas = consultas.slice(0, 5).map(function (consulta) {
        return [consulta.pregunta, QBI.fecha(consulta.fecha), QBI.enlace('Abrir', 'consultas.php?consulta=' + encodeURIComponent(consulta.id))];
    });
    QBI.tabla(actividad, ['Pregunta', 'Fecha', 'Detalle'], filas, 'Actividad reciente');
}