<?php
// Excepción para usuario no existente
class UsuarioNoExistenteException extends Exception {
    public function __construct($message = "El usuario no existe, ingrese un usuario válido", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

// Excepción para contraseña incorrecta
class ContraseniaIncorrectaException extends Exception {
    public function __construct($message = "Contraseña incorrecta", $code = 1, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

// Excepción para cuenta bloqueada
class CuentaBloqueadaException extends Exception {
    public function __construct($message = "Cuenta bloqueada por 3 intentos fallidos", $code = 2, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
?>