<?php
require_once '../Modelo/libros_modelo.php';
require_once '../../Login/validar_sesion.php';

function obtenerDatosLibros() {
    return [
        'libros' => obtenerLibros(),
        'librosDeshabilitados' => obtenerLibrosDeshabilitados(),
        'librosHabilitados' => obtenerLibrosHabilitados()
    ];
}

function procesarFormularioLibros() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['guardar'])) {
            $title = $_POST['title'];
            $author = $_POST['author'];
            $year = $_POST['year'];
            $pages_no = $_POST['pages_no'];
            $genderID = $_POST['gender'];
            if (insertarLibro($title, $author, $year, $pages_no, $genderID)) {
                return obtenerDatosLibros(); // Devuelve datos actualizados
            }
        }

        if (isset($_POST['modificar'])) {
            $libroID = $_POST['libroID'];
            $title = $_POST['title'];
            $author = $_POST['author'];
            $year = $_POST['year'];
            $pages_no = $_POST['pages_no'];
            $genderID = $_POST['gender'];
            if (modificarLibro($libroID, $title, $author, $year, $pages_no, $genderID)) {
                return obtenerDatosLibros(); // Devuelve datos actualizados
            }
        }

        if (isset($_POST['eliminarID'])) {
            $libroID = $_POST['eliminarID'];
            if (eliminarLibro($libroID)) {
                return obtenerDatosLibros(); // Devuelve datos actualizados
            }
        }

        if (isset($_POST['estadoID']) && isset($_POST['nuevoEstado'])) {
            $libroID = $_POST['estadoID'];
            $nuevoEstado = $_POST['nuevoEstado']; 
            if (cambiarEstadoLibro($libroID, $nuevoEstado)) {
                return obtenerDatosLibros(); // Devuelve datos actualizados
            }
        }

        if (isset($_POST['buscar'])) {
            $campo_busqueda = $_POST['campo_busqueda'];
            $valor_busqueda = $_POST['valor_busqueda'];
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