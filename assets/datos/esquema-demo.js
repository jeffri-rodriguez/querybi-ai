// Metadatos ilustrativos de los ejemplos. No son el diseño definitivo de la BD.
const esquemasDemo = {
    tienda: {
        tablas: {
            clientes: [
                ['id', 'Identificador del cliente'],
                ['ciudad', 'Ciudad del cliente']
            ],
            ventas: [
                ['id', 'Identificador de la venta'],
                ['fecha', 'Fecha de la venta'],
                ['total', 'Importe en soles'],
                ['id_cliente', 'Cliente asociado']
            ],
            productos: [
                ['id', 'Identificador del producto'],
                ['nombre', 'Nombre del producto']
            ],
            detalle_venta: [
                ['id_venta', 'Venta asociada'],
                ['id_producto', 'Producto asociado'],
                ['cantidad', 'Unidades vendidas']
            ]
        },
        relaciones: [
            ['clientes.id', 'ventas.id_cliente'],
            ['ventas.id', 'detalle_venta.id_venta'],
            ['productos.id', 'detalle_venta.id_producto']
        ]
    }
};