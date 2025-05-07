<?php
include '../../../Config/conexion_be.php';
require_once __DIR__.'/../../../Helpers/excepciones/excepciones_libro.php'; // Agregamos el archivo de excepciones
require_once __DIR__.'/../../../Helpers/Servicios/LibroValidatorService.php';

/**
 * Inserta un nuevo libro en la base de datos
 */
function insertarLibro($title, $author, $year, $pages_no, $genderID) {
    global $enlace;
    
    try {
        $validator = new LibroValidatorService($enlace);
        $validator->validarDatosLibro($title, $author, $year, $pages_no, $genderID);
        $validator->validarLibroDuplicado($title, $author);
        
        $stmt = mysqli_prepare($enlace, "CALL sp_insertar_libro(?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new DatabaseException("Error al preparar consulta: " . mysqli_error($enlace));
        }
        
        mysqli_stmt_bind_param($stmt, "ssiii", $title, $author, $year, $pages_no, $genderID);
        $resultado = mysqli_stmt_execute($stmt);
        
        if (!$resultado) {
            throw new DatabaseException("Error al insertar libro: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
        return true;
        
    } catch (mysqli_sql_exception $e) {
        throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
    }
}

/**
 * Obtiene todos los libros del sistema
 */
function obtenerLibros() {
    global $enlace;
    
    try {
        $stmt = mysqli_prepare($enlace, "CALL sp_obtener_libros()");
        if (!$stmt) {
            throw new DatabaseException("Error al obtener libros: " . mysqli_error($enlace));
        }
        
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $libros = [];
        
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $libros[] = $fila;
        }
        
        mysqli_stmt_close($stmt);
        
        if (empty($libros)) {
            throw new LibroNoEncontradoException("No se encontraron libros registrados");
        }
        
        return $libros;
        
    } catch (mysqli_sql_exception $e) {
        throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
    }
}

/**
 * Modifica un libro existente
 */
function modificarLibro($libroID, $title, $author, $year, $pages_no, $genderID) {
    global $enlace;
    
    try {
        $validator = new LibroValidatorService($enlace);
        $validator->validarDatosLibro($title, $author, $year, $pages_no, $genderID);
        $validator->validarLibroDuplicado($title, $author, $libroID);
        
        $stmt = mysqli_prepare($enlace, "CALL sp_modificar_libro(?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new DatabaseException("Error al preparar consulta: " . mysqli_error($enlace));
        }
        
        mysqli_stmt_bind_param($stmt, "issiii", $libroID, $title, $author, $year, $pages_no, $genderID);
        $resultado = mysqli_stmt_execute($stmt);
        
        if (!$resultado) {
            throw new DatabaseException("Error al modificar libro: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
        return true;
        
    } catch (mysqli_sql_exception $e) {
        throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
    }
}

/**
 * Elimina un libro del sistema
 */
function eliminarLibro($libroID) {
    global $enlace;   
    $stmt = mysqli_prepare($enlace, "CALL sp_eliminar_libro(?)"); 
    mysqli_stmt_bind_param($stmt, "i", $libroID);
    $resultado = mysqli_stmt_execute($stmt); 
    mysqli_stmt_close($stmt);
    return $resultado;
}
    
// Obtiene libros habilitados
function obtenerLibrosHabilitados() {
    global $enlace;
    $libros = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_obtener_libros_habilitados()");
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $libros[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $libros;
}

// Obtiene libros deshabilitados
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

// Cambia el estado de un libro
function cambiarEstadoLibro($libroID, $estado) {
    global $enlace;
    $stmt = mysqli_prepare($enlace, "CALL sp_habilitar_libro(?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $libroID, $estado);
    $resultado = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $resultado;
}

// Busca libros por campo y valor
function buscarLibros($campo_busqueda, $valor_busqueda) {
    global $enlace;
    $libros = [];
    $stmt = mysqli_prepare($enlace, "CALL sp_buscar_libros(?, ?)");
    mysqli_stmt_bind_param($stmt, "ss", $campo_busqueda, $valor_busqueda);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $libros[] = $fila;
    }
    mysqli_stmt_close($stmt);
    return $libros;
}
?>
