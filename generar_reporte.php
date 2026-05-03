<?php
// Forzar visualización de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/tcpdf/tcpdf.php');

$pdf = new TCPDF();
$pdf->SetCreator('Sistema');
$pdf->SetTitle('Reporte de Ventas');
$pdf->AddPage();

$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'REPORTE DE VENTAS', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 10, 'Reporte generado automaticamente desde la bitacora', 0, 1, 'C');
$pdf->Ln(10);

// Contenido de la tabla
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
    <tbody>
        <tr>
            <td>1</td>
            <td>' . date('Y-m-d') . '</td>
            <td>$ 1,500.00</td>
            <td>101</td>
        </tr>
    </tbody>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Guardar
$nombre_archivo = 'reporte_' . time() . '.pdf';
$ruta = __DIR__ . '/pdfs_generados/' . $nombre_archivo;

if (!file_exists(__DIR__ . '/pdfs_generados/')) {
    mkdir(__DIR__ . '/pdfs_generados/', 0777, true);
}

$pdf->Output($ruta, 'F');

// Redirigir de vuelta al panel para no dejar la página en blanco
header("Location: reportes.php");
exit();
?>