<?php
require_once "../Modelo/existencias_modelo.php";

function obtenerDatosVista() {
    $campo = $_GET['campo_busqueda'] ?? '';
    $valor = $_GET['valor_busqueda'] ?? '';
    
    $datos = [
        'existencias' => $campo && $valor ? buscarExistencias($campo, $valor) : obtenerTodasExistencias(),
        'ubicaciones' => obtenerUbicaciones(),
        'estados' => obtenerEstadosExistencia(),
        'disponibilidades' => obtenerDisponibilidades()
    ];
    
    return $datos;
}

if (isset($_POST['guardar_existencia'])) {
    $libroID = $_POST['libroID'];
    $ubicacionID = $_POST['ubicacionID'];
    $estadoExistenciaID = $_POST['estadoExistenciaID'];
    $disponibilidadExistenciaID = $_POST['disponibilidadExistenciaID'];

    $resultado = insertarExistencia($libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID);

    if ($resultado) {
        header("Location: ../Vista/existencias.php?exito=1");
        exit();
    } else {
        echo "❌ Error al guardar la existencia.";
    }
}

if (isset($_POST['accion']) && $_POST['accion'] === 'modificar') {
    $existenciaID = $_POST['existenciaID'];
        $libroID = $_POST['libroID'];
        $ubicacionID = $_POST['ubicacionID'];
        $estadoExistenciaID = $_POST['estadoExistenciaID'];
        $disponibilidadExistenciaID = $_POST['disponibilidadExistenciaID'];

        $resultado = modificarExistencia($existenciaID, $libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID);

        if ($resultado) {
            header("Location: ../Vista/existencias.php?modificado=1");
            exit();
        } else {
            echo "❌ Error al modificar la existencia.";
        }
    }


?>
