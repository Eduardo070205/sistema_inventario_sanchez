<?php
// Forzamos el inicio de sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h3>Diagnóstico de Sesión:</h3>";
echo "ID de sesión actual: " . session_id() . "<br><br>";

echo "Contenido de \$_SESSION:<br>";
if (!empty($_SESSION)) {
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
} else {
    echo "<b>La variable \$_SESSION está vacía. La sesión no se está guardando.</b>";
}
?>