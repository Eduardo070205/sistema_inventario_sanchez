<?php
// 1. Incluir la conexión a la base de datos
$pdo = require '../../conexion/conexion.php';

// 2. Verificar que se haya recibido el ID por la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // 3. Preparar la consulta DELETE
        $stmt = $pdo->prepare("DELETE FROM entregas WHERE id = ?");
        $stmt->execute([$id]);
        
        // 4. Mostrar mensaje de confirmación y redirigir a la lista
        echo "<script>
                alert('Entrega eliminada correctamente'); 
                window.location.href='entregas.php';
              </script>";
              
    } catch (PDOException $e) {
        // 5. En caso de error (por ejemplo, si está vinculada a otra tabla)
        echo "<script>
                alert('Error al eliminar la entrega: " . $e->getMessage() . "'); 
                window.location.href='entregas.php';
              </script>";
    }
} else {
    // Si no viene el ID, redirigir
    header("Location: entregas.php");
    exit();
}
?>