<?php
require_once '../../Controlador/Session/validar_sesion.php';
if (!isset($lectores)) $lectores = [];
if (!isset($lectoresInactivos)) $lectoresInactivos = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Lectores</title>
    <link rel="stylesheet" href="../../assets/css/lector.css">
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        
    </style>
</head>
<body>
    <?php include '../Helpers/templates/sidebar.php'; ?>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <div class="main-content">
        <div class="header">
            <h1>Gestión de Lectores</h1>
            <div class="user-info">
                <span><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></span>
                <a href="../../index.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>

        <div class="content">
            <!-- Formulario de búsqueda -->
            <form method="POST" class="form-busqueda">
                <select name="campo_busqueda" required>
                    <option value="nombre">Buscar por Nombre</option>
                    <option value="apellido">Buscar por Apellido</option>
                    <option value="cedula">Buscar por Cédula</option>
                </select>
                <input type="text" name="valor_busqueda" placeholder="Buscar..." required>
                <button type="submit" name="buscar">Buscar</button>
                <button type="button" onclick="location.reload()">Mostrar Todos</button>
            </form>

            <!-- Tabla de lectores activos -->
            <section class="Mostrar_Datos">
                <h2>Lectores Activos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cédula</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lectores as $l): ?>
                            <tr id="fila-<?= $l['lectorID'] ?>">
                                <td><?= htmlspecialchars($l['lectorID']) ?></td>
                                <td class="nombre"><?= htmlspecialchars($l['nombre']) ?></td>
                                <td class="apellido"><?= htmlspecialchars($l['apellido']) ?></td>
                                <td class="cedula"><?= htmlspecialchars($l['cedula']) ?></td>
                                <td class="email"><?= htmlspecialchars($l['email']) ?></td>
                                <td class="telefono"><?= htmlspecialchars($l['telefono']) ?></td>
                                <td>
                                    <button onclick="editarLector(<?= $l['lectorID'] ?>)">Modificar</button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="desactivarID" value="<?= $l['lectorID'] ?>">
                                        <button type="submit">Desactivar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Botón para agregar -->
            <button id="mostrarFormulario">Agregar Lector</button>

            <!-- Formulario flotante -->
            <section id="formularioLector" style="display: none;">
                <form method="POST" id="form_lector">
                    <input type="hidden" name="lectorID" id="lectorID">
                    <div>
                        <label>Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required>
                    </div>
                    <div>
                        <label>Apellido:</label>
                        <input type="text" name="apellido" id="apellido" required>
                    </div>
                    <div>
                        <label>Cédula:</label>
                        <input type="text" name="cedula" id="cedula" required>
                    </div>
                    <div>
                        <label>Email:</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div>
                        <label>Teléfono:</label>
                        <input type="text" name="telefono" id="telefono" required>
                    </div>
                    <div>
                        <label>Dirección:</label>
                        <textarea name="direccion" id="direccion" required></textarea>
                    </div>
                    <div class="acciones">
                        <button type="submit" name="guardar">Guardar</button>
                        <button type="button" onclick="cerrarFormulario()">Cancelar</button>
                    </div>
                </form>
            </section>

            <!-- Tabla de lectores inactivos (oculta inicialmente) -->
            <button onclick="toggleInactivos()">Mostrar Lectores Inactivos</button>
            <div id="tablaInactivos" style="display: none;">
                <h2>Lectores Inactivos</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cédula</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lectoresInactivos as $l): ?>
                            <tr>
                                <td><?= htmlspecialchars($l['lectorID']) ?></td>
                                <td><?= htmlspecialchars($l['nombre']) ?></td>
                                <td><?= htmlspecialchars($l['apellido']) ?></td>
                                <td><?= htmlspecialchars($l['cedula']) ?></td>
                                <td><?= htmlspecialchars($l['email']) ?></td>
                                <td><?= htmlspecialchars($l['telefono']) ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="reactivarID" value="<?= $l['lectorID'] ?>">
                                        <button type="submit">Reactivar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../../assets/js/lector.js"></script>
    <script src="../../assets/js/sidebar.js"></script>
    <script>
        // Cerrar automáticamente los mensajes de alerta después de 3 segundos
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 3000);
    </script>
</body>
</html>