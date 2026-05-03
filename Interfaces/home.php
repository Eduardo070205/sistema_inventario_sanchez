<?php
// Llama siempre a la sesión desde el directorio raíz del servidor
require $_SERVER['DOCUMENT_ROOT'] . '/Programa/conexion/verificar_sesion.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/header.css">
</head>
<body>

    <div class="container">

        <header class="header">
            <div class="header-content">
                <h1 id="page-title">Home</h1>
                <div class="header-icons">
                    <a class="icon-btn" title="Inicio">
                        <img src="img/home.png" alt="Botón Inicio" class="icon-btn">
                    </a>
                    <a class="icon-btn" title="Salir" href="iniciar_sesion.php">
                        <img src="img/exit.png" alt="Botón Salir" class="icon-btn">
                    </a>
                </div>
            </div>
        </header>

        <main class="main-content home-content">
            <div class="welcome-card">
                <img src="img/user2.png" alt="User Avatar" class="avatar"></img>
                <div class="welcome-text">
                    <h1>Home Page</h1>
                    <p>Usuario ####</p>
                </div>
            </div>
            <div class="grid-actions">
                <a href="ventas/ventas.php" class="action-card">Ventas</a>
                <a href="usuarios/usuarios.php" class="action-card">Usuario</a>
                <a href="reportes.html" class="action-card">Reportes</a>
                <a href="entregas/entregas.php" class="action-card">Entregas</a>
                <a href="clientes/clientes.php" class="action-card">Clientes</a>
                <a href="inventario/inventario.php" class="action-card">Inventario</a>
                <a href="productos/productos.php" class="action-card">Productos</a>
  
            </div>
        </main>

    </div>


</body>
</html>