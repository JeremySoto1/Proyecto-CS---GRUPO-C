<?php
require_once(__DIR__ . '/../excepciones/excepciones_cubiculo.php');
require_once(__DIR__ . '/../../../Modelo/Config/conexion_be.php');

class CubiculoValidatorService {
    private $enlace;
    
    public function __construct($enlace) {
        $this->enlace = $enlace;
    }

    /**
     * Valida si un lector existe
     */
    public function validarLectorExistente($lector) {
        if (!$lector) {
            throw new LectorNoEncontradoException("Lector no existe");
        }
    }

    /**
     * Valida si un lector está disponible (estadoLectorID = 1)
     */
    public function validarEstadoLector($lector) {
        if ($lector['estadoLectorID'] == 2) {
            throw new LectorBloqueadoException("No se puede alquilar a un lector con multas activas");
        }
    }

    /**
     * Valida si un cubículo está disponible
     */
    public function validarDisponibilidadCubiculo($cubiculoID) {
        $stmt = mysqli_prepare($this->enlace, "SELECT disponibilidadID FROM cubiculo WHERE cubiculoID = ?");
        mysqli_stmt_bind_param($stmt, "i", $cubiculoID);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $cubiculo = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($stmt);

        if ($cubiculo['disponibilidadID'] != 1) {
            throw new CubiculoNoDisponibleException("El cubículo no está disponible");
        }
    }
}