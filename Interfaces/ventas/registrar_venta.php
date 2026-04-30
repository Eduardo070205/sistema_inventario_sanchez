<?php
// 1. Incluimos la conexión
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Recibir los datos del formulario
    $fecha       = $_POST['fecha'];
    $total       = $_POST['total'];
    $tipo_pago   = $_POST['tipo_pago'];
    $estado      = $_POST['estado'];
    $id_cliente  = $_POST['id_cliente'];
    $id_pago     = $_POST['id_pago'];
    $Entregas_id = $_POST['Entregas_id'];

    try {
        // 3. Preparar la consulta SQL
        $sql = "INSERT INTO ventas (fecha, total, tipo_pago, estado, id_cliente, id_pago, Entregas_id) 
                VALUES (:fecha, :total, :tipo_pago, :estado, :id_cliente, :id_pago, :Entregas_id)";
        
        $stmt = $pdo->prepare($sql);

        // 4. Ejecutar la consulta
        $stmt->execute([
            ':fecha'       => $fecha,
            ':total'       => $total,
            ':tipo_pago'   => $tipo_pago,
            ':estado'      => $estado,
            ':id_cliente'  => $id_cliente,
            ':id_pago'     => $id_pago,
            ':Entregas_id' => $Entregas_id
        ]);

        // 5. Éxito: Mensaje y redirección
        echo "<script>
                alert('Venta guardada exitosamente');
                window.location.href = 'ventas.php'; 
              </script>";

    } catch (PDOException $e) {
        // Error: Mostrar qué pasó
        echo "Error al registrar venta: " . $e->getMessage();
    }

} else {
    echo "Acceso denegado.";
}
?>