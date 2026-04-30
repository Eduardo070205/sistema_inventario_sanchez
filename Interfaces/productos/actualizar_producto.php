<?php
// 1. Conexión a la base de datos
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Recibir los datos
    $id             = $_POST['id'];
    $nombre         = $_POST['nombre'];
    $codigo         = $_POST['codigo'];
    $descripcion    = $_POST['descripcion'];
    $precio         = $_POST['precio'];
    $Inventarios_id = $_POST['Inventarios_id'];

    try {
        // 3. Preparar la consulta SQL
        $sql = "UPDATE productos SET 
                    nombre = :nombre, 
                    codigo = :codigo, 
                    descripcion = :descripcion, 
                    precio = :precio,
                    Inventarios_id = :inventarios_id
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre'         => $nombre,
            ':codigo'         => $codigo,
            ':descripcion'    => $descripcion,
            ':precio'         => $precio,
            ':inventarios_id' => $Inventarios_id,
            ':id'             => $id
        ]);

        // 4. Éxito y redirección
        echo "<script>
                alert('Producto actualizado con éxito');
                window.location.href = 'productos.php';
              </script>";

    } catch (PDOException $e) {
        echo "Error al actualizar el producto: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
?>