<?php
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id               = $_POST['id'];
    $id_venta         = $_POST['id_venta'];
    $fecha_programada = $_POST['fecha_programada'];
    $estado           = $_POST['estado'];

    try {
        $sql = "UPDATE entregas SET 
                    id_venta = :id_venta, 
                    fecha_programada = :fecha_programada, 
                    estado = :estado 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_venta'         => $id_venta,
            ':fecha_programada' => $fecha_programada,
            ':estado'           => $estado,
            ':id'               => $id
        ]);

        echo "<script>
                alert('Entrega actualizada correctamente');
                window.location.href = 'entregas.php';
              </script>";

    } catch (PDOException $e) {
        echo "Error al actualizar la entrega: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
?>