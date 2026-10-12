// Datos ficticios. No proceden de una base de datos ni de un servicio de IA.
const datosDemo = {
    ejemplos: {
        mensual: {
            id: 'mensual',
            pregunta: '¿Cuál fue el total vendido por mes entre enero y marzo de 2025?',
            titulo: 'Ventas del primer trimestre de 2025',
            columnas: ['Mes', 'Ventas (S/)'],
            filas: [['Enero', 12500], ['Febrero', 15800], ['Marzo', 18200]],
            unidad: 'soles',
            sql: "SELECT MONTH(fecha) AS mes, SUM(total) AS ventas\nFROM ventas\nWHERE fecha >= '2025-01-01' AND fecha < '2025-04-01'\nGROUP BY MONTH(fecha)\nORDER BY mes;"
        },
        productos: {
            id: 'productos',
            pregunta: '¿Cuáles son los cinco productos más vendidos en el primer trimestre de 2025?',
            titulo: 'Productos más vendidos · Primer trimestre de 2025',
            columnas: ['Producto', 'Unidades'],
            filas: [['Cuaderno', 420], ['Lapicero', 350], ['Carpeta', 210], ['Resaltador', 160], ['Agenda', 90]],
            unidad: 'unidades',
            sql: "SELECT p.nombre, SUM(d.cantidad) AS unidades\nFROM productos p\nJOIN detalle_venta d ON p.id = d.id_producto\nJOIN ventas v ON v.id = d.id_venta\nWHERE v.fecha >= '2025-01-01' AND v.fecha < '2025-04-01'\nGROUP BY p.id, p.nombre\nORDER BY unidades DESC, p.nombre ASC\nLIMIT 5;"
        },
        ciudades: {
            id: 'ciudades',
            pregunta: '¿Cuánto vendimos por ciudad en el primer trimestre de 2025?',
            titulo: 'Ventas por ciudad · Primer trimestre de 2025',
            columnas: ['Ciudad', 'Ventas (S/)'],
            filas: [['Lima', 25000], ['Arequipa', 13500], ['Trujillo', 8000]],
            unidad: 'soles',
            sql: "SELECT c.ciudad, SUM(v.total) AS ventas\nFROM clientes c\nJOIN ventas v ON c.id = v.id_cliente\nWHERE v.fecha >= '2025-01-01' AND v.fecha < '2025-04-01'\nGROUP BY c.ciudad\nORDER BY ventas DESC;"
        },
        vacio: {
            id: 'vacio',
            pregunta: '¿Cuánto vendimos durante 2024?',
            titulo: 'Ventas de 2024',
            columnas: ['Mes', 'Ventas (S/)'],
            filas: [],
            unidad: 'soles',
            sql: "SELECT MONTH(fecha) AS mes, SUM(total) AS ventas\nFROM ventas\nWHERE fecha >= '2024-01-01' AND fecha < '2025-01-01'\nGROUP BY MONTH(fecha);"
        }
    },
    fuentes: [
        { id: 'tienda', nombre: 'Tienda Demo', motor: 'MySQL', servidor: 'db.ejemplo.test', puerto: 3306, base: 'tienda_demo', usuario: 'lector_demo', habilitada: true },
        { id: 'almacen', nombre: 'Almacén Demo', motor: 'MySQL', servidor: 'almacen.ejemplo.test', puerto: 3306, base: 'almacen_demo', usuario: 'lector', habilitada: false }
    ],
    usuarios: [
        { id: 'admin', nombre: 'Administrador Demo', correo: 'admin@ejemplo.test', rol: 'Administrador', activo: true },
        { id: 'analista', nombre: 'Analista Demo', correo: 'analista@ejemplo.test', rol: 'Analista', activo: true }
    ]
};