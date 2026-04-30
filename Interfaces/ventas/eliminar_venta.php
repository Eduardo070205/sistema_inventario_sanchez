<?php
$pdo = require '../../conexion/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM ventas WHERE id = ?");
        $stmt->execute([$id]);
        echo "<script>alert('Venta eliminada correctamente'); window.location.href='ventas.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error al eliminar la venta: " . $e->getMessage() . "'); window.location.href='ventas.php';</script>";
    }
}
?>