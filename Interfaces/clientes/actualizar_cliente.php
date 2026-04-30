<?php
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id        = $_POST['id'];
    $nombre    = $_POST['nombre'];
    $telefono  = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $correo    = $_POST['correo'];

    try {
        $sql = "UPDATE clientes SET 
                    nombre = :nom, 
                    telefono = :tel, 
                    direccion = :dir, 
                    correo = :cor 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nombre,
            ':tel' => $telefono,
            ':dir' => $direccion,
            ':cor' => $correo,
            ':id'  => $id
        ]);

        echo "<script>
                alert('Cliente actualizado con éxito');
                window.location.href = 'clientes.php';
              </script>";

    } catch (PDOException $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
} else {
    echo "Acceso denegado.";
}
?>