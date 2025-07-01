<?php
require_once 'excepciones_sistema.php';

class BibliotecarioDuplicadoException extends RegistroDuplicadoException {
    public function __construct($message = "El bibliotecario ya existe", $code = 400, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class BibliotecarioNoEncontradoException extends RegistroNoEncontradoException {
    public function __construct($message = "Bibliotecario no encontrado", $code = 401, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class BibliotecarioBloqueadoException extends SistemaException {
    public function __construct($message = "Bibliotecario no disponible", $code = 402, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}