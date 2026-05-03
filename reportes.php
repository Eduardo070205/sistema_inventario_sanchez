<?php
// Validación de sesión
require $_SERVER['DOCUMENT_ROOT'] . '/Programa/conexion/verificar_sesion.php';

if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != '1') { 
    echo "<script>
            alert('Acceso denegado: Módulo exclusivo para administradores.');
            window.location.href = '../home.php';
          </script>";
    exit();
}

$carpeta_pdfs = $_SERVER['DOCUMENT_ROOT'] . '/Programa/pdfs_generados/';

// Eliminar archivo si se solicita
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
    $archivo = trim($_POST['nombre_archivo']);
    if (!empty($archivo)) {
        $ruta_archivo = $carpeta_pdfs . $archivo;
        if (file_exists($ruta_archivo)) {
            unlink($ruta_archivo);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generador de Reportes</title>
    <link rel="stylesheet" href="Interfaces/css/reportes.css">
    <link rel="stylesheet" href="Interfaces/css/header.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="header-content">
                <h1>Generador de Reportes</h1>
                <div class="header-icons">
                    <a class="icon-btn" title="Inicio" href="Interfaces/home.php">
                        <img src="Interfaces/img/home.png" alt="Botón Inicio" class="icon-btn">
                    </a>
                    <a class="icon-btn" title="Salir" href="/Programa/conexion/cerrar_sesion.php">
                        <img src="Interfaces/img/exit.png" alt="Botón Salir" class="icon-btn">
                    </a>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="content-grid">
                <aside class="left-panel">
                    <div class="profile-card">
                        <p class="profile-label">Usuario: <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
                    </div>

                    <form id="form-generar" action="generar_reporte.php" method="POST" style="margin-bottom:10px;">
                        <button type="submit" class="btn btn-primary" style="width:100%; padding: 10px;">Añadir (Generar Reporte)</button>
                    </form>

                    <form id="form-acciones" action="" method="POST">
                        <input type="hidden" name="accion" id="input-accion" value="">
                        <input type="hidden" name="nombre_archivo" id="nombre-archivo" value="">
                        <div class="buttons-group" style="display: flex; flex-direction: column; gap: 8px;">
                            <button type="button" class="btn btn-primary" onclick="eliminarReporte()">Eliminar</button>
                            <button type="button" class="btn btn-primary" onclick="abrirReporte()">Abrir</button>
                        </div>
                    </form>
                </aside>

                <section class="right-panel">
                    <div class="reports-table-container">
                        <h3>Lista de Reportes Generados</h3>
                        <table class="reports-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre del Archivo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $index = 1;
                                if (is_dir($carpeta_pdfs)) {
                                    $archivos = scandir($carpeta_pdfs);
                                    foreach ($archivos as $archivo) {
                                        if (pathinfo($archivo, PATHINFO_EXTENSION) == 'pdf') {
                                            echo "<tr class='fila-reporte' onclick='seleccionarArchivo(this, \"{$archivo}\")'>";
                                            echo "<td>" . $index . "</td>";
                                            echo "<td>" . htmlspecialchars($archivo) . "</td>";
                                            echo "</tr>";
                                            $index++;
                                        }
                                    }
                                }
                                if ($index === 1) {
                                    echo "<tr><td colspan='2'>No hay reportes generados.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        let archivoSeleccionado = null;

        function seleccionarArchivo(elemento, nombreArchivo) {
            document.querySelectorAll('.fila-reporte').forEach(f => f.style.backgroundColor = "");
            elemento.style.backgroundColor = "#1976d2";
            
            archivoSeleccionado = nombreArchivo;
            document.getElementById('nombre-archivo').value = archivoSeleccionado;
        }

        function abrirReporte() {
            if (!archivoSeleccionado) {
                alert('Por favor, selecciona un reporte de la tabla.');
                return;
            }
            window.open('pdfs_generados/' + archivoSeleccionado, '_blank');
        }

        function eliminarReporte() {
            if (!archivoSeleccionado) {
                alert('Por favor, selecciona un reporte de la tabla para eliminarlo.');
                return;
            }
            if (confirm('¿Estás seguro de que deseas eliminar este PDF?')) {
                document.getElementById('input-accion').value = 'eliminar';
                document.getElementById('form-acciones').submit();
            }
        }
    </script>
</body>
</html>