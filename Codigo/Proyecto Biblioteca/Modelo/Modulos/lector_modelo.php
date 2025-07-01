<?php
require_once __DIR__ . '/../Config/conexion_be.php';//Llamada a la conexión
require_once '../../Controlador/Helpers/Servicios/LectorValidatorService.php';//Llamada al tratado de exepciones


//Inserta un nuevo lector en la base de datos
function insertarLector($nombre, $apellido, $cedula, $email, $telefono, $direccion) {
    global $enlace;//Conexión a la BD
    $stmt = mysqli_prepare($enlace, "CALL sp_insertar_lector(?, ?, ?, ?, ?, ?)");//Llamada al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "ssssss", $nombre, $apellido, $cedula, $email, $telefono, $direccion);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

//Obtiene todos los lectores activos
function obtenerLectores() {
    global $enlace;//Conexión a la BD
    $lectores = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_lectores()");//Llamada al procedimiento almacenado
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
    global $enlace;//Conexión a la BD
    $lectores = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_lectores_inactivos()");//Llamada al procedimiento almacenado
    mysqli_stmt_execute($stmt);//Ejecuta el Procedimiento
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $lectores[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $lectores;
}

//Actualiza los datos de un lector existente
function modificarLector($lectorID, $nombre, $apellido, $cedula, $email, $telefono, $direccion) {
    global $enlace;//Conexión a la BD
    $stmt = mysqli_prepare($enlace, "CALL sp_modificar_lector(?, ?, ?, ?, ?, ?, ?)");//Llamada al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "issssss", $lectorID, $nombre, $apellido, $cedula, $email, $telefono, $direccion);
    $resultado = mysqli_stmt_execute($stmt);//Ejecuta el Procedimiento
    mysqli_stmt_close($stmt);
    return $resultado;
}

//Desactiva un lector (cambio de estado en lugar de eliminación física)
function desactivarLector($lectorID) {
    global $enlace;//Conexión a la BD
    $stmt = mysqli_prepare($enlace, "CALL sp_desactivar_lector(?)");//Llamada al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "i", $lectorID);
    $resultado = mysqli_stmt_execute($stmt);//Ejecuta el procedimiento
    mysqli_stmt_close($stmt);
    return $resultado;
}

//Reactiva un lector previamente desactivado
function reactivarLector($lectorID) {
    global $enlace;//Conexión a la BD
    $stmt = mysqli_prepare($enlace, "CALL sp_reactivar_lector(?)");//Llamada al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "i", $lectorID);
    $resultado = mysqli_stmt_execute($stmt);//Ejecuta el Procedimiento
    mysqli_stmt_close($stmt);
    return $resultado;
}

// Busca lectores según criterios específicos
function buscarLectores($campo, $valor) {
    global $enlace, $validator;//Conexión a la BD y validator para hacer validaciones de las excepciones
    $lectores = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_buscar_lectores(?, ?)");//Llamada al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "ss", $campo, $valor);
    mysqli_stmt_execute($stmt);//Ejecuta el procedimiento
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $lectores[] = $fila;
    }
    mysqli_stmt_close($stmt);
    
    // Validar estado del lector si solo se busca uno
    if (count($lectores) === 1) {
        $validator->validarLectorExistente($lectores[0]);
    }
    
    return $lectores;
}

?>