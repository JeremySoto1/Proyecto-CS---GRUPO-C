<?php
include '../../../Config/conexion_be.php';

function insertarPrestamo($fecha_prestamo, $fecha_devolucion, $lectorID, $existenciaID) {
    global $enlace;
    $stmt = $enlace->prepare("CALL sp_insertar_prestamo_y_reserva(?, ?, ?, ?)");
    if (!$stmt) {
        die("Error en prepare: " . $enlace->error);
    }
    $stmt->bind_param("ssii", $fecha_prestamo, $fecha_devolucion, $lectorID, $existenciaID);
    if (!$stmt->execute()) {
        die("Error al ejecutar sp_insertar_prestamo_y_reserva: " . $stmt->error);
    }
    $stmt->close();
}




function buscarExistenciasLibro($busqueda) {
    global $enlace;
    $existencias = [];
    $stmt = $enlace->prepare("CALL sp_buscar_existencias_libro(?)");
    $stmt->bind_param("s", $busqueda);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $existencias[] = $row;
    }
    $stmt->close();
    return $existencias;
}

function buscarLector($cedula) {
    global $enlace;
    $cedula = mysqli_real_escape_string($enlace, trim($cedula));
    $stmt = $enlace->prepare("CALL sp_buscar_lector(?)");
    $stmt->bind_param("s", $cedula);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($lector = $res->fetch_assoc()) {
        return [
            'id' => $lector['lectorID'],
            'nombre' => $lector['nombre'],
            'email' => $lector['email']
        ];
    }
    $stmt->close();
    return null;
}
?>
