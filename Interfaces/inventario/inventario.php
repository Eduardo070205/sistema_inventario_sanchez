<?php
require '../../conexion/verificar_sesion.php';
$pdo = require '../../conexion/conexion.php';

$busqueda = '';
if (isset($_GET['buscar_id']) && trim($_GET['buscar_id']) !== '') {
    $busqueda = trim($_GET['buscar_id']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario</title>
    <link rel="stylesheet" href="../css/inventario.css">
    <style>
        .fila-inventario { cursor: pointer; transition: 0.2s; }
        .fila-inventario:hover { background-color: rgba(47, 87, 141, 0.1); }
        .seleccionada { background-color: #2f578d !important; color: white !important; }
        .seleccionada .status { border: 1px solid white; color: #fff !important; }
        button:disabled { opacity: 0.5; cursor: not-allowed; filter: grayscale(1); }
        
        .search-box {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Inventario';
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
                            <img src="../img/user2.png" alt="Avatar del usuario" class="profile-avatar-img">
                        </div>
                        <p class="profile-label">Usuario: <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
                    </div>

                    <div class="form-section">
                        <form method="GET" action="">
                            <input type="text" name="buscar_id" class="search-box username-input" id="inputBuscarInventario" placeholder="Ingresa ID para consultar..." value="<?php echo htmlspecialchars($busqueda); ?>">
                            <div class="buttons-group">
                                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">Consultar</button>
                            </div>
                        </form>

                        <div style="margin-top: 5px;">
                            <input type="text" class="username-input" id="inputInventario" placeholder="Sucursal de selección" readonly disabled>
                        </div>
                        
                        <div class="buttons-group" style="margin-top: 10px;">
                            <a href="inventario_agregar.php" class="btn btn-primary" style="text-decoration:none;">Añadir</a>
                            <button id="btn-eliminar" class="btn btn-primary" disabled>Eliminar</button>
                            <a id="link-modificar" href="inventario_modificar.php" style="text-decoration:none;"><button id="btn-modificar" class="btn btn-primary" disabled>Cambiar</button></a>
                        </div>
                    </div>
                </aside>

                <section class="right-panel">
                    <div class="reports-table-container">
                        <h3>Lista de Inventario</h3>
                        <table class="reports-table">
                            <thead>
                                <tr>
                                    <th># ID</th>
                                    <th>ID Sucursal</th>
                                    <th>ID Producto</th>
                                    <th>Cantidad Disponible</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    if (!empty($busqueda)) {
                                        $stmt = $pdo->prepare("SELECT * FROM inventarios WHERE id = :id");
                                        $stmt->bindParam(':id', $busqueda, PDO::PARAM_INT);
                                        $stmt->execute();
                                        $inventarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    } else {
                                        $query = $pdo->query("SELECT * FROM inventarios");
                                        $inventarios = $query->fetchAll(PDO::FETCH_ASSOC);
                                    }

                                    if (count($inventarios) > 0) {
                                        foreach ($inventarios as $row) {
                                            echo "<tr class='fila-inventario' data-id='{$row['id']}' data-sucursal='{$row['id_sucursal']}'>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['id_sucursal']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['id_producto']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['cantidad_disponible']) . "</td>";
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
        let idSeleccionado = null;
        let sucursalSeleccionada = null;

        const btnEliminar = document.getElementById('btn-eliminar');
        const btnModificar = document.getElementById('btn-modificar');
        const linkModificar = document.getElementById('link-modificar');
        const profileLabel = document.querySelector('.profile-label');
        const inputInventario = document.getElementById('inputInventario');

        // Estado inicial de los botones
        btnEliminar.disabled = true;
        btnModificar.disabled = true;
        linkModificar.style.pointerEvents = 'none';
        linkModificar.style.opacity = '0.5';

        document.querySelectorAll('.fila-inventario').forEach(fila => {
            fila.addEventListener('click', function() {
                document.querySelectorAll('.fila-inventario').forEach(f => f.classList.remove('seleccionada'));
                this.classList.add('seleccionada');

                idSeleccionado = this.getAttribute('data-id');
                sucursalSeleccionada = this.getAttribute('data-sucursal');

                profileLabel.innerText = "Inv ID: " + idSeleccionado;
                inputInventario.value = "Sucursal: " + sucursalSeleccionada;

                btnEliminar.disabled = false;
                btnModificar.disabled = false;
                linkModificar.style.pointerEvents = 'auto';
                linkModificar.style.opacity = '1';
                linkModificar.href = "inventario_modificar.php?id=" + idSeleccionado;
            });
        });

        btnEliminar.addEventListener('click', function() {
            if (idSeleccionado && confirm("¿Deseas eliminar el inventario ID: " + idSeleccionado + "?")) {
                window.location.href = "eliminar_inventario.php?id=" + idSeleccionado;
            }
        });
    </script>
</body>
</html>