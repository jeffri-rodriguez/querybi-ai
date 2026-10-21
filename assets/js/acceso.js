const formularioRecuperacion =
    document.getElementById('form-recuperacion');

if (formularioRecuperacion) {
    document.getElementById('correo').required = true;

    formularioRecuperacion.addEventListener(
        'submit',
        function (evento) {
            evento.preventDefault();

            QBI.mensaje(
                'mensaje-acceso',
                'Simulación: si existiera una cuenta asociada, recibiría instrucciones. No se envió ningún correo.'
            );
        }
    );
}