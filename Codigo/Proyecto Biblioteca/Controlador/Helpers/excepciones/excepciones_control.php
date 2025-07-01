<?php
require_once 'excepciones_sistema.php';

class LectorNoEncontradoException extends RegistroNoEncontradoException {
    public function __construct($message = "Lector no encontrado", $code = 602, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class MultasNoEncontradasException extends RegistroNoEncontradoException {
    public function __construct($message = "No se encontraron multas", $code = 603, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}