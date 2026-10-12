const formularioLogin = document.getElementById('form-login');
if (formularioLogin) {
    const correo = document.getElementById('correo');
    correo.required = true;
    formularioLogin.addEventListener('submit', function (evento) {
        evento.preventDefault();
        const perfil = QBI.perfil();
        perfil.correo = correo.value.trim();
        perfil.rol = document.getElementById('rol').value;
        const guardado = QBI.guardar('perfil', perfil);
        if (guardado) {
            window.location.href = '../aplicacion/panel.html';
        } else {
            QBI.mensaje('mensaje-acceso', 'No se pudo conservar el perfil. Puedes entrar como visitante desde la navegación.');
        }
    });
}

const formularioRecuperacion = document.getElementById('form-recuperacion');
if (formularioRecuperacion) {
    document.getElementById('correo').required = true;
    formularioRecuperacion.addEventListener('submit', function (evento) {
        evento.preventDefault();
        QBI.mensaje('mensaje-acceso', 'Simulación: si existiera una cuenta asociada, recibiría instrucciones. No se envió ningún correo.');
    });
}