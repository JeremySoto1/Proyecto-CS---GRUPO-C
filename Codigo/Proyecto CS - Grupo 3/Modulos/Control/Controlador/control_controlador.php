<?php
header('Content-Type: application/json');
require_once("../Modelo/control_modelo.php");

$modelo = new ControlModelo();

$accion = $_POST['accion'] ?? '';

switch ($accion) {
    case 'buscarMulta':
        $cedula = $_POST['cedula'] ?? '';
        $resultado = $modelo->buscarMultaPorCedula($cedula);
        echo json_encode($resultado);
        break;

    case 'cancelarMulta':
        $multaID = $_POST['multaID'] ?? 0;
        $filas = $modelo->cancelarMulta($multaID);
        echo json_encode(["filasAfectadas" => $filas]);
        break;

    case 'bloquearLector':
        $lectorID = $_POST['lectorID'] ?? 0;
        $modelo->bloquearLector($lectorID);
        echo json_encode(["mensaje" => "Lector bloqueado"]);
        break;

    case 'desbloquearLector':
        $lectorID = $_POST['lectorID'] ?? 0;
        $modelo->desbloquearLector($lectorID);
        echo json_encode(["mensaje" => "Lector desbloqueado"]);
        break;

    default:
        echo json_encode(["error" => "Acción no reconocida"]);
        break;
}
