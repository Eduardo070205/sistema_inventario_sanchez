<?php
// 1. Conexión a la base de datos
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Recibir los datos con validación para evitar errores
    $id          = $_POST['id'] ?? null;
    $fecha       = $_POST['fecha'] ?? null;
    $total       = $_POST['total'] ?? null;
    $tipo_pago   = $_POST['tipo_pago'] ?? null;
    $estado      = $_POST['estado'] ?? null;
    $id_cliente  = $_POST['id_cliente'] ?? null;
    $id_pago     = $_POST['id_pago'] ?? null;
    $Entregas_id = $_POST['Entregas_id'] ?? null;

    // Verificar que el ID principal exista
    if (!$id) {
        echo "<script>alert('Error: ID de venta no especificado.'); window.location.href = 'ventas.php';</script>";
        exit();
    }

    try {
        // 3. Preparar el SQL con los nuevos datos
        $sql = "UPDATE ventas SET 
                    fecha = :fecha, 
                    total = :total, 
                    tipo_pago = :tipo_pago, 
                    estado = :estado, 
                    id_cliente = :id_cliente, 
                    id_pago = :id_pago, 
                    Entregas_id = :Entregas_id 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        // Ejecutar los datos
        $stmt->execute([
            ':fecha'       => $fecha,
            ':total'       => $total,
            ':tipo_pago'   => $tipo_pago,
            ':estado'      => $estado,
            ':id_cliente'  => $id_cliente,
            ':id_pago'     => $id_pago,
            ':Entregas_id' => $Entregas_id,
            ':id'          => $id
        ]);

        // 4. Mensaje y redirección
        echo "<script>
                alert('Venta actualizada correctamente');
                window.location.href = 'ventas.php';
              </script>";

    } catch (PDOException $e) {
        echo "Error al actualizar la venta: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
?>