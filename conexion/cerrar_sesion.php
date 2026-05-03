PHP
<?php
// 1. Iniciamos la sesión para poder acceder a las variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Vaciamos el arreglo $_SESSION
$_SESSION = array();

// 3. Si se utilizan cookies para la sesión, las eliminamos del navegador
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Destruimos la sesión en el servidor
session_destroy();

// 5. Redirigimos al usuario al login
header("Location: ../interfaces/iniciar_sesion.php");
exit();
?>