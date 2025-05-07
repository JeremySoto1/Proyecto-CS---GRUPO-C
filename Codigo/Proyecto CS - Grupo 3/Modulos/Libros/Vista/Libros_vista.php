<?php
// Verificar si la sesión está activa
require_once '../../Login/validar_sesion.php'; // Verifica la ruta

if (!isset($libros)) $libros = [];
if (!isset($librosDeshabilitados)) $librosDeshabilitados = [];
if (!isset($librosHabilitados)) $librosHabilitados = [];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Libros</title>
    <link rel="stylesheet" href="../../../assets/CSS/libro.css">
    <link rel="stylesheet" href="../../../assets/css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include '../../../Helpers/templates/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="header">
            <h1>Gestión de Libros</h1>
            <div class="user-info">
                <span><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></span>
                <a href="../../../index.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
        

        <div class="content">
            <?php 
                 if (isset($_GET['error'])) {
                     $errorClass = '';
                     switch($_GET['code'] ?? 0) {
                         case 600: $errorClass = 'error-duplicado'; break;
                         case 603: $errorClass = 'error-validacion'; break;
                         case 602: $errorClass = 'error-prestado'; break;
                         default: $errorClass = 'error-generico';
                     }

                     echo '<div class="error-message '.$errorClass.'">';
                     echo '<i class="fas fa-exclamation-circle"></i> ';
                     echo htmlspecialchars($_GET['error']);
                     echo '</div>';
                 }
             ?>
            <!-- Formulario de búsqueda -->
            <form method="POST" class="form-busqueda">
                <select name="campo_busqueda" required>
                    <option value="titulo">Buscar por Título</option>
                    <option value="autor">Buscar por Autor</option>
                    <option value="id">Buscar por ID</option>
                </select>
                <input type="text" name="valor_busqueda" placeholder="Buscar..." required>
                <button type="submit" name="buscar">Buscar</button>
            </form>

            <!-- TABLA -->
            <section class="Mostrar_Datos">
                <table>
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Año</th>
                            <th>Páginas</th>
                            <th>Género</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros as $libro): ?>
                            <tr id="fila-<?= $libro['libroID'] ?>">
                                <td class="titulo"><?= htmlspecialchars($libro['title']) ?></td>
                                <td class="autor"><?= htmlspecialchars($libro['author']) ?></td>
                                <td class="anio"><?= htmlspecialchars($libro['year']) ?></td>
                                <td class="paginas"><?= htmlspecialchars($libro['pages_no']) ?></td>
                                <td class="gender" data-id="<?= htmlspecialchars($libro['gender']) ?>">
                                    <?= htmlspecialchars($libro['gender']) ?>
                                </td>
                                <td>
                                    <button onclick="editarLibro(<?= $libro['libroID'] ?>)">Modificar</button>
                                    <form method="POST" action="../Controlador/libros_controlador.php" style="display:inline;">
                                        <input type="hidden" name="eliminarID" value="<?= $libro['libroID'] ?>">
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>

            <!-- BOTÓN -->
            <button id="mostrarFormulario">Agregar Libro</button>

            <!-- FORMULARIO -->
            <section id="formularioLibro" style="display: none;">
                <form id="form_libro" action="../Controlador/libros_controlador.php" method="POST">
                    <input type="hidden" name="libroID" id="libroID">
                    <div><label>Título:</label><input type="text" name="title" id="title" required></div>
                    <div><label>Autor:</label><input type="text" name="author" id="author" required></div>
                    <div><label>Año:</label><input type="number" name="year" id="year" required></div>
                    <div><label>Páginas:</label><input type="number" name="pages_no" id="pages_no" required></div>
                    <div>
                        <label>Género:</label>
                        <select name="gender" id="gender" required>
                            <option value="1">Ciencia Ficción</option>
                            <option value="2">Romance</option>
                            <option value="3">Historia</option>
                            <option value="4">Drama</option>
                            <option value="5">Terror</option>
                            <option value="6">Científico</option>
                        </select>
                    </div>
                    <button type="submit" name="guardar">Guardar Libro</button>
                </form>
            </section>

            <!-- Tabla DESHABILITADOS OCULTA -->
            <button onclick="toggleDeshabilitados()">Ver Libros Deshabilitados</button>
            <div id="tablaDeshabilitados" class="oculto" style="display: none;">
                <h2>Libros Deshabilitados</h2>
                <table>
                    <thead>
                        <tr><th>Título</th><th>Autor</th><th>Año</th><th>Páginas</th><th>Género</th><th>Acciones</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($librosDeshabilitados as $libro): ?>
                            <tr>
                                <td><?= htmlspecialchars($libro['title']) ?></td>
                                <td><?= htmlspecialchars($libro['author']) ?></td>
                                <td><?= htmlspecialchars($libro['year']) ?></td>
                                <td><?= htmlspecialchars($libro['pages_no']) ?></td>
                                <td><?= htmlspecialchars($libro['gender']) ?></td>
                                <td>
                                    <form method="POST" action="../Controlador/libros_controlador.php" style="display:inline;">
                                        <input type="hidden" name="estadoID" value="<?= $libro['libroID'] ?>">
                                        <input type="hidden" name="nuevoEstado" value="1">
                                        <button type="submit">Habilitar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../../../assets/js/libros.js"></script>
    <script src="../../../assets/js/sidebar.js"></script>
</body>
</html>