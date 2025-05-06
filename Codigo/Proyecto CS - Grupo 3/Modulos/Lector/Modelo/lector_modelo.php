<?php
require_once '../../../Config/conexion_be.php';

//Inserta un nuevo lector en la base de datos
function insertarLector($nombre, $apellido, $cedula, $email, $telefono, $direccion) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_insertar_lector(?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $apellido, $cedula, $email, $telefono, $direccion);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

//Obtiene todos los lectores activos
function obtenerLectores() {
    global $enlace;
    $lectores = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_lectores()");
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $lectores[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $lectores;
}

//Obtiene todos los lectores marcados como inactivos
function obtenerLectoresInactivos() {
    global $enlace;
    $lectores = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_lectores_inactivos()");
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $lectores[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $lectores;
}

//Actualiza los datos de un lector existente
function modificarLector($lectorID, $nombre, $apellido, $cedula, $email, $telefono, $direccion) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_modificar_lector(?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issssss", $lectorID, $nombre, $apellido, $cedula, $email, $telefono, $direccion);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

//Desactiva un lector (cambio de estado en lugar de eliminación física)
function desactivarLector($lectorID) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_desactivar_lector(?)");
    mysqli_stmt_bind_param($stmt, "i", $lectorID);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

//Reactiva un lector previamente desactivado
function reactivarLector($lectorID) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_reactivar_lector(?)");
    mysqli_stmt_bind_param($stmt, "i", $lectorID);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

// Busca lectores según criterios específicos
function buscarLectores($campo, $valor) {
    global $enlace;
    $lectores = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_buscar_lectores(?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $campo, $valor);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $lectores[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $lectores;
}
?>