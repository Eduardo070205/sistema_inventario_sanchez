<?php
require '../../conexion/verificar_sesion.php';

if (!isset($_SESSION['id_rol']) || $_SESSION['id_rol'] != '1') { // Ajusta el '1' según el rol de tu base de datos
    echo "<script>
            alert('Acceso denegado: Módulo exclusivo para administradores.');
            window.location.href = '../home.php';
          </script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros de Usuarios</title>
    <link rel="stylesheet" href="../css/usuarios.css">
</head>
<body>
    <div class="container">
   
        <div id="header"></div>

        <script>
            window.pageTitle = 'Registrar Usuarios';
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
                        <input type="text" class="username-input" placeholder="Nombre Usuario">
                        <div class="buttons-group">
                            <a href="usuario_agregar.php"><button class="btn btn-primary">Añadir</button></a>
                            <button id="btn-eliminar"class="btn btn-primary">Eliminar</button>
                            <button class="btn btn-primary">Consultar</button>
                            <a  href="usuario_modificar.php"><button id="btn-modificar" class="btn btn-primary">Cambiar</button></a>
                        </div>
                    </div>
                </aside>

                <section class="right-panel">
                    <div class="users-table-container">
                        <h3>Lista de Usuarios</h3>
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre de Usuario</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $pdo = require '../../conexion/conexion.php';
                                    try {
                                        $query = $pdo->query("SELECT id, nombre_usuario, estado FROM usuarios");
                                        
                                        while ($row = $query->fetch()) {
                                            $claseEstado = ($row['estado'] == 'activo') ? 'active' : 'inactive';
                                            // AGREGADO: class="fila-usuario" y data-id, data-nombre
                                            echo "<tr class='fila-usuario' data-id='{$row['id']}' data-nombre='".htmlspecialchars($row['nombre_usuario'])."'>";
                                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nombre_usuario']) . "</td>";
                                            echo "<td><span class='status {$claseEstado}'>" . htmlspecialchars($row['estado']) . "</span></td>";
                                            echo "</tr>";
                                        }
                                    } catch (PDOException $e) {
                                        echo "<tr><td colspan='3'>Error al cargar usuarios: " . $e->getMessage() . "</td></tr>";
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

        // Seleccionamos los elementos por ID o clases específicas
        const btnEliminar = document.getElementById('btn-eliminar');
        const linkCambiar = document.querySelector('a[href="usuario_modificar.php"]');
        const profileLabel = document.querySelector('.profile-label');
        const inputNombre = document.querySelector('.username-input');

        // FUNCIÓN PARA DESHABILITAR AL INICIO
        function deshabilitarAcciones() {
            btnEliminar.disabled = true;
            btnEliminar.style.opacity = "0.5";
            btnEliminar.style.cursor = "not-allowed";
            
            if(linkCambiar) {
                linkCambiar.style.pointerEvents = 'none';
                linkCambiar.style.opacity = '0.5';
            }
        }

        // Ejecutar al cargar la página
        deshabilitarAcciones();

        document.querySelectorAll('.fila-usuario').forEach(fila => {
            fila.addEventListener('click', function() {
                // Quitar estilos de otras filas
                document.querySelectorAll('.fila-usuario').forEach(f => f.style.backgroundColor = "");
                
                // Marcar fila seleccionada
                this.style.backgroundColor = "#1976d2";
                
                // Obtener datos
                idSeleccionado = this.getAttribute('data-id');
                nombreSeleccionado = this.getAttribute('data-nombre');

                // Actualizar interfaz
                profileLabel.innerText = "Usuario ID: " + idSeleccionado;
                inputNombre.value = nombreSeleccionado;

                // RE-HABILITAR BOTONES
                btnEliminar.disabled = false;
                btnEliminar.style.opacity = "1";
                btnEliminar.style.cursor = "pointer";
                
                if(linkCambiar) {
                    linkCambiar.style.pointerEvents = 'auto';
                    linkCambiar.style.opacity = '1';
                    linkCambiar.href = "usuario_modificar.php?id=" + idSeleccionado;
                }
            });
        });

        // Evento Eliminar
        btnEliminar.addEventListener('click', function() {
            if(!idSeleccionado) return;
            
            if(confirm("¿Seguro que deseas eliminar a " + nombreSeleccionado + "?")) {
                window.location.href = "eliminar_usuario.php?id=" + idSeleccionado;
            }
        });
    </script>

</body>
</html>