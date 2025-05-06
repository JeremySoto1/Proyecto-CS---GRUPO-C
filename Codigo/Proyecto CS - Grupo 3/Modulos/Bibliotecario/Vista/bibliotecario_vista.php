<?php
require_once '../../Login/validar_sesion.php';
if (!isset($bibliotecarios)) $bibliotecarios = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Bibliotecarios</title>
    <link rel="stylesheet" href="../../../assets/css/bibliotecario.css">
    <link rel="stylesheet" href="../../../assets/css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../../../Helpers/templates/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h1>Gestión de Bibliotecarios</h1>
            <div class="user-info">
                <span><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></span>
                <a href="../../Login/logout.php" class="logout-btn">
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
                    <option value="usuario">Buscar por Usuario</option>
                </select>
                <input type="text" name="valor_busqueda" placeholder="Buscar..." required>
                <button type="submit" name="buscar">Buscar</button>
                <button type="button" onclick="location.reload()">Mostrar Todos</button>
            </form>

            <!-- Tabla de bibliotecarios -->
            <section class="Mostrar_Datos">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Usuario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bibliotecarios as $b): ?>
                            <tr id="fila-<?= $b['bibliotecarioID'] ?>">
                                <td><?= htmlspecialchars($b['bibliotecarioID']) ?></td>
                                <td class="nombre"><?= htmlspecialchars($b['nombre']) ?></td>
                                <td class="apellido"><?= htmlspecialchars($b['apellido']) ?></td>
                                <td class="email"><?= htmlspecialchars($b['email']) ?></td>
                                <td class="usuario"><?= htmlspecialchars($b['usuario']) ?></td>
                                <td>
                                    <button onclick="editarBibliotecario(<?= $b['bibliotecarioID'] ?>)">Modificar</button>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="eliminarID" value="<?= $b['bibliotecarioID'] ?>">
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- Botón para agregar -->
            <button id="mostrarFormulario">Agregar Bibliotecario</button>

            <!-- Formulario flotante -->
            <section id="formularioBibliotecario" style="display: none;">
                <form method="POST" id="form_bibliotecario">
                    <input type="hidden" name="bibliotecarioID" id="bibliotecarioID">
                    <div>
                        <label>Nombre:</label>
                        <input type="text" name="nombre" id="nombre" required>
                    </div>
                    <div>
                        <label>Apellido:</label>
                        <input type="text" name="apellido" id="apellido" required>
                    </div>
                    <div>
                        <label>Email:</label>
                        <input type="email" name="email" id="email" required>
                    </div>
                    <div>
                        <label>Usuario:</label>
                        <input type="text" name="usuario" id="usuario" required>
                    </div>
                    <div>
                        <label>Contraseña:</label>
                        <input type="password" name="contrasenia" id="contrasenia">
                        <small>(Dejar vacío para no cambiar)</small>
                    </div>
                    <div class="acciones">
                        <button type="submit" name="guardar">Guardar</button>
                        <button type="button" onclick="cerrarFormulario()">Cancelar</button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <script src="../../../assets/js/bibliotecario.js"></script>
    <script src="../../../assets/js/sidebar.js"></script>
</body>
</html>