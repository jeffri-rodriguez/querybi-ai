const formularioConsulta = document.getElementById('form-consulta');
const selectorEjemplo = document.getElementById('ejemplo');
const pregunta = document.getElementById('pregunta');
const botonConsulta = document.getElementById('boton-consultar');
const seccionResultados = document.getElementById('resultados');
let resultadoActual = null;
let consultaActual = null;

function elegirEjemplo(id) {
    const ejemplo = datosDemo.ejemplos[id];
    selectorEjemplo.value = id;
    pregunta.value = ejemplo.pregunta;
    resultadoActual = null;
    seccionResultados.hidden = true;
    document.getElementById('estado-consulta').hidden = true;
}

function cambiarVista(id, enfocar = false) {
    ['tabla', 'grafico', 'sql'].forEach(function (nombre) {
        const activo = nombre === id;
        document.getElementById(nombre).hidden = !activo;
        const boton = document.getElementById('tab-' + nombre);
        boton.setAttribute('aria-selected', String(activo));
        boton.tabIndex = activo ? 0 : -1;
        if (activo && enfocar) boton.focus();
    });
}

function mostrarResultado(ejemplo) {
    resultadoActual = ejemplo;
    document.getElementById('mensaje-reporte').hidden = true;
    if (ejemplo.filas.length === 0) {
        seccionResultados.hidden = true;
        QBI.mensaje('estado-consulta', 'No hay registros para 2024 en esta demostración. Prueba otro ejemplo.');
        return;
    }
    seccionResultados.hidden = false;
    document.getElementById('titulo-resultado').textContent = ejemplo.titulo;
    const total = ejemplo.filas.reduce(function (suma, fila) { return suma + fila[1]; }, 0);
    document.getElementById('total-resultado').textContent = ejemplo.unidad === 'soles' ? QBI.moneda(total) : QBI.numero(total) + ' unidades';
    QBI.tabla(document.getElementById('tabla'), ejemplo.columnas, ejemplo.filas, ejemplo.titulo);
    dibujarGrafico(document.getElementById('grafico'), ejemplo.filas, ejemplo.unidad);
    document.getElementById('codigo-sql').textContent = ejemplo.sql;
    cambiarVista('tabla');
    QBI.mensaje('estado-consulta', 'Resultado de ejemplo listo. No se utilizó IA ni se ejecutó SQL.');
}

selectorEjemplo.addEventListener('change', function () { elegirEjemplo(selectorEjemplo.value); });
pregunta.addEventListener('input', function () {
    resultadoActual = null;
    seccionResultados.hidden = true;
    document.getElementById('estado-consulta').hidden = true;
});
formularioConsulta.addEventListener('submit', function (evento) {
    evento.preventDefault();
    if (botonConsulta.disabled) return;
    seccionResultados.hidden = true;
    resultadoActual = null;
    const fuente = QBI.fuentes().find(function (item) { return item.id === 'tienda'; });
    if (!fuente || !fuente.habilitada) {
        QBI.mensaje('estado-consulta', 'Tienda Demo está deshabilitada en el catálogo local. Actívala en Fuentes de datos.', true);
        return;
    }
    const ejemplo = Validaciones.reconocerEjemplo(pregunta.value);
    if (!ejemplo) {
        QBI.mensaje('estado-consulta', 'Esta demostración solo reconoce las preguntas del selector. Elige un ejemplo; la interpretación libre llegará con el backend y la IA.', true);
        return;
    }
    botonConsulta.disabled = true;
    pregunta.disabled = true;
    selectorEjemplo.disabled = true;
    QBI.mensaje('estado-consulta', 'Preparando el ejemplo local…');
    // Pausa breve para representar el estado de carga, sin peticiones de red.
    setTimeout(function () {
        const registro = { id: QBI.crearId(), ejemploId: ejemplo.id, pregunta: ejemplo.pregunta, fecha: new Date().toISOString(), estado: ejemplo.filas.length ? 'Completada' : 'Sin resultados' };
        const historial = QBI.historial();
        historial.unshift(registro);
        QBI.guardar('historial', historial);
        consultaActual = registro.id;
        mostrarResultado(ejemplo);
        botonConsulta.disabled = false;
        pregunta.disabled = false;
        selectorEjemplo.disabled = false;
    }, 450);
});

const tabs = ['tabla', 'grafico', 'sql'];
tabs.forEach(function (nombre, indice) {
    const boton = document.getElementById('tab-' + nombre);
    boton.addEventListener('click', function () { cambiarVista(nombre); });
    boton.addEventListener('keydown', function (evento) {
        let destino = indice;
        if (evento.key === 'ArrowRight') destino = (indice + 1) % tabs.length;
        else if (evento.key === 'ArrowLeft') destino = (indice + tabs.length - 1) % tabs.length;
        else if (evento.key === 'Home') destino = 0;
        else if (evento.key === 'End') destino = tabs.length - 1;
        else return;
        evento.preventDefault();
        cambiarVista(tabs[destino], true);
    });
});

document.getElementById('guardar-reporte').addEventListener('click', function () {
    if (!resultadoActual || !resultadoActual.filas.length) return;
    const reportes = QBI.reportes();
    if (reportes.some(function (reporte) { return reporte.consultaId === consultaActual; })) {
        QBI.mensaje('mensaje-reporte', 'Esta consulta ya está guardada en Reportes.');
        return;
    }
    reportes.unshift({ id: QBI.crearId(), consultaId: consultaActual, fecha: new Date().toISOString(), datos: JSON.parse(JSON.stringify(resultadoActual)) });
    const guardado = QBI.guardar('reportes', reportes);
    QBI.mensaje('mensaje-reporte', guardado ? 'Reporte guardado en este navegador. Puedes abrirlo desde Reportes.' : 'No se pudo guardar el reporte entre páginas.');
});

document.getElementById('copiar-sql').addEventListener('click', async function () {
    try {
        await navigator.clipboard.writeText(resultadoActual.sql);
        QBI.mensaje('mensaje-reporte', 'SQL de ejemplo copiado.');
    } catch (error) {
        const rango = document.createRange();
        rango.selectNodeContents(document.getElementById('codigo-sql'));
        const seleccion = window.getSelection();
        seleccion.removeAllRanges();
        seleccion.addRange(rango);
        QBI.mensaje('mensaje-reporte', 'El navegador no permite copiar automáticamente. El texto está seleccionado: usa Ctrl+C o Cmd+C.');
    }
});

const parametros = new URLSearchParams(window.location.search);
const registro = QBI.historial().find(function (item) { return item.id === parametros.get('consulta'); });
if (registro && datosDemo.ejemplos[registro.ejemploId]) {
    elegirEjemplo(registro.ejemploId);
    consultaActual = registro.id;
    mostrarResultado(datosDemo.ejemplos[registro.ejemploId]);
} else {
    const id = parametros.get('ejemplo');
    elegirEjemplo(Object.prototype.hasOwnProperty.call(datosDemo.ejemplos, id) ? id : 'mensual');
    if (parametros.has('consulta')) {
        QBI.mensaje('estado-consulta', 'La consulta no existe en este navegador. Puedes ejecutar un nuevo ejemplo.', true);
    }
}