<?php
require_once 'excepciones_sistema.php';

class LectorNoEncontradoException extends RegistroNoEncontradoException {
    public function __construct($message = "Lector no encontrado", $code = 602, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class LectorBloqueadoException extends SistemaException {
    public function __construct($message = "Lector bloqueado", $code = 603, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class CubiculoNoDisponibleException extends SistemaException {
    public function __construct($message = "Cubículo no disponible", $code = 604, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class CubiculoNoEncontradoException extends RegistroNoEncontradoException {
    public function __construct($message = "Cubículo no encontrado", $code = 605, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}