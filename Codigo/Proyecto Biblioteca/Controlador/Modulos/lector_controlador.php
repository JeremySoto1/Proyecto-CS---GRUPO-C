<?php
require_once '../../Modelo/Modulos/lector_modelo.php';
require_once __DIR__ . '/../Session/validar_sesion.php';
require_once __DIR__.'/../Helpers/Servicios/lectorValidatorService.php';

// Crear instancia del validador
$validators = new LectorValidatorService($enlace);

//Obtiene los datos necesarios sobre lectores para la vista
function obtenerDatosLectores() {
    global $validators;
    $lectores = obtenerLectores();
    
    try {
        $validators->validarLectorExistente($lectores);
    } catch (LectorNoEncontradoException $e) {
        // No hacemos nada aquí, ya que es normal que no haya lectores al inicio
    }
    
    return [
        'lectores' => $lectores,
        'lectoresInactivos' => obtenerLectoresInactivos()
    ];
}

//Procesa las acciones enviadas por formulario (POST)
function procesarFormularioLector() {
    global $validators;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // ========= CREAR NUEVO LECTOR =========
            if (isset($_POST['guardar'])) {
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $cedula = $_POST['cedula'];
                $email = $_POST['email'];
                $telefono = $_POST['telefono'];
                $direccion = $_POST['direccion'];
                
                // Validar duplicados
                $validators->validarDuplicados($cedula, $email);
                
                // Intenta insertar y devuelve datos actualizados si tiene éxito
                if (insertarLector($nombre, $apellido, $cedula, $email, $telefono, $direccion)) {
                    return obtenerDatosLectores();
                }
            }
            
            // ========= MODIFICAR LECTOR EXISTENTE =========
            if (isset($_POST['modificar'])) {
                $lectorID = $_POST['lectorID'];
                $nombre = $_POST['nombre'];
                $apellido = $_POST['apellido'];
                $cedula = $_POST['cedula'];
                $email = $_POST['email'];
                $telefono = $_POST['telefono'];
                $direccion = $_POST['direccion'];
                
                // Validar duplicados (excluyendo el lector actual)
                $validators->validarDuplicados($cedula, $email, $lectorID);
                
                // Intenta modificar y devuelve datos actualizados si tiene éxito
                if (modificarLector($lectorID, $nombre, $apellido, $cedula, $email, $telefono, $direccion)) {
                    return obtenerDatosLectores();
                }
            }

            // ========= BUSCAR LECTORES =========
            if (isset($_POST['buscar'])) {
                $campo = $_POST['campo_busqueda'];
                $valor = $_POST['valor_busqueda'];
                
                $lectores = buscarLectores($campo, $valor);
                $validators->validarLectorExistente($lectores);
                
                return ['lectores' => $lectores];
            }
            
            // ========= DESACTIVAR LECTOR =========
            if (isset($_POST['desactivarID'])) {
                $lectorID = $_POST['desactivarID'];
                
                // Validar si el lector tiene préstamos pendientes
                               
                // Intenta desactivar y devuelve datos actualizados si tiene éxito
                if (desactivarLector($lectorID)) {
                    return obtenerDatosLectores();
                }
            }

            // ========= REACTIVAR LECTOR =========
            if (isset($_POST['reactivarID'])) {
                $lectorID = $_POST['reactivarID'];
                
                // Intenta reactivar y devuelve datos actualizados si tiene éxito
                if (reactivarLector($lectorID)) {
                    return obtenerDatosLectores();
                }
            }
            
        } catch (LectorDuplicadoException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        } catch (LectorNoEncontradoException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        } catch (LectorBloqueadoException $e) {
            $_SESSION['error_message'] = $e->getMessage();
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Ocurrió un error inesperado: " . $e->getMessage();
        }
    }
    return false;
}

// Obtener datos iniciales
$datos = obtenerDatosLectores();

// Procesar formulario
$procesado = procesarFormularioLector();

// Actualizar datos si hubo cambios
if (is_array($procesado)) {
    $datos = $procesado;
}

// Extraer variables para la vista
extract($datos);

// Incluir la vista
require_once '../../Vista/Modulos/lector_vista.php';