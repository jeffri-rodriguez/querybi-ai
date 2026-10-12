const casillas = Array.from(document.querySelectorAll('main input[type="checkbox"]'));
const seleccion = QBI.leer('esquema', casillas.map(function () { return true; }));
casillas.forEach(function (casilla, indice) {
    casilla.checked = seleccion[indice] !== false;
});
document.getElementById('guardar-esquema').addEventListener('click', function () {
    const valores = casillas.map(function (casilla) { return casilla.checked; });
    const guardado = QBI.guardar('esquema', valores);
    QBI.mensaje('mensaje-esquema', guardado ? 'Selección guardada como demostración. No restringe las consultas ni aplica permisos reales.' : 'Selección temporal; no se pudo conservar entre páginas.');
});