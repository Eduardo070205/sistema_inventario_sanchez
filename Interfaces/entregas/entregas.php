<?php
require '../../conexion/verificar_sesion.php';
$pdo = require '../../conexion/conexion.php';

// Construcción de la consulta de filtrado
$whereClause = [];
$params = [];

if (isset($_GET['buscar_venta']) && trim($_GET['buscar_venta']) !== '') {
    $whereClause[] = "id_venta = :id_venta";
    $params[':id_venta'] = trim($_GET['buscar_venta']);
}
if (isset($_GET['buscar_estado']) && trim($_GET['buscar_estado']) !== '') {
    $whereClause[] = "estado LIKE :estado";
    $params[':estado'] = '%' . trim($_GET['buscar_estado']) . '%';
}
if (isset($_GET['buscar_fecha']) && trim($_GET['buscar_fecha']) !== '') {
    $whereClause[] = "fecha_programada = :fecha";
    $params[':fecha'] = trim($_GET['buscar_fecha']);
}

$sql = "SELECT * FROM entregas";
if (!empty($whereClause)) {
    $sql .= " WHERE " . implode(" AND ", $whereClause);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Entregas</title>
    <link rel="stylesheet" href="../css/entregas.css">
    <style>
        .fila-entrega { cursor: pointer; transition: 0.2s; }
        .fila-entrega:hover { background-color: rgba(47, 87, 141, 0.1); }
        .seleccionada { background-color: #2f578d !important; color: white !important; }
        .seleccionada .status { border: 1px solid white; color: #fff !important; }
        button:disabled { opacity: 0.5; cursor: not-allowed; filter: grayscale(1); }
        
        .search-box {
            width: 100%;
            padding: 8px;
            margin-bottom: 8px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Entregas';
            fetch('../componentes/header.html')
            .then(response => response.text())
            .then(html => {
                document.getElementById('header').innerHTML = html;
                document.getElementById('page-title').innerText = window.pageTitle;
            });
        </script>

        <main class="main-content">
            <div class="content-grid">
                <aside class="left-panel">
                    <div class="profile-card">
                        <div class="profile-avatar">
                            <img src="../img/user2.png" alt="Logo de usuario" class="profile-avatar-img">
                        </div>
                        <p class="profile-label">Usuario: <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
                    </div>

                    <div class="form-section">
                        <form method="GET" action="">
                            <input type="text" name="buscar_venta" class="search-box username-input" id="inputBuscarVenta" placeholder="Buscar por ID Venta" value="<?php echo isset($_GET['buscar_venta']) ? htmlspecialchars($_GET['buscar_venta']) : ''; ?>">
                            <input type="text" name="buscar_estado" class="search-box username-input" id="inputBuscarEstado" placeholder="Buscar por Estado" value="<?php echo isset($_GET['buscar_estado']) ? htmlspecialchars($_GET['buscar_estado']) : ''; ?>">
                            <input type="date" name="buscar_fecha" class="search-box username-input" id="inputBuscarFecha" value="<?php echo isset($_GET['buscar_fecha']) ? htmlspecialchars($_GET['buscar_fecha']) : ''; ?>">
                            
                            <div class="buttons-group">
                                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">Consultar</button>
                            </div>
                        </form>
                        
                        <div class="buttons-group">
                            <a href="entrega_agregar.php" style="text-decoration: none; width: 100%;"><button class="btn btn-primary" style="width: 100%; margin-bottom: 5px;">Añadir</button></a>
                            <button id="btn-eliminar" class="btn btn-primary" disabled style="width: 100%; margin-bottom: 5px;">Eliminar</button>
                            <a id="link-modificar" href="entrega_modificar.php" style="text-decoration: none; width: 100%;"><button id="btn-modificar" class="btn btn-primary" disabled style="width: 100%;">Cambiar</button></a>
                        </div>
                    </div>
                </aside>

                <section class="right-panel">
                    <div class="deliveries-table-container">
                        <h3>Registros de Entregas</h3>
                        <table class="deliveries-table">
                            <thead>
                                <tr>
                                    <th># ID</th>
                                    <th>ID Venta</th>
                                    <th>Fecha Programada</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute($params);
                                    $entregas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    if (count($entregas) > 0) {
                                        foreach ($entregas as $row) {
                                            $claseEstado = 'pending';
                                            if (strtolower($row['estado']) == 'entregado') {
                                                $claseEstado = 'delivered';
                                            } elseif (strtolower($row['estado']) == 'enviado') {
                                                $claseEstado = 'in-transit';
                                            }

                                            echo "<tr class='fila-entrega' data-id='{$row['id']}' data-venta='{$row['id_venta']}'>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['id_venta']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['fecha_programada']) . "</td>";
                                            echo "<td><span class='status {$claseEstado}'>" . htmlspecialchars(ucfirst($row['estado'])) . "</span></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>No se encontraron registros.</td></tr>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='4'>Error: " . $e->getMessage() . "</td></tr>";
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
        let idSeleccionadoId = null;
        let idSeleccionado = null;
        let ventaSeleccionada = null;

        const btnEliminar = document.getElementById('btn-eliminar');
        const btnModificar = document.getElementById('btn-modificar');
        const linkModificar = document.getElementById('link-modificar');
        const profileLabel = document.querySelector('.profile-label');

        // Estado inicial de los botones
        btnEliminar.disabled = true;
        btnModificar.disabled = true;
        linkModificar.style.pointerEvents = 'none';
        linkModificar.style.opacity = '0.5';

        document.querySelectorAll('.fila-entrega').forEach(fila => {
            fila.addEventListener('click', function() {
                document.querySelectorAll('.fila-entrega').forEach(f => f.classList.remove('seleccionada'));
                this.classList.add('seleccionada');

                idSeleccionado = this.getAttribute('data-id');
                ventaSeleccionada = this.getAttribute('data-venta');

                profileLabel.innerText = "Entrega ID: " + idSeleccionado;

                btnEliminar.disabled = false;
                btnModificar.disabled = false;
                linkModificar.style.pointerEvents = 'auto';
                linkModificar.style.opacity = '1';
                linkModificar.href = "entrega_modificar.php?id=" + idSeleccionado;
            });
        });

        btnEliminar.addEventListener('click', function() {
            if (idSeleccionado && confirm("¿Deseas eliminar la entrega ID: " + idSeleccionado + "?")) {
                window.location.href = "eliminar_entrega.php?id=" + idSeleccionado;
            }
        });
    </script>
</body>
</html>