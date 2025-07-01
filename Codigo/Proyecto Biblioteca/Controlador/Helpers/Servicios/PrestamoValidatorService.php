<?php
require_once(__DIR__ . '/../excepciones/excepciones_prestamo.php');
require_once __DIR__ . '/../../../Modelo/Config/conexion_be.php';

class PrestamoValidatorService {
    private $enlace;
    
    public function __construct($enlace) {
        $this->enlace = $enlace;
    }

    /**
     * Valida si un lector existe
     */
    public function validarLectorExistente($lector) {
        if (!$lector) {
            throw new LectorNoEncontradoException("Usuario no existe");
        }
    }

    /**
     * Valida si un lector está disponible (estadoLectorID = 1)
     */
    public function validarEstadoLector($lector) {
        if ($lector['estadoLectorID'] == 2) {
            throw new LectorBloqueadoException("Usuario no disponible por multas activas");
        }
    }

    /**
     * Valida si un lector tiene préstamos
     */
    public function validarPrestamosLector($prestamos) {
        if (empty($prestamos)) {
            throw new PrestamoNoEncontradoException("Usuario no tiene préstamos");
        }
    }

    /**
     * Valida si existen existencias disponibles para préstamo
     */
    public function validarExistenciasDisponibles($existencias) {
        $todasNoDisponibles = true;
        foreach ($existencias as $existencia) {
            if ($existencia['estadoExistenciaID'] != 2) {
                $todasNoDisponibles = false;
                break;
            }
        }

        if ($todasNoDisponibles) {
            throw new ExistenciaNoDisponibleException("No se encuentran existencias disponibles para préstamo");
        }
    }
}