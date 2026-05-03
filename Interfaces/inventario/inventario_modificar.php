<?php
require '../../conexion/verificar_sesion.php';
?>

<?php
// 1. Incluir la conexión a la base de datos
$pdo = require '../../conexion/conexion.php';

// 2. Obtener el ID del inventario desde la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$inventario = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM inventarios WHERE id = ?");
    $stmt->execute([$id]);
    $inventario = $stmt->fetch();
}

// Si el inventario no existe, redirigimos a la tabla principal
if (!$inventario) {
    header("Location: inventarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Inventario</title>
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Modificar Inventario';
            fetch('../componentes/header.html')
            .then(response => response.text())
            .then(html => {
                document.getElementById('header').innerHTML = html;
                document.getElementById('page-title').innerText = window.pageTitle;
            });
        </script>

        <main class="main-content form-page">
            <h2>Modificar Inventario</h2>
            <form class="module-form" action="actualizar_inventario.php" method="POST">
                
                <label>ID Inventario
                    <input type="text" name="id" value="<?php echo $inventario['id']; ?>" readonly>
                </label>
                
                <label>ID Sucursal
                    <input type="text" name="id_sucursal" value="<?php echo htmlspecialchars($inventario['id_sucursal']); ?>" required>
                </label>
                
                <label>ID Producto
                    <input type="text" name="id_producto" value="<?php echo htmlspecialchars($inventario['id_producto']); ?>" required>
                </label>
                
                <label>Cantidad Disponible
                    <input type="number" name="cantidad_disponible" value="<?php echo htmlspecialchars($inventario['cantidad_disponible']); ?>" required>
                </label>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='inventarios.php';">Cancelar</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>