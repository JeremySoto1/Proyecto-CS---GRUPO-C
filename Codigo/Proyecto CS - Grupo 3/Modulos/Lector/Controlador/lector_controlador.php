<?php
require_once '../Modelo/lector_modelo.php';
require_once '../../Login/validar_sesion.php';


//Obtiene los datos necesarios osbre lectores para la vista
function obtenerDatosLectores() {
    return [
        'lectores' => obtenerLectores(), //Obtiene lectores activos
        'lectoresInactivos' => obtenerLectoresInactivos() //Obtiene lectores inactivos
    ];
}

//Procesa las acciones enviadas por formulario (POST)
function procesarFormularioLector() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

         // ========= CREAR NUEVO LECTOR =========
        if (isset($_POST['guardar'])) {
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $cedula = $_POST['cedula'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            
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
            
            // Intenta modificar y devuelve datos actualizados si tiene éxito
            if (modificarLector($lectorID, $nombre, $apellido, $cedula, $email, $telefono, $direccion)) {
                return obtenerDatosLectores();
            }
        }

        // ========= DESACTIVAR LECTOR =========
        if (isset($_POST['desactivarID'])) {
            $lectorID = $_POST['desactivarID'];
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

        // ========= BUSCAR LECTORES =========
        if (isset($_POST['buscar'])) {
            $campo = $_POST['campo_busqueda'];// Campo por el que buscar
            $valor = $_POST['valor_busqueda']; // Valor a buscar
            // Devuelve solo los resultados de búsqueda (sin lectores inactivos)
            return ['lectores' => buscarLectores($campo, $valor)];
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
require_once '../Vista/lector_vista.php';
?>