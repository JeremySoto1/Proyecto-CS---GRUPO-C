<?php
session_start();
header('Content-Type: application/json');
require_once("../../Modelo/Modulos/control_modelo.php");
require_once __DIR__.'/../Helpers/Servicios/ControlValidatorService.php';

// Crear instancia del validador
$validator = new ControlValidatorService($enlace);
$modelo = new ControlModelo();

$accion = $_POST['accion'] ?? '';

try {
    switch ($accion) {
        case 'buscarMulta':
            $cedula = $_POST['cedula'] ?? '';
            if (empty($cedula)) {
                throw new Exception("Debe ingresar una cédula");
            }
            
            $resultado = $modelo->buscarMultaPorCedula($cedula);
            
            
            // Validar existencia del lector
            $validator->validarLectorExistente($resultado['lector']);
            
            
            echo json_encode($resultado);
            break;

        case 'cancelarMulta':
            $multaID = $_POST['multaID'] ?? 0;
            if (empty($multaID)) {
                throw new Exception("No se especificó la multa a cancelar");
            }
            
            $filas = $modelo->cancelarMulta($multaID);
            echo json_encode(["filasAfectadas" => $filas]);
            break;

        case 'bloquearLector':
            $lectorID = $_POST['lectorID'] ?? 0;
            if (empty($lectorID)) {
                throw new Exception("No se especificó el lector a bloquear");
            }
            
            $modelo->bloquearLector($lectorID);
            echo json_encode(["mensaje" => "Lector bloqueado"]);
            break;

        case 'desbloquearLector':
            $lectorID = $_POST['lectorID'] ?? 0;
            if (empty($lectorID)) {
                throw new Exception("No se especificó el lector a desbloquear");
            }
            
            $modelo->desbloquearLector($lectorID);
            echo json_encode(["mensaje" => "Lector desbloqueado"]);
            break;

        default:
            throw new Exception("Acción no reconocida");
            break;
    }
} catch (LectorNoEncontradoException $e) {
    echo json_encode(["error" => $e->getMessage()]);
} catch (MultasNoEncontradasException $e) {
    echo json_encode(["info" => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}