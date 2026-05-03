<?php
require '../../conexion/verificar_sesion.php';
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
                        <p class="profile-label">Entrega ID: ####</p>
                    </div>

                    <div class="form-section">
                        <input type="text" class="search-input" id="inputBuscarVenta" placeholder="Buscar por ID Venta" disabled>
                        <input type="text" class="search-input" id="inputBuscarEstado" placeholder="Buscar por Estado" disabled>
                        <input type="date" class="search-input" id="inputBuscarFecha" disabled>
                        
                        <div class="buttons-group">
                            <a href="entrega_agregar.php"><button class="btn btn-primary">Añadir</button></a>
                            <button id="btn-eliminar" class="btn btn-primary" disabled>Eliminar</button>
                            <button class="btn btn-primary" disabled>Consultar</button>
                            <a id="link-modificar" href="#"><button id="btn-modificar" class="btn btn-primary" disabled>Cambiar</button></a>
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
                                $pdo = require '../../conexion/conexion.php';
                                try {
                                    $query = $pdo->query("SELECT * FROM entregas");
                                    while ($row = $query->fetch()) {
                                        // Asignar clases de estilo dependiendo del estado de la entrega
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
                // Alternar selección
                document.querySelectorAll('.fila-entrega').forEach(f => f.classList.remove('seleccionada'));
                this.classList.add('seleccionada');

                idSeleccionado = this.getAttribute('data-id');
                ventaSeleccionada = this.getAttribute('data-venta');

                profileLabel.innerText = "Entrega ID: " + idSeleccionado;

                // Habilitar botones al seleccionar
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