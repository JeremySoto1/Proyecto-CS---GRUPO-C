<?php
require_once 'excepciones_sistema.php';

/**
 * Excepción para cuando un libro ya existe en el sistema
 */
class LibroDuplicadoException extends RegistroDuplicadoException {
    public function __construct($message = "El libro ya existe en el sistema", $code = 600, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Excepción para cuando no se encuentra un libro solicitado
 */
class LibroNoEncontradoException extends RegistroNoEncontradoException {
    public function __construct($message = "Libro no encontrado en la base de datos", $code = 601, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Excepción para operaciones no permitidas con libros prestados
 */
class LibroPrestadoException extends SistemaException {
    public function __construct($message = "Operación no permitida: El libro está actualmente prestado", $code = 602, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Excepción para datos de libro inválidos
 */
class DatosLibroInvalidosException extends ValidacionException {
    public function __construct($message = "Los datos del libro son inválidos o incompletos", $code = 603, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Excepción para género de libro no válido
 */
class GeneroInvalidoException extends ValidacionException {
    public function __construct($message = "El género especificado no existe", $code = 604, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
?>