<?php
include '../../../Config/conexion_be.php';



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


// Procedimiento almacenado para búsqueda por cédula
function buscarPorCedula($cedula) {
    global $enlace;
    $prestamos = [];
    
    $stmt = mysqli_prepare($enlace, "CALL sp_buscar_prestamos_por_cedula(?)");
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

// Procedimiento almacenado para búsqueda por nombre y apellido
function buscarPorNombreApellido($nombreCompleto) {
    global $enlace;
    $prestamos = [];
    
    $stmt = mysqli_prepare($enlace, "CALL sp_buscar_prestamos_por_nombre_apellido(?)");
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

// Función principal de búsqueda
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


function obtenerLibros() {
    global $enlace;
    $libros = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_libros()");
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $libros[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $libros;
}

?>