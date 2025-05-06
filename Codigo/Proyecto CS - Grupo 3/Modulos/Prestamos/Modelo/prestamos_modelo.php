<?php
include '../../../Config/conexion_be.php';

//Método para insertar los datos del préstamos en la BD
function insertarPrestamo($lectorID, $libroID, $fecha_prestamo, $fecha_limite, $estado_prestamo) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_insertar_prestamo(?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iissi", $lectorID, $libroID, $fecha_prestamo, $fecha_limite, $estado_prestamo);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}
//Metodo para el registro de las devoluciones de los libros en la BD
function registrarDevolucion($prestamoID) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_registrar_devolucion(?)");
    mysqli_stmt_bind_param($stmt, "i", $prestamoID);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}
//Método para traer todos los prestamos existentes en la BD
function obtenerPrestamos() {
    global $enlace;
    $prestamos = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_prestamos()");
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $prestamos[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $prestamos;
}
//Método para traer los datos de los lectores de la BD
function buscarLector($cedula) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_buscar_lector(?)");
    mysqli_stmt_bind_param($stmt, "s", $cedula);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $lector = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);
    return $lector;
}
//Método para traer los datos de los prestamos de la BD
function buscarPrestamos($campo, $valor) {
    global $enlace;
    $prestamos = [];
    
    if ($campo == 'lectorCedula') {
        $stmt = mysqli_prepare($enlace, "CALL sp_buscar_prestamos_por_cedula(?)");
        mysqli_stmt_bind_param($stmt, "s", $valor);
    } elseif ($campo == 'estado_prestamo') {
        $stmt = mysqli_prepare($enlace, "CALL sp_buscar_prestamos_por_estado(?)");
        mysqli_stmt_bind_param($stmt, "s", $valor);
    } else {
        return [];
    }
    
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $prestamos[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $prestamos;
}
?>