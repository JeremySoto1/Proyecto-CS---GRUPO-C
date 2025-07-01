<?php
require_once '../../Modelo/Modulos/bibliotecario_modelo.php';
require_once __DIR__ . '/../Session/validar_sesion.php';
require_once __DIR__.'/../Helpers/Servicios/BibliotecarioValidatorService.php';

// Crear instancia del validador
$validador = new BibliotecarioValidatorService($enlace);

//Obtiene los datos iniciales de bibliotecarios para la vista
function obtenerDatosBibliotecarios() {
    global $validador;
    $bibliotecarios = obtenerBibliotecarios();
    
    try {
        $validador->validarBibliotecarioExistente($bibliotecarios);
    } catch (BibliotecarioNoEncontradoException $e) {
        // No hacemos nada aquí, ya que es normal que no haya bibliotecarios al inicio
    }
    
    return ['bibliotecarios' => $bibliotecarios];
}

//Procesa las acciones enviadas por formulario (POST)
function procesarFormularioBibliotecario() {
    global $validador;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // ========= CREAR NUEVO BIBLIOTECARIO =========
            if (isset($_POST['guardar'])) {
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $email = $_POST['email'];
                $usuario = $_POST['usuario'];
                $contrasenia = $_POST['contrasenia'];
                
                // Validar duplicados
                $validador->validarDuplicados($usuario, $email);
                
                if (insertarBibliotecario($nombre, $apellido, $email, $usuario, $contrasenia)) {
                    return obtenerDatosBibliotecarios();
                }
            }

            // ========= MODIFICAR BIBLIOTECARIO =========
            if (isset($_POST['modificar'])) {
                $bibliotecarioID = $_POST['bibliotecarioID'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $email = $_POST['email'];
                $usuario = $_POST['usuario'];
                $contrasenia = $_POST['contrasenia'] ?? '';
                
                // Validar duplicados (excluyendo el bibliotecario actual)
                $validador->validarDuplicados($usuario, $email, $bibliotecarioID);
                
                if (modificarBibliotecario($bibliotecarioID, $nombre, $apellido, $email, $usuario, $contrasenia)) {
                    return obtenerDatosBibliotecarios();
                }
            }

            // ========= ELIMINAR BIBLIOTECARIO =========
            if (isset($_POST['eliminarID'])) {
                $bibliotecarioID = $_POST['eliminarID'];
                if (eliminarBibliotecario($bibliotecarioID)) {
                    return obtenerDatosBibliotecarios();
                }
            }

            // ========= BUSCAR BIBLIOTECARIOS =========
            if (isset($_POST['buscar'])) {
                $campo = $_POST['campo_busqueda'];
                $valor = $_POST['valor_busqueda'];
                
                $bibliotecarios = buscarBibliotecarios($campo, $valor);
                
                // Validar existencia y estado
                $validador->validarBibliotecarioExistente($bibliotecarios);
                
                // Si solo hay un resultado, validar su estado
                if (count($bibliotecarios) === 1) {
                    $validador->validarEstadoBibliotecario($bibliotecarios[0]);
                }
                
                return ['bibliotecarios' => $bibliotecarios];
            }
            
        } catch (BibliotecarioDuplicadoException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        } catch (BibliotecarioNoEncontradoException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        } catch (BibliotecarioBloqueadoException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Ocurrió un error inesperado: " . $e->getMessage();
        }
    }
    return false;
}

// Obtener datos iniciales
$datos = obtenerDatosBibliotecarios();

// Procesar formulario
$procesado = procesarFormularioBibliotecario();

// Actualizar datos si hubo cambios
if (is_array($procesado)) {
    $datos = $procesado;
}

// Extraer variables para la vista
extract($datos);

// Incluir la vista
require_once '../../Vista/Modulos/bibliotecario_vista.php';