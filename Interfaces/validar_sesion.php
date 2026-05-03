<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = require '../conexion/conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibimos los datos del formulario
    $usuario_ingresado = $_POST['usuario'];
    $contraseña        = $_POST['contraseña'];

    try {
        // Buscamos por la columna correcta de tu tabla
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :usuario");
        $stmt->execute([':usuario' => $usuario_ingresado]);
        $user = $stmt->fetch();

        if ($user) {
            // Verificamos la contraseña (texto plano en este caso)
            if ($user['contraseña'] === $contraseña) {
                
                // Guardamos el nombre de usuario y el ID en la sesión
                $_SESSION['usuario'] = $user['nombre_usuario']; // <- Corregido aquí
                $_SESSION['id_usuario'] = $user['id'];
                
                header("Location: home.php");
                exit();
            } else {
                echo "<script>
                        alert('Contraseña incorrecta.');
                        window.location.href='iniciar_sesion.php';
                      </script>";
                exit();
            }
        } else {
            echo "<script>
                    alert('Usuario no encontrado.');
                    window.location.href='iniciar_sesion.php';
                  </script>";
            exit();
        }

    } catch (PDOException $e) {
        echo "Error en el servidor: " . $e->getMessage();
    }
}
?>