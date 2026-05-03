<?php
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id                  = $_POST['id'];
    $id_sucursal         = $_POST['id_sucursal'];
    $id_producto         = $_POST['id_producto'];
    $cantidad_disponible = $_POST['cantidad_disponible'];

    try {
        $sql = "UPDATE inventarios SET 
                    id_sucursal = :id_sucursal, 
                    id_producto = :id_producto, 
                    cantidad_disponible = :cantidad_disponible 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_sucursal'         => $id_sucursal,
            ':id_producto'         => $id_producto,
            ':cantidad_disponible' => $cantidad_disponible,
            ':id'                  => $id
        ]);

        echo "<script>
                alert('Inventario actualizado correctamente');
                window.location.href = 'inventarios.php';
              </script>";

    } catch (PDOException $e) {
        echo "Error al actualizar el inventario: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
?>