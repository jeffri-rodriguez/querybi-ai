// Interacciones de la web pública. Independiente de sesiones y paneles.
document.addEventListener('DOMContentLoaded', function () {
    const pagina = document.querySelector('.web-publica');
    if (!pagina) return;

    pagina.classList.add('pub-js');

    const botonMenu = document.getElementById('boton-menu-publico');
    const navegacion = document.getElementById('navegacion-publica');

    if (botonMenu && navegacion) {
        botonMenu.hidden = false;

        function cerrarMenu() {
            botonMenu.setAttribute('aria-expanded', 'false');
            navegacion.classList.remove('pub-menu-abierto');
        }

        botonMenu.addEventListener('click', function () {
            const abierto = botonMenu.getAttribute('aria-expanded') === 'true';
            botonMenu.setAttribute('aria-expanded', String(!abierto));
            navegacion.classList.toggle('pub-menu-abierto', !abierto);
        });

        navegacion.addEventListener('click', function (evento) {
            if (evento.target.closest('a')) cerrarMenu();
        });

        document.addEventListener('keydown', function (evento) {
            if (evento.key === 'Escape' && botonMenu.getAttribute('aria-expanded') === 'true') {
                cerrarMenu();
                botonMenu.focus();
            }
        });
    }

    // Ejemplos preparados para la presentación comercial del producto.
    const ejemplos = {
        ventas: {
            pregunta: '¿Cuánto vendimos por mes en el primer trimestre de 2025?',
            etiqueta: 'Ventas del trimestre',
            total: 2405,
            moneda: true,
            filas: [['Enero', 347], ['Febrero', 844], ['Marzo', 1214]],
            sql: "SELECT DATE_FORMAT(fecha_venta, '%Y-%m') AS mes,\n       SUM(total_importe) AS total\nFROM ventas\nWHERE fecha_venta >= '2025-01-01'\n  AND fecha_venta < '2025-04-01'\nGROUP BY mes\nORDER BY mes;"
        },
        productos: {
            pregunta: '¿Cuáles fueron los cinco productos más vendidos?',
            etiqueta: 'Unidades vendidas de los cinco productos',
            total: 336,
            moneda: false,
            filas: [['Lapicero', 87], ['Cuaderno', 73], ['Agenda', 62], ['Carpeta', 60], ['Resaltador', 54]],
            sql: 'SELECT p.nombre_producto,\n       SUM(dv.cantidad) AS unidades\nFROM detalle_ventas dv\nJOIN productos p ON p.id = dv.producto_id\nGROUP BY p.id, p.nombre_producto\nORDER BY unidades DESC, p.nombre_producto ASC\nLIMIT 5;'
        },
        ciudades: {
            pregunta: '¿Cómo se distribuyen nuestras ventas por ciudad?',
            etiqueta: 'Ventas de las ciudades analizadas',
            total: 2405,
            moneda: true,
            filas: [['Arequipa', 839], ['Lima', 819], ['Trujillo', 747]],
            sql: 'SELECT c.ciudad, SUM(v.total_importe) AS total\nFROM ventas v\nJOIN clientes c ON c.id = v.cliente_id\nGROUP BY c.ciudad\nORDER BY total DESC;'
        }
    };

    const grafico = document.getElementById('publico-grafico');
    if (!grafico) return;

    const pregunta = document.getElementById('publico-pregunta');
    const etiqueta = document.getElementById('publico-etiqueta');
    const total = document.getElementById('publico-total');
    const sql = document.getElementById('publico-sql');
    const anuncio = document.getElementById('publico-anuncio');
    const botones = document.querySelectorAll('[data-ejemplo-publico]');
    const formato = new Intl.NumberFormat('es-PE', { maximumFractionDigits: 0 });

    function presentar(clave, anunciar = true) {
        const ejemplo = ejemplos[clave];
        if (!ejemplo) return;

        const valor = numero => (ejemplo.moneda ? 'S/ ' : '') + formato.format(numero);
        pregunta.textContent = ejemplo.pregunta;
        etiqueta.textContent = ejemplo.etiqueta;
        total.textContent = valor(ejemplo.total);
        sql.textContent = ejemplo.sql;
        grafico.setAttribute('aria-label', ejemplo.pregunta);

        const fragmento = document.createDocumentFragment();
        const maximo = Math.max(...ejemplo.filas.map(fila => fila[1]));

        ejemplo.filas.forEach(function ([nombre, cantidad]) {
            const fila = document.createElement('div');
            fila.className = 'pub-fila-dato';

            const nombreElemento = document.createElement('span');
            nombreElemento.textContent = nombre;

            const medidor = document.createElement('meter');
            medidor.className = 'pub-medidor';
            medidor.min = 0;
            medidor.max = maximo;
            medidor.value = cantidad;
            medidor.setAttribute('aria-label', nombre + ': ' + valor(cantidad));

            const cantidadElemento = document.createElement('span');
            cantidadElemento.className = 'pub-valor';
            cantidadElemento.textContent = valor(cantidad);

            fila.append(nombreElemento, medidor, cantidadElemento);
            fragmento.append(fila);
        });

        grafico.replaceChildren(fragmento);
        botones.forEach(function (boton) {
            boton.setAttribute('aria-pressed', String(boton.dataset.ejemploPublico === clave));
        });

        if (anunciar) anuncio.textContent = ejemplo.pregunta + ' ' + ejemplo.etiqueta + ': ' + valor(ejemplo.total);
    }

    botones.forEach(function (boton) {
        boton.hidden = false;
        boton.addEventListener('click', function () {
            presentar(boton.dataset.ejemploPublico);
        });
    });

    presentar('ventas', false);
});
