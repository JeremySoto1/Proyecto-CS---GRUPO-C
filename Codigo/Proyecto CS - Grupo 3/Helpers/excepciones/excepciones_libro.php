<?php
require_once 'excepciones_sistema.php';

class LibroDuplicadoException extends RegistroDuplicadoException {
    public function __construct($message = "El libro ya existe", $code = 600, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class LibroNoEncontradoException extends RegistroNoEncontradoException {
    public function __construct($message = "Libro no encontrado", $code = 601, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class LibroPrestadoException extends SistemaException {
    public function __construct($message = "El libro está actualmente prestado", $code = 602, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
?>