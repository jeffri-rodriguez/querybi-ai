function mostrarHistorial() {
    const busqueda = Validaciones.normalizar(document.getElementById('buscar').value);
    const estado = document.getElementById('filtro-estado').value;
    const consultas = QBI.historial().filter(function (consulta) {
        return Validaciones.normalizar(consulta.pregunta).includes(busqueda) && (estado === 'Todos' || consulta.estado === estado);
    });
    document.getElementById('cantidad-historial').textContent = consultas.length + ' consultas encontradas';
    const contenedor = document.getElementById('lista-historial');
    if (consultas.length === 0) {
        contenedor.textContent = 'No hay consultas que coincidan. Ejecuta un ejemplo o cambia los filtros.';
        return;
    }
    const filas = consultas.map(function (consulta) {
        return [consulta.pregunta, QBI.fecha(consulta.fecha), consulta.estado, QBI.enlace('Ver detalle', 'consultas.php?consulta=' + encodeURIComponent(consulta.id))];
    });
    QBI.tabla(contenedor, ['Pregunta', 'Fecha', 'Estado', 'Detalle'], filas, 'Historial local');
}
document.getElementById('buscar').addEventListener('input', mostrarHistorial);
document.getElementById('filtro-estado').addEventListener('change', mostrarHistorial);
mostrarHistorial();