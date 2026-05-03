<?php
// 1. Incluimos la conexión
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Recibir los datos del formulario
    $id_sucursal         = $_POST['id_sucursal'];
    $id_producto         = $_POST['id_producto'];
    $cantidad_disponible = $_POST['cantidad_disponible'];

    try {
        // 3. Preparar la consulta SQL
        $sql = "INSERT INTO inventarios (cantidad_disponible, id_sucursal, id_producto) 
                VALUES (:cantidad_disponible, :id_sucursal, :id_producto)";
        
        $stmt = $pdo->prepare($sql);

        // 4. Ejecutar la consulta
        $stmt->execute([
            ':cantidad_disponible' => $cantidad_disponible,
            ':id_sucursal'         => $id_sucursal,
            ':id_producto'         => $id_producto
        ]);

        // 5. Éxito: Mensaje y redirección
        echo "<script>
                alert('Inventario guardado exitosamente');
                window.location.href = 'inventario.php'; 
              </script>";

    } catch (PDOException $e) {
        // Error: Mostrar qué pasó
        echo "Error al registrar el inventario: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
?>