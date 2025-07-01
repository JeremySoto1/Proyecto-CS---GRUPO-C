<?php
// Excepciones base para todo el sistema
class SistemaException extends Exception {
    public function __construct($message = "Error en el sistema", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

// Excepciones de base de datos
class DatabaseException extends SistemaException {
    public function __construct($message = "Error de base de datos", $code = 100, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class RegistroNoEncontradoException extends DatabaseException {
    public function __construct($message = "Registro no encontrado", $code = 101, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class RegistroDuplicadoException extends DatabaseException {
    public function __construct($message = "Registro duplicado", $code = 102, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

// Excepciones de validación
class ValidacionException extends SistemaException {
    public function __construct($message = "Error de validación", $code = 200, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class CamposRequeridosException extends ValidacionException {
    public function __construct($campos = [], $code = 201, Throwable $previous = null) {
        $message = "Campos requeridos faltantes: " . implode(', ', $campos);
        parent::__construct($message, $code, $previous);
    }
}
?>