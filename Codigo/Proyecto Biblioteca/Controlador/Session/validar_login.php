<?php
session_start();
require_once '../../Modelo/Config/conexion_be.php';
require_once '../Helpers/excepciones/excepciones_login.php';

function registrarIntentoFallido($username, $enlace) {
    if (!isset($_SESSION['intentos'][$username])) {
        $_SESSION['intentos'][$username] = 1;
    } else {
        $_SESSION['intentos'][$username]++;
    }
    
    if ($_SESSION['intentos'][$username] >= 3) {
        $query = "UPDATE bibliotecario SET bloqueado = 1 WHERE usuario = ?";
        $stmt = mysqli_prepare($enlace, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        if (empty($username) || empty($password)) {
            throw new Exception("Usuario y contraseña son requeridos");
        }

        // Verificar si la cuenta está bloqueada
        $queryBloqueo = "SELECT bloqueado, contrasenia FROM bibliotecario WHERE usuario = ?";
        $stmtBloqueo = mysqli_prepare($enlace, $queryBloqueo);
        mysqli_stmt_bind_param($stmtBloqueo, "s", $username);
        mysqli_stmt_execute($stmtBloqueo);
        $resultBloqueo = mysqli_stmt_get_result($stmtBloqueo);
        
        if ($rowBloqueo = mysqli_fetch_assoc($resultBloqueo)) {
            if ($rowBloqueo['bloqueado'] == 1) {
                throw new CuentaBloqueadaException();
            }
        }

        // Consulta para obtener datos del usuario
        $query = "SELECT bibliotecarioID, nombre, apellido, usuario, contrasenia, rolID 
                 FROM bibliotecario 
                 WHERE usuario = ? AND rolID IN (1, 2)";
        
        $stmt = mysqli_prepare($enlace, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // VERIFICACIÓN DIRECTA (sin hash)
            if ($password === $row['contrasenia']) {
                // Autenticación exitosa
                unset($_SESSION['intentos'][$username]);
                
                $_SESSION['bibliotecarioID'] = $row['bibliotecarioID'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['apellido'] = $row['apellido'];
                $_SESSION['usuario'] = $row['usuario'];
                $_SESSION['rolID'] = $row['rolID'];
                
                if ($row['rolID'] == 1) {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: ../../Vista/Helpers/dashbord.php");
                }
                exit();
            } else {
                registrarIntentoFallido($username, $enlace);
                throw new ContraseniaIncorrectaException();
            }
        } else {
            throw new UsuarioNoExistenteException();
        }
    } catch (UsuarioNoExistenteException $e) {
        header("Location: ../../index.php?error=" . urlencode($e->getMessage()) . "&code=" . $e->getCode());
        exit();
    } catch (ContraseniaIncorrectaException $e) {
        header("Location: ../../index.php?error=" . urlencode($e->getMessage()) . "&code=" . $e->getCode());
        exit();
    } catch (CuentaBloqueadaException $e) {
        header("Location: ../../index.php?error=" . urlencode($e->getMessage()) . "&code=" . $e->getCode());
        exit();
    } catch (Exception $e) {
        header("Location: ../../index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: ../../index.php");
    exit();
}
?>