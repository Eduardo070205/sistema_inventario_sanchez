<?php

/**
 * Prueba de conexión a la base de datos usando el archivo conexion/conexion.php.
 */

echo "Prueba de Conexión\n";

$connection = require __DIR__ . '/conexion/conexion.php';

try {
    $stmt = $connection->query('SELECT DATABASE() AS db');
    $row = $stmt->fetch();

    if (!$row) {
        throw new RuntimeException('No se pudo obtener el nombre de la base de datos.');
    }

    echo "✅ Conexión exitosa\n";
    echo "Base de datos conectada: " . $row['db'] . "\n";
} catch (Exception $e) {
    echo '❌ Error en la consulta: ' . $e->getMessage() . "\n";
    exit(1);
}

$connection = null;
