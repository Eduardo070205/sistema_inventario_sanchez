<?php
require '../../conexion/verificar_sesion.php';
?>

<?php
// 1. Conexión a la base de datos
$pdo = require '../../conexion/conexion.php';

// 2. Obtener el ID del producto desde la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$producto = null;

if ($id) {
    // 3. Buscar los datos actuales del producto
    $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);
    $producto = $stmt->fetch();
}

// Si el producto no existe, regresamos a la lista
if (!$producto) {
    header("Location: productos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Producto</title>
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Modificar Producto';
            fetch('../componentes/header.html')
            .then(response => response.text())
            .then(html => {
                document.getElementById('header').innerHTML = html;
                document.getElementById('page-title').innerText = window.pageTitle;
            });
        </script>

        <main class="main-content form-page">
            <h2>Modificar Producto</h2>
            <form class="module-form" action="actualizar_producto.php" method="POST">
                
                <label>ID Producto
                    <input type="text" name="id" value="<?php echo $producto['id']; ?>" readonly>
                </label>
                
                <label>Nombre
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>" required>
                </label>
                
                <label>Código
                    <input type="text" name="codigo" value="<?php echo htmlspecialchars($producto['codigo']); ?>" required>
                </label>
                
                <label>Descripción
                    <textarea name="descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></textarea>
                </label>
                
                <label>Precio
                    <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>" required>
                </label>

                <label>ID Inventario
                    <input type="text" name="Inventarios_id" value="<?php echo htmlspecialchars($producto['Inventarios_id']); ?>" required>
                </label>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='productos.php';">Cancelar</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>