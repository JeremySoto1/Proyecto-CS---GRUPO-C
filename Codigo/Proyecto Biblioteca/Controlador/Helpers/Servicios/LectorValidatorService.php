<?php
require_once(__DIR__ . '/../excepciones/excepciones_lector.php');
require_once(__DIR__ . '/../../../Modelo/Config/conexion_be.php');

class LectorValidatorService {
    private $enlace;

    public function __construct($enlace) {
        $this->enlace = $enlace;
    }

    /**
     * Valida si un lector ya existe (por cédula o email)
     */
    public function validarDuplicados($cedula, $email, $lectorID = null) {
        // Validar cédula duplicada
        $cedulaExistente = $this->buscarPorCedula($cedula);
        if ($cedulaExistente && $cedulaExistente['lectorID'] != $lectorID) {
            throw new LectorDuplicadoException("Número de cédula ya registrado");
        }

        // Validar email duplicado
        $emailExistente = $this->buscarPorEmail($email);
        if ($emailExistente && $emailExistente['lectorID'] != $lectorID) {
            throw new LectorDuplicadoException("Email ya registrado");
        }

        // Si ambos están duplicados (y son de diferentes lectores)
        if ($cedulaExistente && $emailExistente && 
            $cedulaExistente['lectorID'] == $emailExistente['lectorID'] && 
            $cedulaExistente['lectorID'] != $lectorID) {
            throw new LectorDuplicadoException("Email y cédula ya registrados");
        }
    }

    /**
     * Busca un lector por cédula
     */
    public function buscarPorCedula($cedula) {
        $stmt = mysqli_prepare($this->enlace, "SELECT lectorID, cedula, email FROM lector WHERE cedula = ?");
        mysqli_stmt_bind_param($stmt, "s", $cedula);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($resultado);
    }

    /**
     * Busca un lector por email
     */
    public function buscarPorEmail($email) {
        $stmt = mysqli_prepare($this->enlace, "SELECT lectorID, cedula, email FROM lector WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($resultado);
    }

    /**
     * Valida si un lector existe al buscar
     */
    public function validarLectorExistente($lectores) {
        if (empty($lectores)) {
            throw new LectorNoEncontradoException("Lector no existe");
        }
    }

    /**
     * Valida si un lector está bloqueado (estadoLectorID = 2)
     */
    public function validarEstadoLector($lector) {
        if ($lector['estadoLectorID'] == 2) {
            throw new LectorBloqueadoException("Lector no disponible por multa no pagada");
        }
    }
}