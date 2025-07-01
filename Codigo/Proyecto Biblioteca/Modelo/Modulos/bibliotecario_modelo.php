<?php
require_once __DIR__ . '/../Config/conexion_be.php';//Llamada a la conexión
require_once '../../Controlador/Helpers/Servicios/BibliotecarioValidatorService.php';//Llamada al tratado de exepciones

$validador = new BibliotecarioValidatorService($enlace);

//Inserta un nuevo bibliotecario en el sistema
function insertarBibliotecario($nombre, $apellido, $email, $usuario, $contrasenia) {
    global $enlace;//Conexión a la BD
    $stmt = mysqli_prepare($enlace, "CALL sp_insertar_bibliotecario(?, ?, ?, ?, ?)");//Llamada al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "sssss", $nombre, $apellido, $email, $usuario, $contrasenia);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

//Obtiene todos los bibliotecarios registrados
function obtenerBibliotecarios() {
    global $enlace;//Conexión a la BD
    $bibliotecarios = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_bibliotecarios()");//Llamada al procedimiento almacenado
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $bibliotecarios[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $bibliotecarios;
}

// Modifica los datos de un bibliotecario existente
function modificarBibliotecario($bibliotecarioID, $nombre, $apellido, $email, $usuario, $contrasenia = '') {
    global $enlace;//Conexión a la BD
    $stmt = mysqli_prepare($enlace, "CALL sp_modificar_bibliotecario(?, ?, ?, ?, ?, ?)");//Llamada al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "isssss", $bibliotecarioID, $nombre, $apellido, $email, $usuario, $contrasenia);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

//Elimina permanentemente un bibliotecario
function eliminarBibliotecario($bibliotecarioID) {
    global $enlace;//Conexión a la BD
    $stmt = mysqli_prepare($enlace, "CALL sp_eliminar_bibliotecario(?)");//Llamada al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "i", $bibliotecarioID);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

//Busca bibliotecarios según criterios específicos
function buscarBibliotecarios($campo, $valor) {
    global $enlace;//Conexión a la BD
    $bibliotecarios = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_buscar_bibliotecarios(?, ?)");//Llamada al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "ss", $campo, $valor);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $bibliotecarios[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $bibliotecarios;
}
?>