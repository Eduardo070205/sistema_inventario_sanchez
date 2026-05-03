<?php
require '../../conexion/verificar_sesion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Clientes</title>
    <link rel="stylesheet" href="../css/clientes.css">
    <style>
        /* Estilos para la selección */
        .fila-cliente { cursor: pointer; transition: 0.2s; }
        .fila-cliente:hover { background-color: rgba(47, 87, 141, 0.1); }
        .seleccionada { background-color: "#1976d2" !important; color: white !important; }
        .seleccionada .status { border: 1px solid white; }
        button:disabled { opacity: 0.5; cursor: not-allowed; filter: grayscale(1); }
    </style>
</head>
<body>
    <div class="container">
        <div id="header"></div>
        <script>
            window.pageTitle = 'Clientes';
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
                        <!-- Inputs de búsqueda (puedes programar el filtro después) -->
                        <input type="text" class="search-input" id="searchNombre" placeholder="Buscar por Nombre">
                        <input type="text" class="search-input" placeholder="Nombre seleccionado" id="inputNombre" readonly>
                        
                        <div class="buttons-group">
                            <a href="cliente_agregar.php"><button class="btn btn-primary">Añadir</button></a>
                            <button id="btn-eliminar" class="btn btn-primary">Eliminar</button>
                            <button class="btn btn-primary">Consultar</button>
                            <a id="link-modificar" href="cliente_modificar.php"><button class="btn btn-primary">Cambiar</button></a>
                        </div>
                    </div>
                </aside>

                <section class="right-panel">
                    <div class="clients-table-container">
                        <h3>Registros de Clientes</h3>
                        <table class="clients-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $pdo = require '../../conexion/conexion.php';
                                try {
                                    $query = $pdo->query("SELECT * FROM clientes");
                                    while ($row = $query->fetch()) {
                                        echo "<tr class='fila-cliente' data-id='{$row['id']}' data-nombre='".htmlspecialchars($row['nombre'])."'>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['direccion']) . "</td>";
                                        echo "</tr>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='5'>Error: " . $e->getMessage() . "</td></tr>";
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
        let nombreSeleccionado = null;

        const btnEliminar = document.getElementById('btn-eliminar');
        const linkModificar = document.getElementById('link-modificar');
        const profileLabel = document.querySelector('.profile-label');
        const inputNombre = document.getElementById('inputNombre');

        // Estado inicial
        btnEliminar.disabled = true;
        linkModificar.style.pointerEvents = 'none';
        linkModificar.style.opacity = '0.5';

        document.querySelectorAll('.fila-cliente').forEach(fila => {
            fila.addEventListener('click', function() {
                // Estética de selección
                document.querySelectorAll('.fila-cliente').forEach(f => f.classList.remove('seleccionada'));
                this.classList.add('seleccionada');

                // Captura de datos
                idSeleccionado = this.getAttribute('data-id');
                nombreSeleccionado = this.getAttribute('data-nombre');

                // Actualizar panel lateral
                profileLabel.innerText = "Cliente ID: " + idSeleccionado;
                inputNombre.value = nombreSeleccionado;

                // Activar botones
                btnEliminar.disabled = false;
                linkModificar.style.pointerEvents = 'auto';
                linkModificar.style.opacity = '1';
                linkModificar.href = "cliente_modificar.php?id=" + idSeleccionado;
            });
        });

        // Lógica de eliminación
        btnEliminar.addEventListener('click', function() {
            if(idSeleccionado && confirm("¿Deseas eliminar al cliente: " + nombreSeleccionado + "?")) {
                window.location.href = "eliminar_cliente.php?id=" + idSeleccionado;
            }
        });
    </script>
</body>
</html>