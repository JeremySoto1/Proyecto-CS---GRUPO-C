<?php
session_start();
require_once '../../Config/conexion_be.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        header("Location: login.php?error=Usuario+y+contraseña+son+requeridos");
        exit();
    }

    // Consulta preparada para evitar SQL injection
    $query = "SELECT bibliotecarioID, nombre, apellido, usuario, contrasenia, rolID 
              FROM bibliotecario 
              WHERE usuario = ? AND rolID IN (1, 2)";
    
    $stmt = mysqli_prepare($enlace, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Verificar la contraseña (asumiendo que no está hasheada)
        if ($password === $row['contrasenia']) {
            // Autenticación exitosa
            $_SESSION['bibliotecarioID'] = $row['bibliotecarioID'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['apellido'] = $row['apellido'];
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['rolID'] = $row['rolID'];
            
            // Redirigir según el rol
            if ($row['rolID'] == 1) {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: ../../Helpers/dashbord.php");
            }
            exit();
        } else {
            header("Location: login.php?error=Contraseña+incorrecta");
            exit();
        }
    } else {
        header("Location: login.php?error=Usuario+no+encontrado+o+no+tiene+permisos");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>