<?php
require_once '../../Modelo/Modulos/libros_modelo.php';// Incluye el archivo del modelo para operaciones con libros
require_once __DIR__ . '/../Session/validar_sesion.php';// Incluye el archivo para validar la sesión del usuario
require_once __DIR__.'/../Helpers/excepciones/excepciones_libro.php';
require_once __DIR__.'/../Helpers/Servicios/LibroValidatorService.php';

/**
 * Obtiene todos los datos necesarios para la vista
 */
function obtenerDatosLibros() {
    try {
        return [
            'libros' => obtenerLibros(),
            'librosDeshabilitados' => obtenerLibrosDeshabilitados(),
            'librosHabilitados' => obtenerLibrosHabilitados()
        ];
    } catch (LibroNoEncontradoException $e) {
        // Si no hay libros, devolver arrays vacíos
        return ['libros' => [], 'librosDeshabilitados' => [], 'librosHabilitados' => []];
    } catch (Exception $e) {
        throw $e;
    }
}

/**
 * Procesa el formulario de libros
 */
function procesarFormularioLibros() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Crear nuevo libro
            if (isset($_POST['guardar'])) {
                $required = ['title', 'author', 'year', 'pages_no', 'gender'];
                $missing = array_diff($required, array_keys($_POST));
                
                if (!empty($missing)) {
                    throw new DatosLibroInvalidosException("Faltan campos requeridos: " . implode(', ', $missing));
                }
                
                // Convertir a entero para asegurar el tipo
                $genderID = (int)$_POST['gender'];

                if (insertarLibro(
                    $_POST['title'],
                    $_POST['author'],
                    $_POST['year'],
                    $_POST['pages_no'],
                    $genderID
                )) {
                    return obtenerDatosLibros();
                }
            }
            
            // Modificar libro existente
            if (isset($_POST['modificar'])) {
                if (modificarLibro(
                    $_POST['libroID'],
                    $_POST['title'],
                    $_POST['author'],
                    $_POST['year'],
                    $_POST['pages_no'],
                    $_POST['gender']
                )) {
                    return obtenerDatosLibros();
                }
            }
            
            // Eliminar libro
            if (isset($_POST['eliminarID'])) {
                if (eliminarLibro($_POST['eliminarID'])) {
                    return obtenerDatosLibros();
                }
            }
            
            // Cambiar estado
            if (isset($_POST['estadoID']) && isset($_POST['nuevoEstado'])) {
                if (cambiarEstadoLibro($_POST['estadoID'], $_POST['nuevoEstado'])) {
                    return obtenerDatosLibros();
                }
            }
            
            // Buscar libros
            if (isset($_POST['buscar'])) {
                return [
                    'libros' => buscarLibros($_POST['campo_busqueda'], $_POST['valor_busqueda']),
                    'librosDeshabilitados' => obtenerLibrosDeshabilitados(),
                    'librosHabilitados' => obtenerLibrosHabilitados()
                ];
            }
            
        } catch (LibroDuplicadoException $e) {   
            header("Location: ../../../Vista/Modulos/libros_vista.php?error=".urlencode($e->getMessage())."&code=".$e->getCode());
            exit();
        } catch (DatosLibroInvalidosException $e) {
            header("Location: ../../../Vista/Modulos/libros_vista.php?error=".urlencode($e->getMessage())."&code=".$e->getCode());
            exit();
        } catch (LibroPrestadoException $e) {
            header("Location: ../../../Vista/Modulos/libros_vista.php?error=".urlencode($e->getMessage())."&code=".$e->getCode());
            exit();
        /*} catch (DatabaseException $e) {
            error_log("Error de BD: " . $e->getMessage());
            header("Location: ../Vista/libros_vista.php?codigo=500");
            exit();*/
        } catch (Exception $e) {
            header("Location: ../../Vista/Modulos/libros_vista.php?error=".urlencode($e->getMessage())."&code=".$e->getCode());
exit();
            exit();
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
require_once '../../Vista/Modulos/libros_vista.php';
?>