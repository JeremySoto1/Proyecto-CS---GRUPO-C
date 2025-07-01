<?php
require_once(__DIR__ . '/../excepciones/excepciones_bibliotecario.php');
require_once(__DIR__ . '/../../../Modelo/Config/conexion_be.php');

class BibliotecarioValidatorService {
    private $enlace;

    public function __construct($enlace) {
        $this->enlace = $enlace;
    }

    /**
     * Valida si un bibliotecario ya existe (por usuario o email)
     */
    public function validarDuplicados($usuario, $email, $bibliotecarioID = null) {
        // Validar usuario duplicado
        $usuarioExistente = $this->buscarPorUsuario($usuario);
        if ($usuarioExistente && $usuarioExistente['bibliotecarioID'] != $bibliotecarioID) {
            throw new BibliotecarioDuplicadoException("Nombre de usuario ya registrado");
        }

        // Validar email duplicado
        $emailExistente = $this->buscarPorEmail($email);
        if ($emailExistente && $emailExistente['bibliotecarioID'] != $bibliotecarioID) {
            throw new BibliotecarioDuplicadoException("Email ya registrado, por favor introduzca otro");
        }

        // Si ambos están duplicados (y son del mismo bibliotecario)
        if ($usuarioExistente && $emailExistente && 
            $usuarioExistente['bibliotecarioID'] == $emailExistente['bibliotecarioID'] && 
            $usuarioExistente['bibliotecarioID'] != $bibliotecarioID) {
            throw new BibliotecarioDuplicadoException("Email y nombre de usuario ya registrados, por favor ingrese otros");
        }
    }

    /**
     * Busca un bibliotecario por usuario
     */
    public function buscarPorUsuario($usuario) {
        $stmt = mysqli_prepare($this->enlace, "SELECT bibliotecarioID, usuario, email FROM bibliotecario WHERE usuario = ?");
        mysqli_stmt_bind_param($stmt, "s", $usuario);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($resultado);
    }

    /**
     * Busca un bibliotecario por email
     */
    public function buscarPorEmail($email) {
        $stmt = mysqli_prepare($this->enlace, "SELECT bibliotecarioID, usuario, email FROM bibliotecario WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($resultado);
    }

    /**
     * Valida si un bibliotecario existe al buscar
     */
    public function validarBibliotecarioExistente($bibliotecarios) {
        if (empty($bibliotecarios)) {
            throw new BibliotecarioNoEncontradoException("Bibliotecario no existe");
        }
    }

    /**
     * Valida si un bibliotecario está bloqueado (bloqueado = 1)
     */
    public function validarEstadoBibliotecario($bibliotecario) {
    // Verifica si la clave existe y tiene valor 1
    if (isset($bibliotecario['bloqueado']) && $bibliotecario['bloqueado'] == 1) {
        throw new BibliotecarioBloqueadoException("Bibliotecario no disponible");
    }
    
    // Opcional: Si quieres manejar el caso donde bloqueado no está definido
    /*if (!isset($bibliotecario['bloqueado'])) {
        
        throw new Exception("Estado del bibliotecario no disponible");
        return; // Asume que no está bloqueado si no hay información
    }*/
}
}