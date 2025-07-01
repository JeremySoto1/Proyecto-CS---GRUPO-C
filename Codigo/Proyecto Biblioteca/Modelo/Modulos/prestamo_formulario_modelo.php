<?php
require_once __DIR__ . '/../Config/conexion_be.php';//Llamada al archivo de conexión de BD

//Metodo para insetar un prestamo en la BD
function insertarPrestamo($fecha_prestamo, $fecha_devolucion, $lectorID, $existenciaID) {
    global $enlace;//Conexión a BD
    $stmt = $enlace->prepare("CALL sp_insertar_prestamo_y_reserva(?, ?, ?, ?)");//Llamada al procedimiento almacenado
    if (!$stmt) {
        die("Error en prepare: " . $enlace->error);
    }
    $stmt->bind_param("ssii", $fecha_prestamo, $fecha_devolucion, $lectorID, $existenciaID);
    if (!$stmt->execute()) {//Ejecuta el procedimiento almacenado
        die("Error al ejecutar sp_insertar_prestamo_y_reserva: " . $stmt->error);
    }
    $stmt->close();
}

//Método para buscar exitencias de libro
function buscarExistenciasLibro($busqueda) {
    global $enlace;//Conexión a la BD
    $existencias = [];
    $stmt = $enlace->prepare("CALL sp_buscar_existencias_libro(?)");//Llamada al procedimiento almacenado
    $stmt->bind_param("s", $busqueda);
    $stmt->execute();//Ejecuta el procedimiento
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $existencias[] = $row;
    }
    $stmt->close();
    return $existencias;
}

//Método para buscar lectores
function buscarLector($cedula) {
    global $enlace;//Conexión a la BD
    $cedula = mysqli_real_escape_string($enlace, trim($cedula));
    $stmt = $enlace->prepare("CALL sp_buscar_lector(?)");//Llamada al procedimiento almacenado
    $stmt->bind_param("s", $cedula);
    $stmt->execute();//Ejecución del procedimiento almacenado
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