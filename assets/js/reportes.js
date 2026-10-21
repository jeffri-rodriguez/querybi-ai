let reporteSeleccionado = null;
function verReporte(reporte) {
    reporteSeleccionado = reporte;
    document.getElementById('vista-previa').hidden = false;
    document.getElementById('titulo-reporte').textContent = reporte.datos.titulo;
    QBI.tabla(document.getElementById('tabla-reporte'), reporte.datos.columnas, reporte.datos.filas, 'Resultado guardado');
    dibujarGrafico(document.getElementById('grafico-reporte'), reporte.datos.filas, reporte.datos.unidad);
}
function mostrarReportes() {
    const reportes = QBI.reportes();
    const contenedor = document.getElementById('lista-reportes');
    if (!reportes.length) {
        contenedor.textContent = 'Todavía no tienes reportes. Abre Ejemplos y pulsa Guardar reporte.';
        document.getElementById('vista-previa').hidden = true;
        return;
    }
    const filas = reportes.map(function (reporte) {
        const acciones = document.createElement('div');
        acciones.className = 'acciones';
        acciones.append(QBI.boton('Ver', function () { verReporte(reporte); }));
        acciones.append(QBI.boton('Eliminar', function () {
            if (!window.confirm('¿Eliminar este reporte local?')) return;
            const restantes = QBI.reportes().filter(function (item) { return item.id !== reporte.id; });
            const guardado = QBI.guardar('reportes', restantes);
            mostrarReportes();
            QBI.mensaje('mensaje-reportes', guardado ? 'Reporte eliminado del navegador.' : 'Cambio temporal; no se pudo actualizar el almacenamiento.');
        }));
        return [reporte.datos.titulo, QBI.fecha(reporte.fecha), acciones];
    });
    QBI.tabla(contenedor, ['Reporte', 'Fecha', 'Acciones'], filas, 'Reportes locales');
    verReporte(reportes[0]);
}
document.getElementById('descargar-reporte').addEventListener('click', function () {
    if (reporteSeleccionado) QBI.descargarCSV(reporteSeleccionado.datos.columnas, reporteSeleccionado.datos.filas);
});
mostrarReportes();