<?php
// 1. Importamos la conexión (el return $pdo hace que la variable $pdo tenga la conexión)
$pdo = require '../../conexion/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Recogemos los datos (Asegúrate que en el HTML diga name="contrasena")
    $nombre         = $_POST['nombre'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña     = $_POST['contraseña']; 
    $fecha_registro = $_POST['fecha_registro'];
    $estado         = $_POST['estado'];
    $id_rol         = $_POST['id_rol'];

    try {
        // 3. Preparar la consulta con PDO (usamos marcadores :nombre)
        $sql = "INSERT INTO usuarios (nombre, nombre_usuario, contraseña, fecha_registro, estado, id_rol) 
                VALUES (:nombre, :usuario, :pass, :fecha, :estado, :rol)";
        
        $stmt = $pdo->prepare($sql);

        // 4. Ejecutar pasando los datos en un arreglo
        $stmt->execute([
            ':nombre'  => $nombre,
            ':usuario' => $nombre_usuario,
            ':pass'    => $contraseña,
            ':fecha'   => $fecha_registro,
            ':estado'  => $estado,
            ':rol'     => $id_rol
        ]);

        echo "<script>
                alert('¡Usuario registrado con éxito!');
                window.location.href='usuarios.php';
              </script>";

    } catch (PDOException $e) {
        // Si hay un error (ej. la tabla no existe), aquí te lo dirá claramente
        echo "Error en la base de datos: " . $e->getMessage();
    }

} else {
    echo "Acceso denegado.";
}