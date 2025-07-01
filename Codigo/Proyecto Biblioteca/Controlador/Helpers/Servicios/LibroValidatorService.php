<?php
require_once(__DIR__ . '/../excepciones/excepciones_libro.php');
require_once(__DIR__ . '/../../../Modelo/Config/conexion_be.php');


class LibroValidatorService {
    private $enlace;

    public function __construct($enlace) {
        $this->enlace = $enlace;
    }

    /**
     * Valida los datos básicos de un libro antes de insertar o actualizar
     */
    public function validarDatosLibro($title, $author, $year, $pages_no, $genderID) {
        if (empty($title) || empty($author) || empty($year) || empty($pages_no) || empty($genderID)) {
            throw new DatosLibroInvalidosException();
        }
        
        if (!is_numeric($year) || $year <= 0) {
            throw new DatosLibroInvalidosException("El año debe ser un número positivo");
        }
        
        if (!is_numeric($pages_no) || $pages_no <= 0) {
            throw new DatosLibroInvalidosException("El número de páginas debe ser positivo");
        }
        
        $this->validarGenero($genderID);
    }

    /**
     * Verifica si un libro ya existe en el sistema
     */
    public function validarLibroDuplicado($title, $author, $excludeId = null) {
        $query = "SELECT libroID FROM libro WHERE title = ? AND author = ?";
        if ($excludeId) {
            $query .= " AND libroID != ?";
        }
        
        $stmt = mysqli_prepare($this->enlace, $query);
        
        if ($excludeId) {
            mysqli_stmt_bind_param($stmt, "ssi", $title, $author, $excludeId);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $title, $author);
        }
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        
        if (mysqli_stmt_num_rows($stmt) > 0) {
            throw new LibroDuplicadoException();
        }
        
        mysqli_stmt_close($stmt);
    }

    /**
     * Valida que un género exista en la base de datos
     */
    private function validarGenero($genderID) {
        $stmt = mysqli_prepare($this->enlace, "SELECT generoID FROM genero WHERE generoID = ?");
        mysqli_stmt_bind_param($stmt, "i", $genderID);
        mysqli_stmt_execute($stmt);
        
        if (!mysqli_stmt_fetch($stmt)) {
            throw new GeneroInvalidoException();
        }
        
        mysqli_stmt_close($stmt);
    }

}
?>