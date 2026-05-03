<?php
require '../conexion/verificar_sesion.php';
?>

<!DOCTYPE html>
<html lang="es">
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
                    <a class="icon-btn" title="Inicio" href="home.php">
                        <img src="img/home.png" alt="Botón Inicio" class="icon-btn">
                    </a>
                    <a class="icon-btn" title="Salir" href="../conexion/cerrar_sesion.php">
                        <img src="img/exit.png" alt="Botón Salir" class="icon-btn">
                    </a>
                </div>
            </div>
        </header>

        <main class="main-content home-content">
            <div class="welcome-card">
                <img src="img/user2.png" alt="User Avatar" class="avatar">
                <div class="welcome-text">
                    <h1>Home Page</h1>
                    <p><?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
                </div>
            </div>
            
            <div class="grid-actions">
                <a href="ventas/ventas.php" class="action-card">Ventas</a>
                <a href="entregas/entregas.php" class="action-card">Entregas</a>
                <a href="clientes/clientes.php" class="action-card">Clientes</a>
                <a href="inventario/inventario.php" class="action-card">Inventario</a>
                
                <?php 
                // Verificar si el rol es de administrador (por ejemplo, id_rol = '1' o su equivalente)
                $esAdmin = isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == '1'; // Cambia el valor '1' si es necesario
                
                if ($esAdmin) { 
                ?>
                    <a href="usuarios/usuarios.php" class="action-card">Usuario</a>
                    <a href="../reportes.php" class="action-card">Reportes</a>
                    <a href="productos/productos.php" class="action-card">Productos</a>
                <?php } ?>
            </div>
        </main>

    </div>

</body>
</html>