<?php
require '../../conexion/verificar_sesion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Venta</title>
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Agregar Venta';
            fetch('../componentes/header.html')
            .then(response => response.text())
            .then(html => {
                document.getElementById('header').innerHTML = html;
                document.getElementById('page-title').innerText = window.pageTitle;
            });
        </script>

        <main class="main-content form-page">
            <h2>Agregar Venta</h2>
            <form class="module-form" action="registrar_venta.php" method="POST">
                <label>Fecha y Hora
                    <input type="datetime-local" name="fecha" required>
                </label>
                <label>Total
                    <input type="number" step="0.01" name="total" placeholder="Total" required>
                </label>
                <label>Tipo de Pago
                    <select name="tipo_pago" required>
                        <option value="">Seleccione</option>
                        <option value="contado">Contado</option>
                        <option value="credito">Crédito</option>
                    </select>
                </label>
                <label>Estado
                    <select name="estado" required>
                        <option value="">Seleccione</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </label>
                <label>ID Cliente
                    <input type="text" name="id_cliente" placeholder="ID Cliente" required>
                </label>
                <label>ID Pago
                    <input type="text" name="id_pago" placeholder="ID Pago" required>
                </label>
                <label>ID Entrega
                    <input type="text" name="Entregas_id" placeholder="ID Entrega" required>
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