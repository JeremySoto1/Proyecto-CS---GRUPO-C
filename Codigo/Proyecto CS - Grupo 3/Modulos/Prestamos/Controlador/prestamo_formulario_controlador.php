<?php
require_once '../Modelo/prestamo_formulario_modelo.php';

if (isset($_GET['cedula'])) {
    header('Content-Type: application/json');
    $cedula = $_GET['cedula'];
    $lector = buscarLector($cedula);
    echo $lector ? json_encode($lector) : json_encode(['error' => 'Lector no encontrado']);
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
        header("Location: ../Vistas/modal_prestamo.php?mensaje=Préstamo+registrado");
    } else {
        header("Location: ../Vistas/modal_prestamo.php?error=Datos+incompletos");
    }
}

?>
