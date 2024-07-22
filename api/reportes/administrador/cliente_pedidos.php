<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../ayudantes/reportes.php');

// Se instancia la clase para crear el reporte
$pdf = new Report;

// Se verifica si existe un valor para la categoría, de lo contrario se muestra un mensaje.
if (isset($_GET['ID'])) {
    // Se incluyen las clases para la transferencia y acceso a datos.
    require_once('../../modelos/data/inventario_data.php');
    require_once('../../modelos/data/cliente_data.php');
    require_once('../../modelos/data/pedido_data.php');
    require_once('../../modelos/data/detalle_pedido_data.php');
    require_once('../../modelos/data/producto_data.php');
    
    // Se instancian las entidades correspondientes.
    $cliente = new ClientesData;
    $producto = new ProductoData;
    $pedido = new PedidoData;
    $dtpedido = new DetallesPedidosData;
    $dtproducto = new InventarioData;
    
    // Se establece el valor de la categoría, de lo contrario se muestra un mensaje.
    if ($cliente->setId($_GET['ID']) && $pedido->setCliente($_GET['ID'])) {
        // Se verifica si la categoría existe, de lo contrario se muestra un mensaje.
        if ($rowCliente = $cliente->readOne()) {
            // Se inicia el reporte con el encabezado del documento.
            $pdf->startReport2('Productos comprados por el cliente');
            
            // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
            if ($dataProductos = $cliente->pedidosCliente()) {
                // Colores para el encabezado
                $pdf->setFillColor(0, 128, 0); // Verde oscuro
                $pdf->setTextColor(255, 255, 255); // Blanco
                $pdf->setDrawColor(0, 0, 0); // Negro
                
                // Se establece la fuente para los encabezados.
                $pdf->setFont('Arial', 'B', 12);
                
                // Se imprimen las celdas con los encabezados.
                $pdf->cell(40, 10, 'Cliente', 1, 0, 'C', 1);
                $pdf->cell(60, 10, 'Correo electronico', 1, 0, 'C', 1);
                $pdf->cell(50, 10, 'Direccion', 1, 0, 'C', 1);
                $pdf->cell(40, 10, 'Fecha Compra', 1, 0, 'C', 1);
                $pdf->cell(50, 10, 'Producto', 1, 0, 'C', 1);
                $pdf->cell(30, 10, 'Precio', 1, 1, 'C', 1);
                
                // Se establece la fuente para los datos de los productos.
                $pdf->setFont('Arial', '', 11);
                $pdf->setTextColor(0, 0, 0); // Negro
                
                // Alternar colores para filas
                $fill = false;
                foreach ($dataProductos as $rowProducto) {
                    // Alternar color de fondo
                    $pdf->setFillColor($fill ? 230 : 255); // Gris claro y blanco
                    $pdf->cell(40, 10, $rowProducto['nombre_cliente'], 1, 0, 'C', $fill);
                    $pdf->cell(60, 10, $rowProducto['correo_cliente'], 1, 0, 'C', $fill);
                    $pdf->cell(50, 10, $rowProducto['direccion_cliente'], 1, 0, 'C', $fill);
                    $pdf->cell(40, 10, $rowProducto['fecha_venta'], 1, 0, 'C', $fill);
                    $pdf->cell(50, 10, $pdf->encodeString($rowProducto['nombre_producto']), 1, 0, 'C', $fill);
                    $pdf->cell(30, 10, $rowProducto['precio_unitario'], 1, 1, 'C', $fill);
                    $fill = !$fill;
                }
            } else {
                $pdf->cell(0, 10, $pdf->encodeString('No hay productos registrados'), 1, 1);
            }
            
            // Se llama implícitamente al método footer() y se envía el documento al navegador web.
            $pdf->output('I', 'clientes.pdf');
        } else {
            print('Cliente inexistente');
        }
    } else {
        print('Cliente incorrecto');
    }
} else {
    print('Debe seleccionar un cliente');
}