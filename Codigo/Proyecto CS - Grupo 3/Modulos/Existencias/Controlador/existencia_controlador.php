<?php
require_once "../Modelo/existencias_modelo.php";

//Obtiene todos los datos necesarios para la vista de existencias
function obtenerDatosVista() {
    // Obtiene parámetros de búsqueda si existen
    $campo = $_GET['campo_busqueda'] ?? '';// Campo por el que filtrar
    $valor = $_GET['valor_busqueda'] ?? '';// Valor a buscar
    
    $datos = [
        'existencias' => $campo && $valor ? buscarExistencias($campo, $valor) : obtenerTodasExistencias(),
        'ubicaciones' => obtenerUbicaciones(),
        'estados' => obtenerEstadosExistencia(),
        'disponibilidades' => obtenerDisponibilidades()
    ];
    
    return $datos;
}

//MANEJO DE CREACIÓN DE NUEVAS EXISTENCIAS
if (isset($_POST['guardar_existencia'])) {
     // Recoge todos los datos del formulario
    $libroID = $_POST['libroID'];
    $ubicacionID = $_POST['ubicacionID'];
    $estadoExistenciaID = $_POST['estadoExistenciaID'];
    $disponibilidadExistenciaID = $_POST['disponibilidadExistenciaID'];

    // Intenta insertar la nueva existencia
    $resultado = insertarExistencia($libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID);

    if ($resultado) {
        // Redirecciona con mensaje de éxito
        header("Location: ../Vista/existencias.php?exito=1");
        exit();
    } else {
        // Muestra error (en producción, manejaría esto de forma más elegante)
        echo "❌ Error al guardar la existencia.";
    }
}

// MANEJO DE MODIFICACIÓN DE EXISTENCIAS
if (isset($_POST['accion']) && $_POST['accion'] === 'modificar') {
    $existenciaID = $_POST['existenciaID'];
        $libroID = $_POST['libroID'];
        $ubicacionID = $_POST['ubicacionID'];
        $estadoExistenciaID = $_POST['estadoExistenciaID'];
        $disponibilidadExistenciaID = $_POST['disponibilidadExistenciaID'];

         // Intenta modificar la existencia
        $resultado = modificarExistencia($existenciaID, $libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID);

        if ($resultado) {
            // Redirecciona con mensaje de éxito
            header("Location: ../Vista/existencias.php?modificado=1");
            exit();
        } else {
            // Muestra error
            echo "❌ Error al modificar la existencia.";
        }
    }


?>
