const perfil = QBI.perfil();
document.getElementById('nombre').value = perfil.nombre;
document.getElementById('correo').value = perfil.correo;
document.getElementById('correo').readOnly = true;
document.getElementById('nombre').required = true;
document.getElementById('rol-perfil').textContent = 'Perfil de demostración: ' + perfil.rol;
document.getElementById('form-perfil').addEventListener('submit', function (evento) {
    evento.preventDefault();
    const nombre = document.getElementById('nombre').value.trim();
    if (!Validaciones.nombre(nombre)) {
        QBI.mensaje('mensaje-perfil', 'Escribe al menos dos caracteres para el nombre.', true);
        return;
    }
    const guardado = QBI.guardar('perfil', { nombre: nombre, correo: document.getElementById('correo').value.trim(), rol: perfil.rol });
    QBI.mensaje('mensaje-perfil', guardado ? 'Nombre de presentación guardado en este navegador. La cuenta no fue modificada.' : 'Perfil actualizado solo para esta página.');
});
document.getElementById('form-clave').addEventListener('submit', function (evento) {
    evento.preventDefault();
    const error = Validaciones.clave(document.getElementById('nueva').value, document.getElementById('confirmacion').value);
    QBI.mensaje('mensaje-clave', error || 'Los campos coinciden. No se guardó ni se cambió ninguna contraseña.', Boolean(error));
    if (!error) evento.target.reset();
});
document.getElementById('restablecer').addEventListener('click', function () {
    if (!window.confirm('¿Borrar todos los datos de esta demostración guardados en este navegador?')) return;
    try {
        Object.keys(localStorage).filter(function (clave) { return clave.startsWith(QBI.prefijo); }).forEach(function (clave) { localStorage.removeItem(clave); });
        QBI.temporal = {};
        window.location.reload();
    } catch (error) {
        QBI.mensaje('mensaje-perfil', 'El navegador no permite modificar el almacenamiento.', true);
    }
});