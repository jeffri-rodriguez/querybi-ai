// Validaciones del lado del cliente. No sustituyen validaciones de un backend.
const Validaciones = {
    normalizar: function (texto) {
        return texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[¿?¡!.,]/g, '').replace(/\s+/g, ' ').trim();
    },

    reconocerEjemplo: function (pregunta) {
        const texto = this.normalizar(pregunta);
        return Object.values(datosDemo.ejemplos).find(function (ejemplo) {
            return Validaciones.normalizar(ejemplo.pregunta) === texto;
        }) || null;
    },

    clave: function (primera, segunda) {
        if (primera.length < 8) {
            return 'La contraseña de prueba debe tener al menos 8 caracteres.';
        }
        if (primera !== segunda) {
            return 'Las dos contraseñas no coinciden.';
        }
        return '';
    },

    nombre: function (texto) {
        return texto.trim().length >= 2;
    }
};