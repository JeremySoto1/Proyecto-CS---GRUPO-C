<?php
require_once '../Modelo/libros_modelo.php';// Incluye el archivo del modelo para operaciones con libros
require_once '../../Login/validar_sesion.php';// Incluye el archivo para validar la sesión del usuario

/**
 * Función que obtiene todos los datos de libros necesarios para la vista
 * @return array Arreglo con tres conjuntos de datos:
 *               - Todos los libros
 *               - Libros deshabilitados
 *               - Libros habilitados
 */
function obtenerDatosLibros() {
    return [
        'libros' => obtenerLibros(),
        'librosDeshabilitados' => obtenerLibrosDeshabilitados(),
        'librosHabilitados' => obtenerLibrosHabilitados()
    ];
}

function procesarFormularioLibros() {
    // Solo procesa si es una petición POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // ========= CREAR NUEVO LIBRO =========
        if (isset($_POST['guardar'])) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $year = $_POST['year'];
            $pages_no = $_POST['pages_no'];
            $genderID = $_POST['gender'];
            
            // Intenta insertar el libro y devuelve datos actualizados si tiene éxito
            if (insertarLibro($title, $author, $year, $pages_no, $genderID)) {
                return obtenerDatosLibros(); // Devuelve datos actualizados
            }
        }
        // ========= MODIFICAR LIBRO EXISTENTE =========
        if (isset($_POST['modificar'])) {
            $libroID = $_POST['libroID'];
            $title = $_POST['title'];
            $author = $_POST['author'];
            $year = $_POST['year'];
            $pages_no = $_POST['pages_no'];
            $genderID = $_POST['gender'];
            // Intenta modificar el libro y devuelve datos actualizados si tiene éxito
            if (modificarLibro($libroID, $title, $author, $year, $pages_no, $genderID)) {
                return obtenerDatosLibros(); // Devuelve datos actualizados
            }
        }
        // ========= ELIMINAR LIBRO =========
        if (isset($_POST['eliminarID'])) {
            $libroID = $_POST['eliminarID'];
            // Intenta eliminar el libro y devuelve datos actualizados si tiene éxito
            if (eliminarLibro($libroID)) {
                return obtenerDatosLibros(); // Devuelve datos actualizados
            }
        }

        // ========= CAMBIAR ESTADO (HABILITAR/DESHABILITAR) =========
        if (isset($_POST['estadoID']) && isset($_POST['nuevoEstado'])) {
            $libroID = $_POST['estadoID'];
            $nuevoEstado = $_POST['nuevoEstado']; 
            // Intenta cambiar el estado del libro si tiene exito
            if (cambiarEstadoLibro($libroID, $nuevoEstado)) {
                return obtenerDatosLibros(); // Devuelve datos actualizados
            }
        }
        // ========= BUSCAR LIBROS =========
        if (isset($_POST['buscar'])) {
            $campo_busqueda = $_POST['campo_busqueda'];// Campo por el que buscar
            $valor_busqueda = $_POST['valor_busqueda'];// Valor a buscar
            // Devuelve resultados de búsqueda manteniendo los otros conjuntos de datos
            return [
                'libros' => buscarLibros($campo_busqueda, $valor_busqueda),
                'librosDeshabilitados' => obtenerLibrosDeshabilitados(),
                'librosHabilitados' => obtenerLibrosHabilitados()
            ];
        }
    }
    return false;
}

// Obtener datos iniciales
$datos = obtenerDatosLibros();

// Procesar formulario
$procesado = procesarFormularioLibros();

// Actualizar datos si hubo cambios
if (is_array($procesado)) {
    $datos = $procesado;
}

// Extraer variables para la vista
extract($datos);

// Incluir la vista
require_once '../Vista/libros_vista.php';
?>