<?php
session_start();
require_once __DIR__ . '/../../Modelo/Modulos/devolucion_modelo.php';

// Verificar si es AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Método no permitido", 405);
    }

    // Obtener parámetros
    $prestamoID = filter_input(INPUT_POST, 'prestamoID', FILTER_VALIDATE_INT);
    $estadoExistencia = filter_input(INPUT_POST, 'estado_existencia', FILTER_VALIDATE_INT);
    $disponibilidad = filter_input(INPUT_POST, 'disponibilidad', FILTER_VALIDATE_INT);
    $motivo_multa = ($estadoExistencia == 1) ? trim($_POST['motivo_multa'] ?? '') : null;

    // Validaciones básicas
    if (!$prestamoID || !$estadoExistencia || !$disponibilidad) {
        throw new Exception("Datos incompletos o inválidos", 400);
    }

    // Registrar devolución (ya no necesitamos enviar existenciaID)
    $resultado = registrarDevolucionCompleta(
        $prestamoID,
        $estadoExistencia,
        $disponibilidad,
        $motivo_multa
    );

    if (!$resultado) {
        throw new Exception("No se pudo registrar la devolución", 500);
    }

    $response = [
        'success' => true,
        'message' => 'Devolución registrada correctamente',
        'prestamoID' => $prestamoID
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => $e->getMessage(),
        'code' => $e->getCode()
    ];
}

header('Content-Type: application/json');
echo json_encode($response);
exit;