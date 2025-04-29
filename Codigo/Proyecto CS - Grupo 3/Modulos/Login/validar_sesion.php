<?php
// Verificar el estado de la sesión primero
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

// Verificar el rol si es necesario (ejemplo para roles 1 y 2)
if (!isset($_SESSION['rolID']) || !in_array($_SESSION['rolID'], [1, 2])) {
    header('Location: acceso_denegado.php');
    exit();
}
?>