const dialogo = document.getElementById('dialogo-usuario');
const formularioUsuario = document.getElementById('form-usuario');
let usuarioEditado = null;
document.getElementById('nombre-usuario').required = true;
document.getElementById('correo-usuario').required = true;

function abrirFormulario(usuario = null) {
    usuarioEditado = usuario;
    formularioUsuario.reset();
    document.getElementById('mensaje-usuario').hidden = true;
    document.getElementById('titulo-usuario').textContent = usuario ? 'Editar usuario' : 'Nuevo usuario';
    document.getElementById('nombre-usuario').value = usuario ? usuario.nombre : '';
    document.getElementById('correo-usuario').value = usuario ? usuario.correo : '';
    document.getElementById('rol-usuario').value = usuario ? usuario.rol : 'Analista';
    document.getElementById('activo-usuario').checked = usuario ? usuario.activo : true;
    dialogo.showModal();
    document.getElementById('nombre-usuario').focus();
}
function mostrarUsuarios() {
    const usuarios = QBI.leer('usuarios', datosDemo.usuarios);
    const filas = usuarios.map(function (usuario) {
        return [usuario.nombre, usuario.correo, usuario.rol, usuario.activo ? 'Activo' : 'Inactivo', QBI.boton('Editar', function () { abrirFormulario(usuario); })];
    });
    QBI.tabla(document.getElementById('lista-usuarios'), ['Nombre', 'Correo', 'Rol', 'Estado', 'Acciones'], filas, 'Usuarios ficticios');
}
document.getElementById('nuevo-usuario').addEventListener('click', function () { abrirFormulario(); });
document.getElementById('cancelar-usuario').addEventListener('click', function () { dialogo.close(); });
formularioUsuario.addEventListener('submit', function (evento) {
    evento.preventDefault();
    const nombre = document.getElementById('nombre-usuario').value.trim();
    const correo = document.getElementById('correo-usuario').value.trim().toLowerCase();
    const usuarios = QBI.leer('usuarios', datosDemo.usuarios);
    if (!Validaciones.nombre(nombre)) {
        QBI.mensaje('mensaje-usuario', 'Escribe un nombre de al menos dos caracteres.', true);
        return;
    }
    if (usuarios.some(function (usuario) { return usuario.correo.toLowerCase() === correo && usuario.id !== (usuarioEditado ? usuarioEditado.id : null); })) {
        QBI.mensaje('mensaje-usuario', 'Ese correo ya aparece en la lista.', true);
        return;
    }
    const usuario = { id: usuarioEditado ? usuarioEditado.id : QBI.crearId(), nombre: nombre, correo: correo, rol: document.getElementById('rol-usuario').value, activo: document.getElementById('activo-usuario').checked };
    const indice = usuarios.findIndex(function (item) { return item.id === usuario.id; });
    if (indice >= 0) usuarios[indice] = usuario;
    else usuarios.push(usuario);
    const guardado = QBI.guardar('usuarios', usuarios);
    dialogo.close();
    mostrarUsuarios();
    QBI.mensaje('mensaje-usuarios', guardado ? 'Usuario ficticio guardado en este navegador.' : 'Usuario actualizado solo para esta página.');
});
mostrarUsuarios();