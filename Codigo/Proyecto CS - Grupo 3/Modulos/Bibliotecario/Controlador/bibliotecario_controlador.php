<?php
require_once '../Modelo/bibliotecario_modelo.php';
require_once '../../Login/validar_sesion.php';

//Obtiene los datos iniciales de bibliotecarios para la vista
function obtenerDatosBibliotecarios() {
    return ['bibliotecarios' => obtenerBibliotecarios()];//Obtiene todos los bibliotecarios
}

//Procesa las acciones enviadas por formulario (POST)
function procesarFormularioBibliotecario() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // ========= CREAR NUEVO BIBLIOTECARIO =========
        if (isset($_POST['guardar'])) {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $usuario = $_POST['usuario'];
            $contrasenia = $_POST['contrasenia'];
            
            if (insertarBibliotecario($nombre, $apellido, $email, $usuario, $contrasenia)) {
                return obtenerDatosBibliotecarios();// Devuelve datos actualizados
            }
        }

        // ========= MODIFICAR BIBLIOTECARIO =========
        if (isset($_POST['modificar'])) {
            $bibliotecarioID = $_POST['bibliotecarioID'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $email = $_POST['email'];
            $usuario = $_POST['usuario'];
            $contrasenia = $_POST['contrasenia'] ?? '';// Contraseña opcional en modificación
            
            if (modificarBibliotecario($bibliotecarioID, $nombre, $apellido, $email, $usuario, $contrasenia)) {
                return obtenerDatosBibliotecarios();
            }
        }

        // ========= ELIMINAR BIBLIOTECARIO =========
        if (isset($_POST['eliminarID'])) {
            $bibliotecarioID = $_POST['eliminarID'];
            if (eliminarBibliotecario($bibliotecarioID)) {
                return obtenerDatosBibliotecarios();
            }
        }

        // ========= BUSCAR BIBLIOTECARIOS =========
        if (isset($_POST['buscar'])) {
            $campo = $_POST['campo_busqueda'];// Campo por el que buscar
            $valor = $_POST['valor_busqueda'];//Valor a Buscar
            return ['bibliotecarios' => buscarBibliotecarios($campo, $valor)];
        }
    }
    return false;
}

// Obtener datos iniciales
$datos = obtenerDatosBibliotecarios();

// Procesar formulario
$procesado = procesarFormularioBibliotecario();

// Actualizar datos si hubo cambios
if (is_array($procesado)) {
    $datos = $procesado;
}

// Extraer variables para la vista
extract($datos);

// Incluir la vista
require_once '../Vista/bibliotecario_vista.php';
?>