<?php
$pdo = require '../../conexion/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        echo "<script>alert('Producto eliminado correctamente'); window.location.href='productos.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error al eliminar el producto: " . $e->getMessage() . "'); window.location.href='productos.php';</script>";
    }
}
?>