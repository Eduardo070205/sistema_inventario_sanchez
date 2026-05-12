<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


$pdo = require __DIR__ . '/conexion/conexion.php';

require_once(__DIR__ . '/tcpdf/tcpdf.php');

$pdf = new TCPDF();
$pdf->SetCreator('Sistema');
$pdf->SetTitle('Reporte de Ventas');
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'REPORTE DE VENTAS', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 10, 'Reporte generado automaticamente desde el sistema', 0, 1, 'C');
$pdf->Ln(10);


try {
    $query = $pdo->query("SELECT id, fecha, total, id_cliente FROM ventas ORDER BY fecha DESC");
    $ventas = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $ventas = [];
}

// 3. Generar el contenido HTML de forma dinámica
$html = '
<table border="1" cellpadding="5">
    <thead>
        <tr style="background-color:#f2f2f2; font-weight:bold;">
            <th>ID Venta</th>
            <th>Fecha</th>
            <th>Total</th>
            <th>ID Cliente</th>
        </tr>
    </thead>
    <tbody>';

if (count($ventas) > 0) {
    foreach ($ventas as $venta) {
        $html .= '
        <tr>
            <td>' . htmlspecialchars($venta['id']) . '</td>
            <td>' . htmlspecialchars($venta['fecha']) . '</td>
            <td>$ ' . number_format($venta['total'], 2) . '</td>
            <td>' . htmlspecialchars($venta['id_cliente']) . '</td>
        </tr>';
    }
} else {
    $html .= '
        <tr>
            <td colspan="4" style="text-align:center;">No hay ventas registradas.</td>
        </tr>';
}

$html .= '
    </tbody>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// 4. Guardar el archivo
$nombre_archivo = 'reporte_' . time() . '.pdf';
$ruta = __DIR__ . '/pdfs_generados/' . $nombre_archivo;

if (!file_exists(__DIR__ . '/pdfs_generados/')) {
    mkdir(__DIR__ . '/pdfs_generados/', 0777, true);
}

$pdf->Output($ruta, 'F');

// Redirigir de vuelta al panel
header("Location: reportes.php");
exit();
?>