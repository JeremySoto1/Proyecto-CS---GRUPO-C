<?php
require_once 'excepciones_sistema.php';

class PrestamoNoEncontradoException extends RegistroNoEncontradoException {
    public function __construct($message = "Préstamo no encontrado", $code = 601, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class LectorNoEncontradoException extends RegistroNoEncontradoException {
    public function __construct($message = "Lector no encontrado", $code = 602, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class LectorBloqueadoException extends SistemaException {
    public function __construct($message = "Lector no disponible", $code = 603, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class ExistenciaNoDisponibleException extends SistemaException {
    public function __construct($message = "Existencia no disponible", $code = 604, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}