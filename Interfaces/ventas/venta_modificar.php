<?php
require '../../conexion/verificar_sesion.php';
$pdo = require '../../conexion/conexion.php';

// Obtener el ID de la venta seleccionada desde la URL
$id_venta = $_GET['id'] ?? null;

// Consultar los datos actuales de la venta para rellenar el formulario
$venta = null;
if ($id_venta) {
    $stmt = $pdo->prepare("SELECT * FROM ventas WHERE id = :id");
    $stmt->execute([':id' => $id_venta]);
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Si no se encuentra el registro, redirigir
if (!$venta) {
    // Puedes quitar este if si prefieres manejarlo de otra forma, pero es preventivo.
    // echo "<script>alert('Venta no encontrada'); window.location.href='ventas.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Venta</title>
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Modificar Venta';
            fetch('../componentes/header.html')
            .then(response => response.text())
            .then(html => {
                document.getElementById('header').innerHTML = html;
                document.getElementById('page-title').innerText = window.pageTitle;
            });
        </script>

        <main class="main-content form-page">
            <h2>Modificar Venta</h2>
            <form class="module-form" action="actualizar_venta.php" method="POST">
                
                <label>ID Venta
                    <input type="text" name="id" value="<?php echo htmlspecialchars($venta['id'] ?? ''); ?>" readonly>
                </label>
                
                <label>Fecha
                    <input type="date" name="fecha" value="<?php echo htmlspecialchars($venta['fecha'] ?? ''); ?>">
                </label>
                
                <label>Total
                    <input type="number" step="0.01" name="total" value="<?php echo htmlspecialchars($venta['total'] ?? ''); ?>">
                </label>
                
                <label>Tipo de Pago
                    <select name="tipo_pago">
                        <option value="contado" <?php echo (isset($venta['tipo_pago']) && $venta['tipo_pago'] == 'contado') ? 'selected' : ''; ?>>Contado</option>
                        <option value="credito" <?php echo (isset($venta['tipo_pago']) && $venta['tipo_pago'] == 'credito') ? 'selected' : ''; ?>>Crédito</option>
                    </select>
                </label>
                
                <label>Estado
                    <select name="estado">
                        <option value="pendiente" <?php echo (isset($venta['estado']) && $venta['estado'] == 'pendiente') ? 'selected' : ''; ?>>Pendiente</option>
                        <option value="completada" <?php echo (isset($venta['estado']) && $venta['estado'] == 'completada') ? 'selected' : ''; ?>>Completada</option>
                        <option value="cancelada" <?php echo (isset($venta['estado']) && $venta['estado'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                    </select>
                </label>
                
                <label>ID Cliente
                    <input type="text" name="id_cliente" value="<?php echo htmlspecialchars($venta['id_cliente'] ?? ''); ?>">
                </label>
                
                <label>ID Pago
                    <input type="text" name="id_pago" value="<?php echo htmlspecialchars($venta['id_pago'] ?? ''); ?>">
                </label>

                <label>ID Entrega
                    <input type="text" name="Entregas_id" value="<?php echo htmlspecialchars($venta['Entregas_id'] ?? ''); ?>">
                </label>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Cancelar</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>