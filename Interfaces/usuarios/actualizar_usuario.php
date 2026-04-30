<?php
// 1. Conexión a la base de datos
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 2. Recibir datos del formulario
    $id             = $_POST['id']; // El ID que viene readonly
    $nombre         = $_POST['nombre'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena     = $_POST['contrasena']; // Puede venir vacío
    $fecha_registro = $_POST['fecha_registro'];
    $estado         = $_POST['estado'];
    $id_rol         = $_POST['id_rol'];

    try {
        // 3. Decidir si actualizamos la contraseña o no
        if (!empty($contrasena)) {
            // Si el usuario escribió una nueva contraseña
            $sql = "UPDATE usuarios SET 
                        nombre = :nom, 
                        nombre_usuario = :user, 
                        contrasena = :pass, 
                        fecha_registro = :fecha, 
                        estado = :est, 
                        id_rol = :rol 
                    WHERE id = :id";
            
            $params = [
                ':nom'   => $nombre,
                ':user'  => $nombre_usuario,
                ':pass'  => $contrasena,
                ':fecha' => $fecha_registro,
                ':est'   => $estado,
                ':rol'   => $id_rol,
                ':id'    => $id
            ];
        } else {
            // Si el campo contraseña está vacío, NO la tocamos en el SET
            $sql = "UPDATE usuarios SET 
                        nombre = :nom, 
                        nombre_usuario = :user, 
                        fecha_registro = :fecha, 
                        estado = :est, 
                        id_rol = :rol 
                    WHERE id = :id";
            
            $params = [
                ':nom'   => $nombre,
                ':user'  => $nombre_usuario,
                ':fecha' => $fecha_registro,
                ':est'   => $estado,
                ':rol'   => $id_rol,
                ':id'    => $id
            ];
        }

        // 4. Preparar y ejecutar
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // 5. Éxito: Mensaje y redirección
        echo "<script>
                alert('¡Datos actualizados correctamente!');
                window.location.href = 'usuarios.php';
              </script>";

    } catch (PDOException $e) {
        echo "Error al actualizar: " . $e->getMessage();
    }
} else {
    echo "Acceso no permitido.";
}
?>