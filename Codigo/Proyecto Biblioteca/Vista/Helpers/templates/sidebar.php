<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Proyecto Biblioteca/Controlador/Session/validar_sesion.php');

?>

<!-- Barra lateral de navegación -->
<div class="sidebar">
    <div class="sidebar-header">
        <h3>Sistema Biblioteca</h3>
        <p><?= ($_SESSION['rolID'] == 1) ? 'Administrador' : 'Bibliotecario' ?></p>
    </div>
    
    <div class="sidebar-menu">
        <ul>
            <li>
                <a href="/Proyecto Biblioteca/Vista/Modulos/prestamos.php">
                    <i class="fas fa-exchange-alt"></i> Préstamos
                </a>
            </li><br>
            <li>
                <a href="/Proyecto Biblioteca/Vista/Modulos/Libros.php">
                    <i class="fas fa-book"></i> Libros
                </a>
            </li><br>
            <li>
                    <a href="/Proyecto Biblioteca/Vista/Modulos/existencias.php">
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
                        <a href="/Proyecto Biblioteca/Vista/Modulos/bibliotecario.php">
                            <i class="fas fa-user-shield"></i> Bibliotecarios
                        </a>
                    </li>
                    <li>
                        <a href="/Proyecto Biblioteca/Vista/Modulos/lector.php">
                            <i class="fas fa-user-graduate"></i> Lectores
                        </a>
                    </li>
                </ul>
            </li><br>
            <li>
                <a href="/Proyecto Biblioteca/Vista/Modulos/cubiculo_vista.php">
                    <i class="fas fa-door-closed"></i> Cubículo
                </a>
            </li><br>
            <li>
                <a href="/Proyecto Biblioteca/Vista/Modulos/control_vista.php">
                    <i class="fas fa-tasks"></i> Gestión y Control
                </a>
            </li>
        </ul>
    </div>
</div>
