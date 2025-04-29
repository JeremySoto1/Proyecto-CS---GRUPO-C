<?php
require_once "../../../Config/conexion_be.php";

// Obtener todas las existencias (con joins a las tablas relacionadas)
function obtenerTodasExistencias() {
    global $enlace;
    $sql = "CALL obtenerTodasExistencias()";
    $result = mysqli_query($enlace, $sql);
    $existencias = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_next_result($enlace); // Limpia el buffer del procedimiento
    return $existencias;
}

// Buscar por título o existenciaID
function buscarExistencias($campo, $valor) {
    global $enlace;
    $campo = mysqli_real_escape_string($enlace, $campo);
    $valor = mysqli_real_escape_string($enlace, $valor);

    $sql = "CALL buscarExistencias('$campo', '$valor')";
    $result = mysqli_query($enlace, $sql);
    $existencias = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_next_result($enlace); // Limpia el buffer del procedimiento
    return $existencias;
}

// Obtener ubicaciones para llenar el combo
function obtenerUbicaciones() {
    global $enlace;
    $sql = "CALL obtenerUbicaciones()";
    $result = mysqli_query($enlace, $sql);
    $ubicaciones = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_next_result($enlace); // Limpia el buffer del procedimiento
    return $ubicaciones;
}

// Obtener estados de existencia
function obtenerEstadosExistencia() {
    global $enlace;
    $sql = "CALL obtenerEstadosExistencia()";
    $result = mysqli_query($enlace, $sql);
    $estados = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_next_result($enlace); // Limpia el buffer del procedimiento
    return $estados;
}

// Obtener disponibilidades
function obtenerDisponibilidades() {
    global $enlace;
    $sql = "CALL obtenerDisponibilidades()";
    $result = mysqli_query($enlace, $sql);
    $disponibilidades = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_next_result($enlace); // Limpia el buffer del procedimiento
    return $disponibilidades;
}

function insertarExistencia($libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID) {
    global $enlace;

    $stmt = mysqli_prepare($enlace, "CALL insertarExistencia(?, ?, ?, ?)");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiii", $libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID);
        $ejecucion = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_next_result($enlace); // Limpia el buffer para futuras llamadas
        return $ejecucion;
    } else {
        return false;
    }
}

function modificarExistencia($existenciaID, $libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID) {
    global $enlace;

    $stmt = mysqli_prepare($enlace, "CALL sp_actualizar_existencia(?, ?, ?, ?, ?)");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiiii", $existenciaID, $libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID);
        $ejecucion = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_next_result($enlace); // Limpia el buffer para futuras llamadas
        return $ejecucion;
    } else {
        return false;
    }
}

?>
