<?php
require_once '../../../Config/conexion_be.php';
class ControlModelo {

public static function buscarMultaPorCedula($cedula) {
        global $enlace;
        $stmt = $enlace->prepare("CALL sp_buscar_multa(?)");
        $stmt->bind_param("s", $cedula);
        $stmt->execute();

        $lectorResult = $stmt->get_result();
        $lector = $lectorResult->fetch_assoc();

        $stmt->next_result();
        $multasResult = $stmt->get_result();
        $multas = $multasResult->fetch_all(MYSQLI_ASSOC);

        return [
            "lector" => $lector,
            "multas" => $multas
        ];
    }

    public static function cancelarMulta($multaID) {
        global $enlace;
        $stmt = $enlace->prepare("CALL sp_cancelar_multa(?)");
        $stmt->bind_param("i", $multaID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    public static function bloquearLector($lectorID) {
        global $enlace;
        $stmt = $enlace->prepare("CALL sp_bloquear_lector(?)");
        $stmt->bind_param("i", $lectorID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }

    public static function desbloquearLector($lectorID) {
        global $enlace;
        $stmt = $enlace->prepare("CALL sp_desbloquear_lector(?)");
        $stmt->bind_param("i", $lectorID);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result;
    }
}
?>