<?php
require_once __DIR__ . '/../Config/conexion_be.php';//Conexión a la Base de Datos

//Método para obtener todos los prestamos activados o completados de la base de Datos
function obtenerPrestamos() {
    global $enlace;
    $prestamos = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_prestamos()"); // Llamada al procedimiento almacenado
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $prestamos[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $prestamos;
}

//Método para buscar un préstamo por cédula del lector
function buscarPorCedula($cedula) {
    global $enlace;
    $prestamos = [];
    
    $stmt = mysqli_prepare($enlace, "CALL sp_buscar_prestamos_por_cedula(?)");//Llamada al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "s", $cedula);
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Error en búsqueda por cédula: " . mysqli_stmt_error($stmt));
        return [];
    }
    
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $prestamos[] = $fila;
    }
    
    mysqli_stmt_close($stmt);
    return $prestamos;
}

//Método para Buscar un préstamo por nombre y apellido del lector
function buscarPorNombreApellido($nombreCompleto) {
    global $enlace;
    $prestamos = [];
    
    $stmt = mysqli_prepare($enlace, "CALL sp_buscar_prestamos_por_nombre_apellido(?)");//Llamada al procedimiento almacenado
    $param = "%$nombreCompleto%";
    mysqli_stmt_bind_param($stmt, "s", $param);
    
    if (!mysqli_stmt_execute($stmt)) {
        error_log("Error en búsqueda por nombre/apellido: " . mysqli_stmt_error($stmt));
        return [];
    }
    
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $prestamos[] = $fila;
    }
    
    mysqli_stmt_close($stmt);
    return $prestamos;
}

//Método para seleccionar el tipo de busqueda que se desea hacer si por cedula o nombre y apellido
function buscarPrestamos($tipo, $valor) {
    switch ($tipo) {
        case 'cedula':
            return buscarPorCedula($valor);
        case 'nombre_apellido':
            return buscarPorNombreApellido($valor);
        default:
            return obtenerPrestamos();
    }
}

//Función para obtener los libros 
function obtenerLibros() {
    global $enlace;
    $libros = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_libros()");//Llamada al procedimiento almacenado
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $libros[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $libros;
}