(function () {
    const tipo = document.querySelector('[data-gestion]').dataset.gestion;

    const opciones = {
        empresas: {
            singular: 'empresa',
            plural: 'empresas',
            rol: ''
        },
        administradores: {
            singular: 'administrador',
            plural: 'administradores',
            rol: 'Administrador'
        },
        usuarios: {
            singular: 'usuario',
            plural: 'usuarios',
            rol: 'Usuario'
        }
    };

    const config = opciones[tipo];
    const empresaSesion = window.QBI_SESION.empresa_id;
    const campo = id => document.getElementById(id);

    const formulario = campo('form-registro');
    const dialogo = campo('dialogo-registro');
    const detalle = campo('dialogo-detalle');

    let editadoId = null;
    let detalleId = null;

    function leer(clave) {
        const datos = QBI.leer(clave, datosDemo[clave]);

        if (!Array.isArray(datos)) return [];

        return datos.filter(item =>
            item
            && typeof item.id === 'string'
            && typeof item.nombre === 'string'
            && typeof item.correo === 'string'
        ).map(item => ({ ...item }));
    }

    function registros() {
        const lista = leer(tipo);

        if (tipo !== 'usuarios') return lista;

        return lista
            .filter(item => item.rol === 'Usuario')
            .map(item => ({
                ...item,
                empresaId: item.empresaId || empresaSesion
            }))
            .filter(item => item.empresaId === empresaSesion);
    }

    function nombreEmpresa(id) {
        if (tipo === 'usuarios') {
            return window.QBI_SESION.empresa_nombre;
        }

        const empresa = leer('empresas').find(item => item.id === id);

        return empresa ? empresa.nombre : 'Empresa no disponible';
    }

    function mostrar() {
        const busqueda = Validaciones.normalizar(
            campo('buscar-registro').value
        );

        const estado = campo('filtro-activo').value;
        const lista = registros();

        const visibles = lista.filter(item => {
            const coincide = Validaciones.normalizar(
                item.nombre + ' ' + item.correo
            ).includes(busqueda);

            return coincide && (
                estado === 'todos'
                || (estado === 'activos' ? item.activo : !item.activo)
            );
        });

        campo('resumen-gestion').textContent =
            visibles.length + ' de ' + lista.length + ' registros. '
            + (visibles.length ? '' : 'No hay coincidencias.');

        const columnas = ['Nombre', 'Correo'];

        if (tipo !== 'empresas') {
            columnas.push('Empresa', 'Rol');
        }

        columnas.push('Estado', 'Acciones');

        const filas = visibles.map(item => {
            const acciones = document.createElement('div');
            acciones.className = 'acciones-tabla';

            acciones.append(
                QBI.boton('Ver detalle', () => verDetalle(item.id)),
                QBI.boton('Editar', () => abrir(item.id))
            );

            const fila = [item.nombre, item.correo];

            if (tipo !== 'empresas') {
                fila.push(nombreEmpresa(item.empresaId), config.rol);
            }

            fila.push(
                item.activo ? 'Activo' : 'Inactivo',
                acciones
            );

            return fila;
        });

        QBI.tabla(
            campo('lista-gestion'),
            columnas,
            filas,
            'Catálogo de ' + config.plural
        );
    }

    function abrir(id = null) {
        const item = id === null
            ? null
            : registros().find(registro => registro.id === id);

        if (id !== null && !item) {
            QBI.mensaje(
                'mensaje-gestion',
                'El registro ya no está disponible.',
                true
            );
            return;
        }

        editadoId = id;

        campo('titulo-registro').textContent =
            (item ? 'Editar ' : 'Nueva entrada: ') + config.singular;

        campo('nombre-registro').value = item ? item.nombre : '';
        campo('correo-registro').value = item ? item.correo : '';
        campo('activo-registro').checked = item
            ? Boolean(item.activo)
            : true;

        campo('mensaje-registro').hidden = true;
        campo('empresa-registro').replaceChildren();

        if (tipo === 'administradores') {
            const inicial = document.createElement('option');
            inicial.value = '';
            inicial.textContent = 'Selecciona una empresa';

            campo('empresa-registro').append(inicial);

            leer('empresas').forEach(empresa => {
                const opcion = document.createElement('option');
                opcion.value = empresa.id;
                opcion.textContent = empresa.nombre
                    + (empresa.activo ? '' : ' (inactiva)');

                campo('empresa-registro').append(opcion);
            });

            campo('empresa-registro').value = item
                ? item.empresaId
                : '';
        }

        dialogo.showModal();
        campo('nombre-registro').focus();
    }

    function verDetalle(id) {
        const item = registros().find(registro => registro.id === id);

        if (!item) return;

        detalleId = id;
        campo('titulo-detalle').textContent = item.nombre;

        const filas = [
            ['Nombre', item.nombre],
            ['Correo', item.correo]
        ];

        if (tipo !== 'empresas') {
            filas.push(
                ['Empresa', nombreEmpresa(item.empresaId)],
                ['Rol', config.rol]
            );
        }

        filas.push([
            'Estado en el catálogo',
            item.activo ? 'Activo' : 'Inactivo'
        ]);

        QBI.tabla(
            campo('contenido-detalle'),
            ['Campo', 'Valor'],
            filas,
            'Datos del registro'
        );

        detalle.showModal();
    }

    formulario.addEventListener('submit', function (evento) {
        evento.preventDefault();

        if (!formulario.reportValidity()) return;

        const nombre = campo('nombre-registro').value.trim();
        const correo = campo('correo-registro').value
            .trim()
            .toLowerCase();

        const activo = campo('activo-registro').checked;

        const empresaId = tipo === 'usuarios'
            ? empresaSesion
            : campo('empresa-registro').value;

        const lista = registros();
        let error = '';

        if (nombre.length < 2 || nombre.length > 80) {
            error = 'El nombre debe tener entre 2 y 80 caracteres.';
        } else if (
            correo.length > 120
            || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo)
        ) {
            error = 'Escribe un correo válido de hasta 120 caracteres.';
        } else if (
            editadoId !== null
            && !lista.some(item => item.id === editadoId)
        ) {
            error = 'El registro que intentas editar ya no está disponible.';
        } else if (
            lista.some(item =>
                item.id !== editadoId && (
                    tipo === 'empresas'
                        ? Validaciones.normalizar(item.nombre)
                            === Validaciones.normalizar(nombre)
                        : item.correo.toLowerCase() === correo
                )
            )
        ) {
            error = tipo === 'empresas'
                ? 'Ya existe una empresa con ese nombre.'
                : 'Ese correo ya está registrado en este catálogo.';
        }

        if (
            !error
            && tipo === 'empresas'
            && editadoId !== null
            && !activo
            && leer('administradores').some(item =>
                item.empresaId === editadoId && item.activo
            )
        ) {
            error = 'Primero desactiva los administradores de esta empresa en el catálogo.';
        }

        if (!error && tipo === 'administradores') {
            const empresa = leer('empresas').find(item =>
                item.id === empresaId
            );

            if (!empresa) {
                error = 'Selecciona una empresa existente.';
            } else if (activo && !empresa.activo) {
                error = 'Un administrador activo necesita una empresa activa.';
            }
        }

        if (error) {
            QBI.mensaje('mensaje-registro', error, true);
            return;
        }

        const nuevo = {
            id: editadoId || QBI.crearId(),
            nombre,
            correo,
            activo
        };

        if (tipo !== 'empresas') {
            Object.assign(nuevo, {
                empresaId,
                rol: config.rol
            });
        }

        const todos = leer(tipo);
        const indice = todos.findIndex(item => item.id === nuevo.id);

        if (indice >= 0) {
            todos[indice] = nuevo;
        } else {
            todos.push(nuevo);
        }

        if (!QBI.guardarLista(tipo, todos)) {
            QBI.mensaje(
                'mensaje-registro',
                'No se pudo guardar. Revisa el almacenamiento del navegador e inténtalo otra vez.',
                true
            );
            return;
        }

        dialogo.close();
        mostrar();

        QBI.mensaje(
            'mensaje-gestion',
            'Registro guardado en el catálogo local. Las cuentas de ingreso no han cambiado.'
        );
    });

    // Conserva también los datos iniciales si falla el primer guardado.
    QBI.temporal[tipo] = leer(tipo);

    campo('nuevo-registro').textContent = 'Añadir ' + config.singular;

    campo('etiqueta-correo').textContent = tipo === 'empresas'
        ? 'Correo de contacto'
        : 'Correo';

    campo('campo-empresa').hidden = tipo !== 'administradores';
    campo('empresa-registro').required = tipo === 'administradores';
    campo('empresa-registro').disabled = tipo !== 'administradores';

    campo('rol-registro').textContent = config.rol
        ? 'Rol del registro: ' + config.rol
        : '';

    campo('nuevo-registro').addEventListener('click', () => abrir());

    campo('cancelar-registro').addEventListener(
        'click',
        () => dialogo.close()
    );

    campo('cerrar-detalle').addEventListener(
        'click',
        () => detalle.close()
    );

    campo('editar-detalle').addEventListener('click', function () {
        detalle.close();
        abrir(detalleId);
    });

    campo('buscar-registro').addEventListener('input', mostrar);
    campo('filtro-activo').addEventListener('change', mostrar);

    mostrar();
})();