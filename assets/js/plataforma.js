(function () {
    function lista(clave) {
        const datos = QBI.leer(clave, datosDemo[clave]);
        return Array.isArray(datos) ? datos : [];
    }

    const empresas = lista('empresas');
    const administradores = lista('administradores');

    document.getElementById('cantidad-empresas').textContent =
        empresas.length;

    document.getElementById('cantidad-empresas-activas').textContent =
        empresas.filter(item => item && item.activo).length;

    document.getElementById('cantidad-administradores').textContent =
        administradores.length;
})();