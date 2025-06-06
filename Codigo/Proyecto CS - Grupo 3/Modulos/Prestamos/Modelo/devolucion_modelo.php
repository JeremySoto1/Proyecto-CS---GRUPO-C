<?php
include '../../../Config/conexion_be.php';

function registrarDevolucionCompleta($prestamoID, $estadoExistenciaID, $disponibilidadID, $motivo_multa = null) {
    global $enlace;
    
    try {
        if (!$enlace) {
            throw new Exception("Error de conexión a la base de datos");
        }

        // Llamar al procedimiento almacenado
        $query = "CALL sp_registrar_devolucion(?, ?, ?, ?)";
        $stmt = mysqli_prepare($enlace, $query);
        
        if (!$stmt) {
            throw new Exception("Error preparando la consulta: " . mysqli_error($enlace));
        }
        
        mysqli_stmt_bind_param($stmt, "iiis", 
            $prestamoID, 
            $estadoExistenciaID, 
            $disponibilidadID, 
            $motivo_multa);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error ejecutando procedimiento: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
        return true;
        
    } catch (Exception $e) {
        error_log("Error en modelo de devolución: " . $e->getMessage());
        return false;
    }
}