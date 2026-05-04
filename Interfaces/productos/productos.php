<?php
require '../../conexion/verificar_sesion.php';
$pdo = require '../../conexion/conexion.php';

if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != '1') {
    echo "<script>
            alert('Acceso denegado: Módulo exclusivo para administradores.');
            window.location.href = '../home.php';
          </script>";
    exit();
}

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
    <title>Registros de Productos</title>
    <link rel="stylesheet" href="../css/productos.css">
    <style>
        .fila-producto { cursor: pointer; transition: 0.2s; }
        .fila-producto:hover { background-color: rgba(47, 87, 141, 0.1); }
        .seleccionada { background-color: #2f578d !important; color: white !important; }
        .seleccionada .status { border: 1px solid white; }
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
            window.pageTitle = 'Productos';
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
                            <input type="text" name="buscar_id" class="search-box username-input" id="inputBuscarProducto" placeholder="Ingresa ID para consultar..." value="<?php echo htmlspecialchars($busqueda); ?>">
                            <div class="buttons-group">
                                <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 10px;">Consultar</button>
                            </div>
                        </form>

                        <div style="margin-top: 5px;">
                            <input type="text" class="search-input username-input" id="inputNombre" placeholder="Producto seleccionado" readonly>
                        </div>
                        
                        <div class="buttons-group" style="margin-top: 10px;">
                            <a href="producto_agregar.php" class="btn btn-primary" style="text-decoration:none;">Añadir</a>
                            <button id="btn-eliminar" class="btn btn-primary">Eliminar</button>
                            <a id="link-modificar" href="producto_modificar.php" class="btn btn-primary" style="text-decoration:none;">Cambiar</a>
                        </div>
                    </div>
                </aside>
                
                <section class="right-panel">
                    <div class="products-table-container">
                        <h3>Registros de Productos</h3>
                        <table class="products-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>ID Inventario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    if (!empty($busqueda)) {
                                        $stmt = $pdo->prepare("SELECT * FROM productos WHERE id = :id");
                                        $stmt->bindParam(':id', $busqueda, PDO::PARAM_INT);
                                        $stmt->execute();
                                        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    } else {
                                        $query = $pdo->query("SELECT * FROM productos");
                                        $productos = $query->fetchAll(PDO::FETCH_ASSOC);
                                    }

                                    if (count($productos) > 0) {
                                        foreach ($productos as $row) {
                                            echo "<tr class='fila-producto' data-id='{$row['id']}' data-nombre='" . htmlspecialchars($row['nombre']) . "'>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['codigo']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                                            echo "<td>$" . number_format($row['precio'], 2) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['Inventarios_id']) . "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No se encontraron registros.</td></tr>";
                                    }
                                } catch (PDOException $e) {
                                    echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
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
        letSeleccionadoNombre = null;

        const btnEliminar = document.getElementById('btn-eliminar');
        const linkModificar = document.getElementById('link-modificar');
        const profileLabel = document.querySelector('.profile-label');
        const inputNombre = document.getElementById('inputNombre');

        // Estado inicial de los botones
        btnEliminar.disabled = true;
        linkModificar.style.pointerEvents = 'none';
        linkModificar.style.opacity = '0.5';

        document.querySelectorAll('.fila-producto').forEach(fila => {
            fila.addEventListener('click', function() {
                // Seleccionar fila
                document.querySelectorAll('.fila-producto').forEach(f => f.classList.remove('seleccionada'));
                this.classList.add('seleccionada');

                // Captura de datos
                idSeleccionado = this.getAttribute('data-id');
                nombreSeleccionado = this.getAttribute('data-nombre');

                // Actualizar panel lateral
                profileLabel.innerText = "Prod ID: " + idSeleccionado;
                inputNombre.value = nombreSeleccionado;

                // Activar botones
                btnEliminar.disabled = false;
                linkModificar.style.pointerEvents = 'auto';
                linkModificar.style.opacity = '1';
                linkModificar.href = "producto_modificar.php?id=" + idSeleccionado;
            });
        });

        // Eliminar producto
        btnEliminar.addEventListener('click', function() {
            if(idSeleccionado && confirm("¿Deseas eliminar el producto \"" + nombreSeleccionado + "\"?")) {
                window.location.href = "eliminar_producto.php?id=" + idSeleccionado;
            }
        });
    </script>
</body>
</html>