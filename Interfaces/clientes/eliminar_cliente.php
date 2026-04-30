<?php
$pdo = require '../../conexion/conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
        $stmt->execute([$id]);
        echo "<script>alert('Cliente eliminado correctamente'); window.location.href='clientes.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error al eliminar: " . $e->getMessage() . "'); window.location.href='clientes.php';</script>";
    }
}
?>