<?php
require_once 'excepciones_sistema.php';

class LectorDuplicadoException extends RegistroDuplicadoException {
    public function __construct($message = "El lector ya existe", $code = 500, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class LectorNoEncontradoException extends RegistroNoEncontradoException {
    public function __construct($message = "Lector no encontrado", $code = 501, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class LectorConPrestamosException extends SistemaException {
    public function __construct($message = "El lector tiene préstamos activos", $code = 502, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
?>