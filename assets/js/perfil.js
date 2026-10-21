(function () {
    const formulario = document.getElementById('form-perfil');
    const nombre = document.getElementById('nombre');
    const guardar = document.getElementById('guardar-perfil');
    const restablecer = document.getElementById('restablecer');

    nombre.value = QBI.perfil().nombre;

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        const valor = nombre.value.trim();

        if (valor.length < 2 || valor.length > 80) {
            QBI.mensaje(
                'mensaje-perfil',
                'Escribe un nombre de entre 2 y 80 caracteres.',
                true
            );

            return;
        }

        if (!formulario.reportValidity()) return;

        const anterior = QBI.leer('perfil', null);

        if (!QBI.guardar('perfil', { nombre: valor })) {
            QBI.temporal.perfil = anterior;

            QBI.mensaje(
                'mensaje-perfil',
                'No se pudo guardar el nombre. Revisa el almacenamiento del navegador.',
                true
            );

            return;
        }

        nombre.value = valor;

        QBI.mensaje(
            'mensaje-perfil',
            'Nombre de presentación guardado para esta cuenta en este navegador.'
        );
    });

    restablecer.addEventListener('click', function () {
        const confirmado = window.confirm(
            'Se eliminarán los datos locales de tu cuenta en este navegador. '
            + 'Esta acción no se puede deshacer. ¿Continuar?'
        );

        if (!confirmado) return;

        try {
            const claves = Object.keys(localStorage).filter(clave =>
                clave.startsWith(QBI.prefijo)
            );

            claves.forEach(clave => localStorage.removeItem(clave));

            QBI.temporal = {};

            window.location.reload();
        } catch (error) {
            QBI.mensaje(
                'mensaje-restablecer',
                'No se pudo completar el restablecimiento. '
                    + 'Recarga la página para revisar qué datos se conservaron.',
                true
            );
        }
    });

    nombre.disabled = false;
    guardar.disabled = false;
    restablecer.disabled = false;
})();