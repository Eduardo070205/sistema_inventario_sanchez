<?php
require '../../conexion/verificar_sesion.php';
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
                        <p class="profile-label">Inv ID: ####</p>
                    </div>

                    <div class="form-section">
                        <input type="text" class="username-input" id="inputInventario" placeholder="Sucursal de selección" readonly disabled>
                        
                        <div class="buttons-group">
                            <a href="inventario_agregar.php"><button class="btn btn-primary">Añadir</button></a>
                            <button id="btn-eliminar" class="btn btn-primary" disabled>Eliminar</button>
                            <button class="btn btn-primary" disabled>Consultar</button>
                            <a id="link-modificar" href="#"><button id="btn-modificar" class="btn btn-primary" disabled>Cambiar</button></a>
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
                                $pdo = require '../../conexion/conexion.php';
                                try {
                                    $query = $pdo->query("SELECT * FROM inventarios");
                                    while ($row = $query->fetch()) {
                                        echo "<tr class='fila-inventario' data-id='{$row['id']}' data-sucursal='{$row['id_sucursal']}'>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . htmlspecialchars($row['id_sucursal']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['id_producto']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['cantidad_disponible']) . "</td>";
                                        echo "</tr>";
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