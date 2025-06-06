<?php
session_start();
require_once '../Modelo/prestamos_modelo.php';


// Verificar acción de búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'buscar') {
    $tipo_busqueda = $_POST['tipo_busqueda'] ?? '';
    $termino_busqueda = trim($_POST['termino_busqueda'] ?? '');
    
    // Validación
    if (empty($termino_busqueda)) {
        $_SESSION['error'] = "Debe ingresar un término de búsqueda";
        header('Location: ../Vista/prestamos_vista.php');
        exit;
    }

    try {
        $prestamos = buscarPrestamos($tipo_busqueda, $termino_busqueda);
        
        if (empty($prestamos)) {
            $_SESSION['mensaje'] = "No se encontraron préstamos con los criterios de búsqueda";
        }
        
        // Pasar resultados a la vista
        $_SESSION['resultados_busqueda'] = $prestamos;
        $_SESSION['ultima_busqueda'] = [
            'tipo' => $tipo_busqueda,
            'termino' => $termino_busqueda
        ];
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Error al realizar la búsqueda: " . $e->getMessage();
    }
    
    header('Location: ../Vista/prestamos_vista.php');
    exit;
}



// Si no hay acciones específicas, redirigir a la vista
header('Location: ../Vista/prestamos_vista.php');
exit;