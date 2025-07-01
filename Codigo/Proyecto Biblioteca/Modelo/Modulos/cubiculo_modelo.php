<?php
require_once __DIR__ . '/../Config/conexion_be.php';//Llamada a la conexión

class CubiculoModelo {

    //Método para listar todos los cubiculos disponibles
    public static function listarCubiculos() {
        global $enlace;//Conexión a la BD
        $sql = "CALL listarCubiculos()";//Llamada al procedimiento almacenado
        $result = mysqli_query($enlace, $sql);
        if ($result) {
            return mysqli_fetch_all($result, MYSQLI_ASSOC);//Devuelve todos los resultados como array asociativo
        } else {
            throw new Exception("Error al listar cubículos: " . mysqli_error($enlace));
        }
    }

    //Método para registrar un nuevo cubículo
    public static function registrarCubiculo($nombre, $equipamiento, $capacidad) {
        global $enlace;//Conexión a la BD
        
        // Validar datos
        if (empty($nombre) || empty($equipamiento) || empty($capacidad)) {
            throw new Exception("Todos los campos son requeridos");
        }
        
        $stmt = mysqli_prepare($enlace, "CALL registrarCubiculo(?, ?, ?)");//Llamada al procedimiento almacenado
        mysqli_stmt_bind_param($stmt, "ssi", $nombre, $equipamiento, $capacidad);
        
        if (!mysqli_stmt_execute($stmt)) {// Ejecuta la consulta
            throw new Exception("Error al registrar cubículo: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
        return ["success" => true];// Retorna indicador de éxito
    }

    //Método para buscar un lector por su número de cédula
    public static function buscarLectorPorCedula($cedula) {
        global $enlace;//Conexión a la BD
        
        $stmt = mysqli_prepare($enlace, "SELECT * FROM lector WHERE cedula = ?");
        mysqli_stmt_bind_param($stmt, "s", $cedula);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $lector = mysqli_fetch_assoc($resultado);// Obtener el primer resultado
        mysqli_stmt_close($stmt);
        
        return $lector;// Retorna los datos del lector o null
    }

    //Método para alquilar un cubículo a un lector
    public static function alquilarCubiculo($lectorID, $cubiculoID) {
        global $enlace;//Conexión a la BD
        
        $stmt = mysqli_prepare($enlace, "CALL alquilarCubiculo(?, ?)");//Llamada al procedimiento almacenado
        mysqli_stmt_bind_param($stmt, "ii", $lectorID, $cubiculoID);
        
        if (!mysqli_stmt_execute($stmt)) {// Ejecutar la consulta

            throw new Exception("Error al alquilar cubículo: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
        return true;// Retorna éxito
    }

    //Método para devolver un cubículo alquilado
    public static function devolverCubiculo($cubiculoID) {
        global $enlace;//Conexión a la BD
        
        $stmt = mysqli_prepare($enlace, "CALL devolverCubiculo(?)");//Llamada al procedimiento almacenado
        mysqli_stmt_bind_param($stmt, "i", $cubiculoID);
        
        if (!mysqli_stmt_execute($stmt)) {// Ejecutar la consulta
            throw new Exception("Error al devolver cubículo: " . mysqli_stmt_error($stmt));
        }
        
        mysqli_stmt_close($stmt);
        return true;// Retorna éxito
    }
}