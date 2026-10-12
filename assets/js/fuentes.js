const listaFuentes = document.getElementById('lista-fuentes');
if (listaFuentes) {
    const filas = QBI.fuentes().map(function (fuente) {
        return [fuente.nombre, fuente.motor, fuente.habilitada ? 'Habilitada (demo)' : 'Deshabilitada (demo)', QBI.enlace('Editar', 'configurar-fuente.html?id=' + encodeURIComponent(fuente.id))];
    });
    QBI.tabla(listaFuentes, ['Nombre', 'Motor', 'Estado local', 'Configuración'], filas, 'Catálogo de demostración');
}
const formFuente = document.getElementById('form-fuente');
if (formFuente) {
    const id = new URLSearchParams(window.location.search).get('id');
    const fuente = QBI.fuentes().find(function (item) { return item.id === id; });
    ['nombre', 'servidor', 'puerto', 'base-datos', 'usuario'].forEach(function (campo) { document.getElementById(campo).required = true; });
    const puerto = document.getElementById('puerto');
    puerto.min = 1;
    puerto.max = 65535;
    puerto.step = 1;
    if (fuente) {
        document.getElementById('titulo-fuente').textContent = 'Editar ' + fuente.nombre;
        document.getElementById('nombre').value = fuente.nombre;
        document.getElementById('servidor').value = fuente.servidor;
        puerto.value = fuente.puerto;
        document.getElementById('base-datos').value = fuente.base;
        document.getElementById('usuario').value = fuente.usuario;
        document.getElementById('habilitada').checked = fuente.habilitada;
    }
    document.getElementById('probar-conexion').addEventListener('click', function () {
        if (formFuente.reportValidity()) QBI.mensaje('mensaje-fuente', 'Los campos requeridos están completos. No se ha probado ninguna conexión real.');
    });
    formFuente.addEventListener('submit', function (evento) {
        evento.preventDefault();
        const nombre = document.getElementById('nombre').value.trim();
        if (!Validaciones.nombre(nombre)) {
            QBI.mensaje('mensaje-fuente', 'El nombre necesita al menos dos caracteres.', true);
            return;
        }
        const nueva = {
            id: fuente ? fuente.id : QBI.crearId(),
            nombre: nombre,
            motor: 'MySQL',
            servidor: document.getElementById('servidor').value.trim(),
            puerto: Number(puerto.value),
            base: document.getElementById('base-datos').value.trim(),
            usuario: document.getElementById('usuario').value.trim(),
            habilitada: document.getElementById('habilitada').checked
        };
        // La contraseña se descarta: nunca debe almacenarse en localStorage.
        document.getElementById('clave').value = '';
        const fuentes = QBI.fuentes();
        const indice = fuentes.findIndex(function (item) { return item.id === nueva.id; });
        if (indice >= 0) fuentes[indice] = nueva;
        else fuentes.push(nueva);
        if (QBI.guardar('fuentes', fuentes)) window.location.href = 'fuentes.html';
        else QBI.mensaje('mensaje-fuente', 'La fuente no pudo conservarse entre páginas.', true);
    });
}