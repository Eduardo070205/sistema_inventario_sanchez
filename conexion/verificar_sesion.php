<?php
// 1. Iniciamos la sesión de forma segura
if (session_status() === PHP_SESSION_NONE) {
    // Configuramos los parámetros de la cookie para que sea accesible en todo el sitio
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    session_start();
}

// 2. Verificamos si la variable de sesión 'usuario' existe
if (!isset($_SESSION['usuario'])) {
    // Si no ha iniciado sesión, lo redirigimos al login
    // Nota: Si tu proyecto está en una subcarpeta (ej. /mi_proyecto/), agrégalo al inicio.
    echo "<script>
            alert('Acceso denegado. Por favor, inicie sesión.');
            window.location.href = '/Programa/Interfaces/iniciar_sesion.php'; 
          </script>";
    exit();
}
?>