<?php
require '../../conexion/verificar_sesion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Entrega</title>
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Agregar Entrega';
            fetch('../componentes/header.html')
            .then(response => response.text())
            .then(html => {
                document.getElementById('header').innerHTML = html;
                document.getElementById('page-title').innerText = window.pageTitle;
            });
        </script>

        <main class="main-content form-page">
            <h2>Agregar Entrega</h2>
            <form class="module-form" action="registrar_entrega.php" method="POST">
                
                <label>ID Venta
                    <input type="text" name="id_venta" placeholder="ID de la venta" required>
                </label>
                
                <label>Fecha Programada
                    <input type="date" name="fecha_programada" required>
                </label>
                
                <label>Estado
                    <select name="estado" required>
                        <option value="">Seleccione</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="enviado">Enviado</option>
                        <option value="entregado">Entregado</option>
                    </select>
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