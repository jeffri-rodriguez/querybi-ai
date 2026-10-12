// Gráfico de barras sencillo. Se construye con elementos HTML y CSS.
function dibujarGrafico(contenedor, filas, unidad) {
    contenedor.replaceChildren();
    const descripcion = document.createElement('p');
    descripcion.className = 'texto-secundario';
    descripcion.textContent = unidad === 'soles' ? 'Importes en soles · Datos de ejemplo' : 'Cantidad de unidades · Datos de ejemplo';
    contenedor.append(descripcion);
    const mayor = Math.max(0, ...filas.map(function (fila) { return fila[1]; }));
    filas.forEach(function (fila) {
        const bloque = document.createElement('div');
        bloque.className = 'fila-grafico';
        const cabecera = document.createElement('div');
        cabecera.className = 'dato-grafico';
        const nombre = document.createElement('span');
        nombre.textContent = fila[0];
        const valor = document.createElement('span');
        valor.textContent = unidad === 'soles' ? QBI.moneda(fila[1]) : QBI.numero(fila[1]) + ' unidades';
        cabecera.append(nombre, valor);
        const pista = document.createElement('div');
        pista.className = 'pista';
        pista.setAttribute('aria-hidden', 'true');
        const barra = document.createElement('div');
        barra.className = 'barra';
        barra.style.width = (mayor === 0 ? 0 : fila[1] / mayor * 100) + '%';
        pista.append(barra);
        bloque.append(cabecera, pista);
        contenedor.append(bloque);
    });
}