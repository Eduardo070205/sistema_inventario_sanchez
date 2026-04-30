<?php
// 1. Incluimos la conexión (Asegúrate de que la ruta sea la correcta)
$pdo = require '../../conexion/conexion.php';

// 2. Verificamos que el ID venga en la URL (ej: eliminar_usuario.php?id=3)
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // 3. Preparamos la sentencia de eliminación
        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        
        // 4. Ejecutamos pasando el ID
        $stmt->execute([':id' => $id]);

        // 5. Si se eliminó con éxito, avisamos y redirigimos
        echo "<script>
                alert('Usuario eliminado correctamente.');
                window.location.href = 'usuarios.php';
              </script>";
    } catch (PDOException $e) {
        // En caso de error (por ejemplo, si el ID está siendo usado en otra tabla)
        echo "<script>
                alert('No se pudo eliminar el usuario: " . $e->getMessage() . "');
                window.location.href = 'usuarios.php';
              </script>";
    }
} else {
    // Si alguien entra al archivo sin un ID
    header("Location: usuarios.php");
    exit();
}
?>