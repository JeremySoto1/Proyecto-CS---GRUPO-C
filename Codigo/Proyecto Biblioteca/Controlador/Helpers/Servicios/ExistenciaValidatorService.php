<?php
require_once(__DIR__ . '/../excepciones/excepciones_existencia.php');
require_once(__DIR__ . '/../../../Modelo/Config/conexion_be.php');

class ExistenciaValidatorService {
    private $enlace;

    public function __construct($enlace) {
        $this->enlace = $enlace;
    }

    
    public function validarDatosExistencia($libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID) {
        $this->validarLibro($libroID);
        $this->validarUbicacion($ubicacionID);
        $this->validarEstadoExistencia($estadoExistenciaID);
        $this->validarDisponibilidad($disponibilidadExistenciaID);
        $this->verificarExistenciaDuplicada($libroID, $ubicacionID);
    }

    private function validarLibro($libroID) {
        $stmt = mysqli_prepare($this->enlace, "SELECT libroID FROM libro WHERE libroID = ?");
        mysqli_stmt_bind_param($stmt, "i", $libroID);
        mysqli_stmt_execute($stmt);
        
        if (!mysqli_stmt_fetch($stmt)) {
            throw new LibroNoDisponibleException();
        }
        mysqli_stmt_close($stmt);
    }

    private function validarUbicacion($ubicacionID) {
        $stmt = mysqli_prepare($this->enlace, "SELECT ubicacionID FROM ubicacion WHERE ubicacionID = ?");
        mysqli_stmt_bind_param($stmt, "i", $ubicacionID);
        mysqli_stmt_execute($stmt);
        
        if (!mysqli_stmt_fetch($stmt)) {
            throw new UbicacionInvalidaException();
        }
        mysqli_stmt_close($stmt);
    }

    private function validarEstadoExistencia($estadoID) {
        $stmt = mysqli_prepare($this->enlace, "SELECT estadoID FROM estado_existencia WHERE estadoID = ?");
        mysqli_stmt_bind_param($stmt, "i", $estadoID);
        mysqli_stmt_execute($stmt);
        
        if (!mysqli_stmt_fetch($stmt)) {
            throw new EstadoInvalidoException();
        }
        mysqli_stmt_close($stmt);
    }

    private function validarDisponibilidad($disponibilidadID) {
        $stmt = mysqli_prepare($this->enlace, "SELECT disponibilidadID FROM disponibilidad WHERE disponibilidadID = ?");
        mysqli_stmt_bind_param($stmt, "i", $disponibilidadID);
        mysqli_stmt_execute($stmt);
        
        if (!mysqli_stmt_fetch($stmt)) {
            throw new DisponibilidadInvalidaException();
        }
        mysqli_stmt_close($stmt);
    }

    private function verificarExistenciaDuplicada($libroID, $ubicacionID) {
        $stmt = mysqli_prepare($this->enlace, "SELECT existenciaID FROM existencia WHERE libroID = ? AND ubicacionID = ?");
        mysqli_stmt_bind_param($stmt, "ii", $libroID, $ubicacionID);
        mysqli_stmt_execute($stmt);
        
        if (mysqli_stmt_fetch($stmt)) {
            throw new ExistenciaDuplicadaException();
        }
        mysqli_stmt_close($stmt);
    }
}
?>