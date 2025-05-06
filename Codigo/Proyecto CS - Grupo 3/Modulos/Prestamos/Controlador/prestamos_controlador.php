<?php
require_once '../Modelo/prestamos_modelo.php';

// Manejo de búsqueda de lector por cédula (para AJAX)
if (isset($_GET['cedula'])) {
    $cedula = $_GET['cedula'];
    $lector = buscarLector($cedula);
    echo json_encode($lector);
    exit;
}

// Manejo de creación de nuevos préstamos
if (isset($_POST['guardar'])) {
    $lectorID = $_POST['lectorID'];
    $libroID = $_POST['libroID'];
    $fecha_prestamo = $_POST['fecha_prestamo'];
    $fecha_limite = $_POST['fecha_limite'];
    $estado_prestamo = 'activo';// Estado por defecto al crear un préstamo

    // Inserta el nuevo préstamo en la base de datos
    insertarPrestamo($lectorID, $libroID, $fecha_prestamo, $fecha_limite, $estado_prestamo);
    // Redirecciona a la vista de préstamos para evitar reenvío del formulario
    header('Location: ../Vista/prestamos.php');
    exit;
}

// Manejo de devolución de libros
if (isset($_POST['devolverID'])) {
    $prestamoID = $_POST['devolverID'];
     // Llama a la función que registra la devolución (probablemente cambia el estado)
    registrarDevolucion($prestamoID);
    //Redirecciona a la vista de prestamos
    header('Location: ../Vista/prestamos.php');
    exit;
}

// Manejo de búsqueda de préstamos
if (isset($_POST['buscar'])) {
    $campo_busqueda = $_POST['campo_busqueda'];// Campo por el que se filtra 
    $valor_busqueda = $_POST['valor_busqueda'];// Valor a buscar

    // Busca préstamos según los criterios proporcionados
    $prestamos = buscarPrestamos($campo_busqueda, $valor_busqueda);
    
    include '../Vista/prestamos_vista.php';
    exit;
}

// Por defecto, mostrar todos los préstamos
$prestamos = obtenerPrestamos();
include '../Vista/prestamos_vista.php';
?>