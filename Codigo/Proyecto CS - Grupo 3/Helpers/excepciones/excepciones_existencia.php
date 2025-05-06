<?php
require_once 'excepciones_sistema.php';

class ExistenciaException extends SistemaException {
    public function __construct($message = "Error en gestión de existencias", $code = 800, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class ExistenciaNoEncontradaException extends ExistenciaException {
    public function __construct($message = "Existencia no encontrada", $code = 801, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class LibroNoDisponibleException extends ExistenciaException {
    public function __construct($message = "El libro no está disponible", $code = 802, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class UbicacionInvalidaException extends ExistenciaException {
    public function __construct($message = "Ubicación no válida", $code = 803, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class EstadoInvalidoException extends ExistenciaException {
    public function __construct($message = "Estado de existencia no válido", $code = 804, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class DisponibilidadInvalidaException extends ExistenciaException {
    public function __construct($message = "Disponibilidad no válida", $code = 805, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class ExistenciaDuplicadaException extends ExistenciaException {
    public function __construct($message = "La existencia ya está registrada", $code = 806, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
?>