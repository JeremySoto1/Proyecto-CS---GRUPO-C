<?php
require_once(__DIR__ . '/../excepciones/excepciones_control.php');
require_once(__DIR__ . '/../../../Modelo/Config/conexion_be.php');

class ControlValidatorService {
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
     * Valida si un lector tiene multas
     */
    public function validarMultasLector($multas) {
        if (empty($multas)) {
            throw new MultasNoEncontradasException("Este lector no tiene multas");
        }
    }
}