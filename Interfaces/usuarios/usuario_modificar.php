<?php
// 1. Conexión
$pdo = require '../../conexion/conexion.php';

// 2. Obtener el ID de la URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$usuario = null;

if ($id) {
    // 3. Buscar los datos del usuario
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    $usuario = $stmt->fetch();
}

// Si no hay ID o el usuario no existe, regresamos a la lista
if (!$usuario) {
    header("Location: usuarios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
    <link rel="stylesheet" href="../css/formularios.css">
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Modificar Usuario';
            fetch('../componentes/header.html')
            .then(response => response.text())
            .then(html => {
                document.getElementById('header').innerHTML = html;
                document.getElementById('page-title').innerText = window.pageTitle;
            });
        </script>

        <main class="main-content form-page">
            <h2>Modificar Usuario</h2>
            <form class="module-form" action="actualizar_usuario.php" method="POST">
                <!-- El ID es vital para el WHERE en el UPDATE, lo dejamos readonly -->
                <label>ID Usuario
                    <input type="text" name="id" value="<?php echo $usuario['id']; ?>" readonly>
                </label>

                <label>Nombre
                    <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                </label>

                <label>Nombre de Usuario
                    <input type="text" name="nombre_usuario" value="<?php echo htmlspecialchars($usuario['nombre_usuario']); ?>">
                </label>

                <label>Contraseña
                    <input type="password" name="contrasena" placeholder="Nueva contraseña (dejar vacío para no cambiar)">
                </label>

                <label>Fecha de Registro
                    <input type="date" name="fecha_registro" value="<?php echo $usuario['fecha_registro']; ?>">
                </label>

                <label>Estado
                    <select name="estado">
                        <option value="activo" <?php echo ($usuario['estado'] == 'activo') ? 'selected' : ''; ?>>Activo</option>
                        <option value="inactivo" <?php echo ($usuario['estado'] == 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                    </select>
                </label>

                <label>ID Rol
                    <input type="text" name="id_rol" value="<?php echo $usuario['id_rol']; ?>">
                </label>

                <div class="form-buttons">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='usuarios.php';">Cancelar</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>