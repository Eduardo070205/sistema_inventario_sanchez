<?php
// 1. Incluir la conexión
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Recibir los datos del formulario
    $nombre         = $_POST['nombre'];
    $codigo         = $_POST['codigo'];
    $descripcion    = $_POST['descripcion'];
    $precio         = $_POST['precio'];
    $Inventarios_id = $_POST['Inventarios_id'];

    try {
        // 3. Preparar el SQL con la nueva columna
        $sql = "INSERT INTO productos (nombre, codigo, descripcion, precio, Inventarios_id) 
                VALUES (:nombre, :codigo, :descripcion, :precio, :inventarios_id)";
        
        $stmt = $pdo->prepare($sql);

        // 4. Ejecutar
        $stmt->execute([
            ':nombre'         => $nombre,
            ':codigo'         => $codigo,
            ':descripcion'    => $descripcion,
            ':precio'         => $precio,
            ':inventarios_id' => $Inventarios_id
        ]);

        // 5. Redirección exitosa
        echo "<script>
                alert('Producto registrado exitosamente');
                window.location.href = 'productos.php';
              </script>";
              
    } catch (PDOException $e) {
        echo "Error al registrar producto: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
?>