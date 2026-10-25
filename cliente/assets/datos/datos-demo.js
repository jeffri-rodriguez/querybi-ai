// Datos sintéticos preparados para la interfaz; no se ejecuta SQL ni se usa IA.
// Las filas y el SQL de referencia coinciden con el script inicial de la tienda.
const datosDemo = {
    fuenteEjemplosId: '1', // ID de Tienda Demo en el script SQL inicial.
    ejemplos: {
        mensual: {
            id: 'mensual',
            pregunta: '¿Cuál fue el total vendido por mes entre enero y marzo de 2025?',
            titulo: 'Ventas del primer trimestre de 2025',
            columnas: ['Mes', 'Ventas (S/)'],
            filas: [['2025-01', 347], ['2025-02', 844], ['2025-03', 1214]],
            unidad: 'soles',
            sql: "SELECT DATE_FORMAT(fecha_venta, '%Y-%m') AS mes, SUM(total_importe) AS ventas\nFROM db_empresa_demo.ventas\nWHERE fecha_venta >= '2025-01-01' AND fecha_venta < '2025-04-01'\nGROUP BY DATE_FORMAT(fecha_venta, '%Y-%m')\nORDER BY mes;"
        },
        productos: {
            id: 'productos',
            pregunta: '¿Cuáles son los cinco productos más vendidos en el primer trimestre de 2025?',
            titulo: 'Productos más vendidos · Primer trimestre de 2025',
            columnas: ['Producto', 'Unidades'],
            filas: [['Lapicero', 87], ['Cuaderno', 73], ['Agenda', 62], ['Carpeta', 60], ['Resaltador', 54]],
            unidad: 'unidades',
            sql: "SELECT p.nombre_producto, SUM(d.cantidad) AS unidades\nFROM db_empresa_demo.productos p\nJOIN db_empresa_demo.detalle_ventas d ON p.id = d.producto_id\nJOIN db_empresa_demo.ventas v ON v.id = d.venta_id\nWHERE v.fecha_venta >= '2025-01-01' AND v.fecha_venta < '2025-04-01'\nGROUP BY p.id, p.nombre_producto\nORDER BY unidades DESC, p.nombre_producto ASC\nLIMIT 5;"
        },
        ciudades: {
            id: 'ciudades',
            pregunta: '¿Cuánto vendimos por ciudad en el primer trimestre de 2025?',
            titulo: 'Ventas por ciudad · Primer trimestre de 2025',
            columnas: ['Ciudad', 'Ventas (S/)'],
            filas: [['Arequipa', 839], ['Lima', 819], ['Trujillo', 747]],
            unidad: 'soles',
            sql: "SELECT c.ciudad, SUM(v.total_importe) AS ventas\nFROM db_empresa_demo.clientes c\nJOIN db_empresa_demo.ventas v ON c.id = v.cliente_id\nWHERE v.fecha_venta >= '2025-01-01' AND v.fecha_venta < '2025-04-01'\nGROUP BY c.ciudad\nORDER BY ventas DESC, c.ciudad ASC;"
        },
        vacio: {
            id: 'vacio',
            pregunta: '¿Cuánto vendimos durante 2024?',
            titulo: 'Ventas de 2024',
            columnas: ['Mes', 'Ventas (S/)'],
            filas: [],
            unidad: 'soles',
            sql: "SELECT DATE_FORMAT(fecha_venta, '%Y-%m') AS mes, SUM(total_importe) AS ventas\nFROM db_empresa_demo.ventas\nWHERE fecha_venta >= '2024-01-01' AND fecha_venta < '2025-01-01'\nGROUP BY DATE_FORMAT(fecha_venta, '%Y-%m')\nORDER BY mes;"
        }
    },
    // Estos catalogos siguen siendo locales; los IDs iniciales coinciden con el SQL.
    empresas: [
        { id: '1', nombre: 'Tienda Demo', correo: 'contacto@tienda.test', activo: true }
    ],
    administradores: [
        { id: '2', nombre: 'Administrador Demo', correo: 'admin@tienda.test', empresaId: '1', rol: 'Administrador', activo: true }
    ],
    usuarios: [
        { id: '3', nombre: 'Usuario Demo', correo: 'usuario@tienda.test', empresaId: '1', rol: 'Usuario', activo: true }
    ]
};
