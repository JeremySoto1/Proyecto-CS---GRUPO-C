<?php
include '../../../Config/conexion_be.php';

function insertarLibro($title, $author, $year, $pages_no, $genderID) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_insertar_libro(?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssiii", $title, $author, $year, $pages_no, $genderID);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
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

function modificarLibro($libroID, $title, $author, $year, $pages_no, $genderID) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_modificar_libro(?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "issiii", $libroID, $title, $author, $year, $pages_no, $genderID);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

function eliminarLibro($libroID) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_eliminar_libro(?)");
    mysqli_stmt_bind_param($stmt, "i", $libroID);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

function obtenerLibrosHabilitados() {
    global $enlace;
    $libros = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_libros_habilitados()"); // Asegúrate de tener este procedimiento en tu DB
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $libros[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $libros;
}


function obtenerLibrosDeshabilitados() {
    global $enlace;
    $libros = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_libros_deshabilitados()");
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $libros[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $libros;
}

function cambiarEstadoLibro($libroID, $estado) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_habilitar_libro(?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $libroID, $estado);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}


function buscarLibros($campo_busqueda, $valor_busqueda) {
    global $enlace;
    $libros = [];

    // Preparar la llamada al procedimiento almacenado
    $query = "CALL sp_buscar_libros(?, ?)";
    $stmt = mysqli_prepare($enlace, $query);

    // Vincular parámetros al procedimiento almacenado
    mysqli_stmt_bind_param($stmt, "ss", $campo_busqueda, $valor_busqueda);  // 'ss' porque ambos parámetros son cadenas

    // Ejecutar el procedimiento almacenado
    mysqli_stmt_execute($stmt);

    // Obtener los resultados
    $resultado = mysqli_stmt_get_result($stmt);
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $libros[] = $fila;
    }

    // Cerrar la declaración
    mysqli_stmt_close($stmt);

    return $libros;
}


?>
