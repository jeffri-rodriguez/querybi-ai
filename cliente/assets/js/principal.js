// Funciones compartidas. localStorage pertenece al navegador, no a un servidor.
const QBI = {
    prefijo: 'querybi-demo-v3-'
    + (window.QBI_SESION ? window.QBI_SESION.id : 'visitante')
    + '-',
    temporal: {},

    leer: function (clave, valorInicial) {
        if (Object.prototype.hasOwnProperty.call(this.temporal, clave)) {
            return this.temporal[clave];
        }
        try {
            const texto = localStorage.getItem(this.prefijo + clave);
            if (texto !== null) {
                return JSON.parse(texto);
            }
        } catch (error) {
            // Si el navegador bloquea el almacenamiento, se usa el valor inicial.
        }
        return JSON.parse(JSON.stringify(valorInicial));
    },

    guardar: function (clave, valor) {
        this.temporal[clave] = valor;
        try {
            localStorage.setItem(this.prefijo + clave, JSON.stringify(valor));
            return true;
        } catch (error) {
            this.avisoAlmacenamiento();
            return false;
        }
    },

    avisoAlmacenamiento: function () {
        if (!document.getElementById('aviso-almacenamiento')) {
            const aviso = document.createElement('p');
            aviso.id = 'aviso-almacenamiento';
            aviso.className = 'aviso';
            aviso.setAttribute('role', 'status');
            aviso.textContent = 'El navegador no permite guardar datos. Los cambios durarán solo mientras permanezcas en esta página.';
            document.querySelector('main').prepend(aviso);
        }
    },

    mensaje: function (id, texto, error = false) {
        const elemento = document.getElementById(id);
        elemento.textContent = texto;
        elemento.className = error ? 'mensaje error' : 'mensaje';
        elemento.hidden = false;
    },

    crearId: function () {
        return Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 8);
    },

    numero: function (valor) {
        return new Intl.NumberFormat('es-PE', { maximumFractionDigits: 2 }).format(valor);
    },

    moneda: function (valor) {
        return new Intl.NumberFormat('es-PE', { style: 'currency', currency: 'PEN' }).format(valor);
    },

    fecha: function (valor) {
        return new Date(valor).toLocaleString('es-PE', { dateStyle: 'short', timeStyle: 'short' });
    },

    // textContent evita interpretar como HTML lo escrito por el usuario.
    tabla: function (contenedor, columnas, filas, titulo) {
        contenedor.replaceChildren();
        const marco = document.createElement('div');
        marco.className = 'tabla-contenedor';
        const tabla = document.createElement('table');
        const caption = document.createElement('caption');
        caption.textContent = titulo;
        tabla.append(caption);
        const encabezado = document.createElement('thead');
        const filaTitulos = document.createElement('tr');
        columnas.forEach(function (texto) {
            const celda = document.createElement('th');
            celda.scope = 'col';
            celda.textContent = texto;
            filaTitulos.append(celda);
        });
        encabezado.append(filaTitulos);
        tabla.append(encabezado);
        const cuerpo = document.createElement('tbody');
        filas.forEach(function (valores) {
            const fila = document.createElement('tr');
            valores.forEach(function (valor) {
                const celda = document.createElement('td');
                if (valor instanceof Node) {
                    celda.append(valor);
                } else {
                    celda.textContent = valor;
                }
                fila.append(celda);
            });
            cuerpo.append(fila);
        });
        tabla.append(cuerpo);
        marco.append(tabla);
        contenedor.append(marco);
    },

    enlace: function (texto, destino) {
        const enlace = document.createElement('a');
        enlace.textContent = texto;
        enlace.href = destino;
        return enlace;
    },

    boton: function (texto, accion) {
        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'boton boton-secundario boton-pequeno';
        boton.textContent = texto;
        boton.addEventListener('click', accion);
        return boton;
    },

    historial: function () {
        const datos = this.leer('historial', []);

        if (!Array.isArray(datos)) return [];

        return datos.filter(function (consulta) {
            return consulta
                && typeof consulta.id === 'string'
                && typeof consulta.pregunta === 'string';
        }).map(function (consulta) {
            const ejemplo =
                consulta.tipo === 'ejemplo'
                || Boolean(consulta.ejemploId);

            const sinResultados =
                consulta.estado === 'Sin resultados'
                || consulta.estado === 'Ejemplo sin resultados';

            return {
                ...consulta,
                tipo: ejemplo ? 'ejemplo' : 'pregunta',
                fuenteId: consulta.fuenteId
                    || (ejemplo ? 'tienda' : ''),
                fuenteNombre: consulta.fuenteNombre
                    || (ejemplo ? 'Tienda Demo' : 'Sin fuente'),
                estado: ejemplo
                    ? (
                        sinResultados
                            ? 'Ejemplo sin resultados'
                            : 'Ejemplo listo'
                    )
                    : (consulta.estado || 'Pendiente')
            };
        });
    },

    reportes: function () {
        const datos = this.leer('reportes', []);

        return Array.isArray(datos)
            ? JSON.parse(JSON.stringify(datos))
            : [];
    },

    guardarLista: function (clave, lista) {
        const anteriores = JSON.parse(
            JSON.stringify(this.leer(clave, []))
        );

        if (this.guardar(clave, lista)) return true;

        this.temporal[clave] = anteriores;

        return false;
    },

    perfil: function () {
        const cuenta = window.QBI_SESION;

        if (!cuenta) {
            return {
                nombre: 'Visitante',
                correo: '',
                rol: 'Sin sesión'
            };
        }

        const preferencias = this.leer('perfil', null);

        const alias = preferencias
            && typeof preferencias.nombre === 'string'
                ? preferencias.nombre.trim()
                : '';

        return {
            nombre: alias.length >= 2 && alias.length <= 80
                ? alias
                : cuenta.nombre,
            correo: cuenta.correo,
            rol: cuenta.nombre_rol
        };
    },

    fuentes: function () {
        return Array.isArray(window.QBI_FUENTES)
            ? JSON.parse(JSON.stringify(window.QBI_FUENTES))
            : [];
    },

    // Escapa las celdas y neutraliza fórmulas al abrir CSV en una hoja de cálculo.
    crearCSV: function (columnas, filas) {
        return [columnas].concat(filas).map(function (fila) {
            return fila.map(function (valor) {
                let texto = String(valor);
                if (typeof valor === 'string' && /^[\s]*[=+@-]/.test(texto)) {
                    texto = "'" + texto;
                }
                return '"' + texto.replace(/"/g, '""') + '"';
            }).join(',');
        }).join('\r\n');
    },

    descargarCSV: function (columnas, filas) {
        const contenido = '\uFEFF' + this.crearCSV(columnas, filas);
        const archivo = new Blob([contenido], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(archivo);
        const enlace = document.createElement('a');
        enlace.href = url;
        enlace.download = 'reporte-querybi.csv';
        document.body.append(enlace);
        enlace.click();
        enlace.remove();
        setTimeout(function () { URL.revokeObjectURL(url); }, 1000);
    }
};

document.addEventListener('DOMContentLoaded', function () {
    const menu = document.querySelector('.menu-lateral');
    if (menu) {
        menu.id = 'menu-lateral';
        const boton = document.createElement('button');
        boton.type = 'button';
        boton.className = 'boton boton-secundario menu-boton';
        boton.textContent = 'Menú';
        boton.setAttribute('aria-controls', 'menu-lateral');
        boton.setAttribute('aria-expanded', 'false');
        menu.classList.add('cerrado');
        boton.addEventListener('click', function () {
            const cerrado = menu.classList.toggle('cerrado');
            boton.setAttribute('aria-expanded', String(!cerrado));
        });
        document.querySelector('.encabezado-app').append(boton);
    }
    const portada = document.getElementById('grafico-portada');
    if (portada) {
        dibujarGrafico(portada, datosDemo.ejemplos.mensual.filas, 'soles');
    }
});