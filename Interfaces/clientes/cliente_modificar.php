<?php
// 1. Conexión a la base de datos
$pdo = require '../../conexion/conexion.php';

// 2. Obtener el ID del cliente desde la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$cliente = null;

if ($id) {
    // 3. Buscar los datos actuales del cliente
    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->execute([$id]);
    $cliente = $stmt->fetch();
}

// Si el cliente no existe, regresamos a la lista
if (!$cliente) {
    header("Location: clientes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Cliente</title>
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Modificar Cliente';
            fetch('../componentes/header.html')
            .then(response => response.text())
            .then(html => {
                document.getElementById('header').innerHTML = html;
                document.getElementById('page-title').innerText = window.pageTitle;
            });
        </script>

        <main class="main-content form-page">
            <h2>Modificar Cliente</h2>
            <!-- Cambiamos el action para que envíe a un procesador -->
            <form class="module-form" action="actualizar_cliente.php" method="POST">
                
                <label>ID Cliente
                    <input type="text" name="id" value="<?php echo $cliente['id']; ?>" readonly>
                </label>
                
                <label>Nombre
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($cliente['nombre']); ?>" required>
                </label>
                
                <label>Teléfono
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono']); ?>">
                </label>
                
                <label>Dirección
                    <input type="text" name="direccion" value="<?php echo htmlspecialchars($cliente['direccion']); ?>">
                </label>
                
                <label>Correo
                    <input type="email" name="correo" value="<?php echo htmlspecialchars($cliente['correo']); ?>">
                </label>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <!-- Cambiamos el cancelar para que regrese a la tabla principal -->
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='clientes.php';">Cancelar</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>