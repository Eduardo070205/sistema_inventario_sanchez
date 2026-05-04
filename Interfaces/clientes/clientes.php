<?php
require '../../conexion/verificar_sesion.php';
$pdo = require '../../conexion/conexion.php';

$busqueda = '';
if (isset($_GET['buscar_nombre']) && trim($_GET['buscar_nombre']) !== '') {
    $busqueda = trim($_GET['buscar_nombre']);
}
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
        .seleccionada { background-color: #1976d2 !important; color: white !important; }
        .seleccionada .status { border: 1px solid white; }
        button:disabled { opacity: 0.5; cursor: not-allowed; filter: grayscale(1); }
        
        .search-box {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
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
                        <form method="GET" action="">
                            <input type="text" name="buscar_nombre" class="search-box username-input" id="searchNombre" placeholder="Buscar por Nombre" value="<?php echo htmlspecialchars($busqueda); ?>">
                            <div class="buttons-group">
                                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">Consultar</button>
                            </div>
                        </form>

                        <input type="text" class="search-input username-input" placeholder="Nombre seleccionado" id="inputNombre" readonly disabled>
                        
                        <div class="buttons-group" style="margin-top: 10px;">
                            <a href="cliente_agregar.php" style="text-decoration: none; width: 100%;"><button class="btn btn-primary" style="width: 100%; margin-bottom: 5px;">Añadir</button></a>
                            <button id="btn-eliminar" class="btn btn-primary" disabled style="width: 100%; margin-bottom: 5px;">Eliminar</button>
                            <a id="link-modificar" href="cliente_modificar.php" style="text-decoration: none; width: 100%;"><button id="btn-modificar" class="btn btn-primary" disabled style="width: 100%;">Cambiar</button></a>
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
                                try {
                                    if (!empty($busqueda)) {
                                        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE nombre LIKE :nombre");
                                        $searchTerm = '%' . $busqueda . '%';
                                        $stmt->bindParam(':nombre', $searchTerm, PDO::PARAM_STR);
                                        $stmt->execute();
                                        $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    } else {
                                        $query = $pdo->query("SELECT * FROM clientes");
                                        $clientes = $query->fetchAll(PDO::FETCH_ASSOC);
                                    }

                                    if (count($clientes) > 0) {
                                        foreach ($clientes as $row) {
                                            echo "<tr class='fila-cliente' data-id='{$row['id']}' data-nombre='" . htmlspecialchars($row['nombre']) . "'>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['direccion']) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No se encontraron registros.</td></tr>";
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
        const btnModificar = document.getElementById('btn-modificar');
        const linkModificar = document.getElementById('link-modificar');
        const profileLabel = document.querySelector('.profile-label');
        const inputNombre = document.getElementById('inputNombre');

        // Estado inicial
        btnEliminar.disabled = true;
        if (btnModificar) {
            btnModificar.disabled = true;
        }
        linkModificar.style.pointerEvents = 'none';
        linkModificar.style.opacity = '0.5';

        document.querySelectorAll('.fila-cliente').forEach(fila => {
            fila.addEventListener('click', function() {
                // Estética de selección
                document.querySelectorAll('.fila-cliente').forEach(f => f.classList.remove('seleccionada'));
                this.classList.add('seleccionada');

                // Captura de datos usando los atributos data-* de la fila
                idSeleccionado = this.getAttribute('data-id');
                nombreSeleccionado = this.getAttribute('data-nombre');

                // Actualizar panel lateral
                profileLabel.innerText = "Cliente ID: " + idSeleccionado;
                inputNombre.value = nombreSeleccionado;

                // Activar botones
                btnEliminar.disabled = false;
                if (btnModificar) {
                    btnModificar.disabled = false;
                }
                
                // Habilitar el enlace y asignar la URL correspondiente
                linkModificar.style.pointerEvents = 'auto';
                linkModificar.style.opacity = '1';
                linkModificar.href = "cliente_modificar.php?id=" + idSeleccionado;
            });
        });

        // Lógica de eliminación
        btnEliminar.addEventListener('click', function() {
            if (idSeleccionado && confirm("¿Deseas eliminar al cliente: " + nombreSeleccionado + "?")) {
                window.location.href = "eliminar_cliente.php?id=" + idSeleccionado;
            }
        });
    </script>
</body>
</html>