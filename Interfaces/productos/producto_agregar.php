<?php
require '../../conexion/verificar_sesion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Agregar Producto';
            fetch('../componentes/header.html')
            .then(response => response.text())
            .then(html => {
                document.getElementById('header').innerHTML = html;
                document.getElementById('page-title').innerText = window.pageTitle;
            });
        </script>

        <main class="main-content form-page">
            <h2>Agregar Producto</h2>
            <form class="module-form" action="registrar_producto.php" method="POST">
                
                <label>Nombre
                    <input type="text" name="nombre" placeholder="Nombre del producto" required>
                </label>
                
                <label>Código
                    <input type="text" name="codigo" placeholder="Código del producto" required>
                </label>
                
                <label>Descripción
                    <textarea name="descripcion" placeholder="Descripción"></textarea>
                </label>
                
                <label>Precio
                    <input type="number" step="0.01" name="precio" placeholder="Precio" required>
                </label>
                
                <label>ID Inventario
                    <input type="text" name="Inventarios_id" placeholder="ID del inventario" required>
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