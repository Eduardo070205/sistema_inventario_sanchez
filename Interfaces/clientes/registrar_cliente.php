<?php
// 1. Incluimos la conexión
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Recibir los datos del formulario
    $nombre    = $_POST['nombre'];
    $telefono  = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $correo    = $_POST['correo'];

    try {
        // 3. Preparar la consulta SQL
        $sql = "INSERT INTO clientes (nombre, telefono, direccion, correo) 
                VALUES (:nom, :tel, :dir, :cor)";
        
        $stmt = $pdo->prepare($sql);

        // 4. Ejecutar la consulta
        $stmt->execute([
            ':nom' => $nombre,
            ':tel' => $telefono,
            ':dir' => $direccion,
            ':cor' => $correo
        ]);

        // 5. Éxito: Mensaje y redirección
        echo "<script>
                alert('Cliente guardado exitosamente');
                window.location.href = 'clientes.php'; 
              </script>";

    } catch (PDOException $e) {
        // Error: Mostrar qué pasó
        echo "Error al registrar cliente: " . $e->getMessage();
    }

} else {
    echo "Acceso denegado.";
}
?>