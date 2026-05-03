<?php
require '../../conexion/verificar_sesion.php';
?>

<?php
// 1. Incluir la conexión a la base de datos
$pdo = require '../../conexion/conexion.php';

// 2. Obtener el ID de la entrega desde la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$entrega = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM entregas WHERE id = ?");
    $stmt->execute([$id]);
    $entrega = $stmt->fetch();
}

// Si la entrega no existe en la base de datos, redirigimos a la tabla principal
if (!$entrega) {
    header("Location: entregas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Entrega</title>
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Modificar Entrega';
            fetch('../componentes/header.html')
            .then(response => response.text())
            .then(html => {
                document.getElementById('header').innerHTML = html;
                document.getElementById('page-title').innerText = window.pageTitle;
            });
        </script>

        <main class="main-content form-page">
            <h2>Modificar Entrega</h2>
            <form class="module-form" action="actualizar_entrega.php" method="POST">
                
                <label>ID Entrega
                    <input type="text" name="id" value="<?php echo $entrega['id']; ?>" readonly>
                </label>
                
                <label>ID Venta
                    <input type="text" name="id_venta" value="<?php echo htmlspecialchars($entrega['id_venta']); ?>" required>
                </label>
                
                <label>Fecha Programada
                    <input type="date" name="fecha_programada" value="<?php echo htmlspecialchars($entrega['fecha_programada']); ?>" required>
                </label>
                
                <label>Estado
                    <select name="estado" required>
                        <option value="pendiente" <?php echo ($entrega['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="enviado" <?php echo ($entrega['estado'] == 'enviado') ? 'selected' : ''; ?>>Enviado</option>
                        <option value="entregado" <?php echo ($entrega['estado'] == 'entregado') ? 'selected' : ''; ?>>Entregado</option>
                    </select>
                </label>
                
                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='entregas.php';">Cancelar</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>