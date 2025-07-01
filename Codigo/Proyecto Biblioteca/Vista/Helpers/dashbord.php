<?php
require_once '../../Controlador/Session/validar_sesion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?= ($_SESSION['rolID'] == 1) ? 'Administrador' : 'Bibliotecario' ?></title>
    <!-- CSS -->
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Barra lateral de navegación -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>Sistema Biblioteca</h3>
            <p><?= ($_SESSION['rolID'] == 1) ? 'Administrador' : 'Bibliotecario' ?></p>
        </div>
        
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="../Modulos/prestamos.php">
                        <i class="fas fa-exchange-alt"></i> Préstamos
                    </a>
                </li><br>
                <li>
                    <a href="../Modulos/Libros.php">
                        <i class="fas fa-book"></i> Libros
                    </a>
                </li><br>
                <li>
                    <a href="../Modulos/existencias.php">
                        <i class="fas fa-book"></i> Existencias
                    </a>
                </li><br>
                <li class="has-submenu">
                    <a href="#" onclick="toggleSubmenu(event, this)">
                        <i class="fas fa-users"></i> Usuarios
                        <i class="fas fa-angle-down float-right"></i>
                    </a>
                    <ul class="submenu">
                        <li>
                            <a href="../Modulos/bibliotecario.php">
                                <i class="fas fa-user-shield"></i> Bibliotecarios
                            </a>
                        </li>
                        <li>
                            <a href="../Modulos/lector.php">
                                <i class="fas fa-user-graduate"></i> Lectores
                            </a>
                        </li>
                    </ul>
                </li><br>
                <li>
                    <a href="../Modulos/Cubiculo_vista.php">
                        <i class="fas fa-door-closed"></i> Cubículo
                    </a>
                </li><br>
                <li>
                    <a href="../Modulos/control_vista.php">
                        <i class="fas fa-tasks"></i> Gestión y Control
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Contenido principal -->
    <div class="main-content">
        <div class="header">
            <h1>Bienvenido, <?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></h1>
            <a href="../../index.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </div>
        
        <!-- Contenido específico del módulo -->
        <div class="content">
            <!-- El contenido dinámico se cargará aquí -->
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="../../assets/js/sidebar.js"></script>
</body>
</html>