<?php
require_once __DIR__ . '/../../Modelo/Modulos/prestamo_formulario_modelo.php';

if (!isset($enlace) || $enlace->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => 'Error de conexión a la BD']));
}

if (isset($_GET['cedula'])) {
    header('Content-Type: application/json');
    $cedula = $_GET['cedula'];
    $resultado = buscarLector($cedula);
    
    // Maneja el caso cuando no se encuentra el lector
    if ($resultado === null) {
        echo json_encode(['error' => 'Lector no encontrado']);
    } else {
        echo json_encode($resultado);
    }
    exit;
}

if (isset($_GET['buscarLibro'])) {
    header('Content-Type: application/json');
    $busqueda = $_GET['buscarLibro'];
    $existencias = buscarExistenciasLibro($busqueda);
    echo json_encode($existencias);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_devolucion = $_POST['fecha_limite'];
    $lectorID = $_POST['lectorID'];
    $existencias = $_POST['existencias'] ?? [];  // Array de existenciaID

    if ($fecha_prestamo && $fecha_devolucion && $lectorID && !empty($existencias)) {
        foreach ($existencias as $existenciaID) {
            insertarPrestamo($fecha_prestamo, $fecha_devolucion, $lectorID, $existenciaID);
        }
        header("Location: ../../Vista/Modulos/prestamos.php?");
    } else {
        header("Location: ../../Vista/Modulos/prestamos.php?");
    }
}

?>
