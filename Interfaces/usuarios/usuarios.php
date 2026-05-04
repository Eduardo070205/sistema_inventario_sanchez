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
    <title>Registros de Usuarios</title>
    <link rel="stylesheet" href="../css/ventas.css"> <style>
        /* Estilos para la fila seleccionada */
        .fila-usuario { cursor: pointer; transition: 0.2s; }
        .fila-usuario:hover { background-color: rgba(47, 87, 141, 0.1); }
        .seleccionada { background-color: #2f578d !important; color: white !important; }
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
            window.pageTitle = 'Registros de Usuarios';
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
                            <input type="text" name="buscar_id" class="search-box username-input" id="inputBuscarUsuario" placeholder="Ingresa ID para consultar..." value="<?php echo htmlspecialchars($busqueda); ?>">
                            <div class="buttons-group">
                                <button type="submit" class="btn btn-primary">Consultar</button>
                            </div>
                        </form>

                        <div style="margin-top: 15px;">
                            <input type="text" class="username-input" id="inputUsuario" placeholder="Usuario seleccionado" readonly>
                        </div>
                        
                        <div class="buttons-group" style="margin-top: 10px;">
                            <a href="usuario_agregar.php" class="btn btn-primary">Añadir</a>
                            <button id="btn-eliminar" class="btn btn-primary">Eliminar</button>
                            <a id="link-modificar" href="usuario_modificar.php" class="btn btn-primary">Cambiar</a>
                        </div>
                    </div>
                </aside>

                <section class="right-panel">
                    <div class="reports-table-container">
                        <h3>Lista de Usuarios</h3>
                        <table class="reports-table">
                            <thead>
                                <tr>
                                    <th># ID</th>
                                    <th>Nombre de Usuario</th>
                                    <th>Rol</th>
                                    <th>Correo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                try {
                                    // Filtrar por ID de usuario si se ingresó un dato, de lo contrario mostrar todos
                                    if (!empty($busqueda)) {
                                        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = :id"); // Asegúrate de que la tabla se llame 'usuarios'
                                        $stmt->bindParam(':id', $busqueda, PDO::PARAM_INT);
                                        $stmt->execute();
                                        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    } else {
                                        $query = $pdo->query("SELECT * FROM usuarios");
                                        $usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
                                    }

                                    if (count($usuarios) > 0) {
                                        foreach ($usuarios as $row) {
                                            // Asigna a los atributos de la fila el identificador adecuado
                                            echo "<tr class='fila-usuario' data-id='{$row['id']}' data-nombre='{$row['nombre_usuario']}'>";
                                            echo "<td>" . $row['id'] . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nombre_usuario']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['id_rol']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['correo'] ?? '') . "</td>";
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
        let nombreSeleccionado = null;

        const btnEliminar = document.getElementById('btn-eliminar');
        const linkModificar = document.getElementById('link-modificar');
        const profileLabel = document.querySelector('.profile-label');
        const inputUsuario = document.getElementById('inputUsuario');

        // Estado inicial
        btnEliminar.disabled = true;
        linkModificar.style.pointerEvents = 'none';
        linkModificar.style.opacity = '0.5';

        document.querySelectorAll('.fila-usuario').forEach(fila => {
            fila.addEventListener('click', function() {
                // Seleccionar fila
                document.querySelectorAll('.fila-usuario').forEach(f => f.classList.remove('seleccionada'));
                this.classList.add('seleccionada');

                // Obtener datos
                idSeleccionado = this.getAttribute('data-id');
                nombreSeleccionado = this.getAttribute('data-nombre');

                // Actualizar campos
                profileLabel.innerText = "Usuario ID: " + idSeleccionado;
                inputUsuario.value = "Nombre: " + nombreSeleccionado;

                // Habilitar botones
                btnEliminar.disabled = false;
                linkModificar.style.pointerEvents = 'auto';
                linkModificar.style.opacity = '1';
                linkModificar.href = "usuario_modificar.php?id=" + idSeleccionado;
            });
        });

        btnEliminar.addEventListener('click', function() {
            if(idSeleccionado && confirm("¿Deseas eliminar al usuario ID: " + idSeleccionado + "?")) {
                window.location.href = "eliminar_usuario.php?id=" + idSeleccionado;
            }
        });
    </script>
</body>
</html>