<?php
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_venta         = $_POST['id_venta'];
    $fecha_programada = $_POST['fecha_programada'];
    $estado           = $_POST['estado'];

    try {
        $sql = "INSERT INTO entregas (fecha_programada, estado, id_venta) 
                VALUES (:fecha_programada, :estado, :id_venta)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':fecha_programada' => $fecha_programada,
            ':estado'           => $estado,
            ':id_venta'         => $id_venta
        ]);

        echo "<script>
                alert('Entrega registrada exitosamente');
                window.location.href = 'entregas.php';
              </script>";

    } catch (PDOException $e) {
        echo "Error al registrar la entrega: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
?>