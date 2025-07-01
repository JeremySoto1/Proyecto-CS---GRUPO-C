<?php
require_once __DIR__ . '/../Config/conexion_be.php';//Llamada al archivo de conexión de la BD

class ControlModelo {
    //Método para buscar multas asociadas a un lector por su número de cédula
    public static function buscarMultaPorCedula($cedula) {
        global $enlace;//Conexión a la BD
        
        $stmt = mysqli_prepare($enlace, "CALL sp_buscar_multa(?)");//Llamada al procedimiento almacenado
        mysqli_stmt_bind_param($stmt, "s", $cedula);
        mysqli_stmt_execute($stmt);// Ejecuta la consulta

        $lectorResult = mysqli_stmt_get_result($stmt);// Obtiene el primer resultado (datos del lector)
        $lector = mysqli_fetch_assoc($lectorResult);

        mysqli_stmt_next_result($stmt);
        $multasResult = mysqli_stmt_get_result($stmt);
        $multas = mysqli_fetch_all($multasResult, MYSQLI_ASSOC);

        mysqli_stmt_close($stmt);

        return [// Retorna ambos conjuntos de datos
            "lector" => $lector,
            "multas" => $multas
        ];
    }

    //Método para cancelar una multa específica
    public static function cancelarMulta($multaID) {
        global $enlace;//Conexión a la BD
        
        $stmt = mysqli_prepare($enlace, "CALL sp_cancelar_multa(?)");//Llamada al procedimiento almacenado
        mysqli_stmt_bind_param($stmt, "i", $multaID);
        mysqli_stmt_execute($stmt);// Ejecuta la consulta
        $result = mysqli_stmt_get_result($stmt)->fetch_assoc();// Obtiene el resultado (normalmente confirmación de operación)
        mysqli_stmt_close($stmt);
        
        return $result;// Retorna el resultado de la operación
    }

    //Método para bloquear un lector en el sistema
    public static function bloquearLector($lectorID) {
        global $enlace;//Conexión a la BD
        
        $stmt = mysqli_prepare($enlace, "CALL sp_bloquear_lector(?)");//Llamada al procedimiento almacenado
        mysqli_stmt_bind_param($stmt, "i", $lectorID);
        mysqli_stmt_execute($stmt);// Ejecuta la consulta
        $result = mysqli_stmt_get_result($stmt)->fetch_assoc();  // Obtiene el resultado
        mysqli_stmt_close($stmt);
        
        return $result;// Retorna el resultado de la operación
    }

    //Método para desbloquear un lector previamente bloqueado
    public static function desbloquearLector($lectorID) {
        global $enlace;//Conexión a la BD
        
        $stmt = mysqli_prepare($enlace, "CALL sp_desbloquear_lector(?)");//Llamada al procedimiento almacenado
        mysqli_stmt_bind_param($stmt, "i", $lectorID);
        mysqli_stmt_execute($stmt);// Ejecuta la consulta
        $result = mysqli_stmt_get_result($stmt)->fetch_assoc();  // Obtiene el resultado
        mysqli_stmt_close($stmt);
        
        return $result;// Retorna el resultado de la operación
    }
}