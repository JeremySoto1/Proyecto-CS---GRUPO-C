<?php
session_start();
require_once '../../Modelo/Modulos/prestamos_modelo.php';
require_once __DIR__ . '/../Session/validar_sesion.php';
require_once __DIR__ . '/../Helpers/Servicios/PrestamoValidatorService.php';

// Crear instancia del validador
$validator = new PrestamoValidatorService($enlace);

// Verificar acción de búsqueda
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'buscar') {
    $tipo_busqueda = $_POST['tipo_busqueda'] ?? '';
    $termino_busqueda = trim($_POST['termino_busqueda'] ?? '');
    
    try {
        // Validación básica
        if (empty($termino_busqueda)) {
            throw new Exception("Debe ingresar un término de búsqueda");
        }

        $prestamos = buscarPrestamos($tipo_busqueda, $termino_busqueda);
        
        // Validar si se encontraron préstamos
        $validator->validarPrestamosLector($prestamos);
        
        // Pasar resultados a la vista
        $_SESSION['resultados_busqueda'] = $prestamos;
        $_SESSION['ultima_busqueda'] = [
            'tipo' => $tipo_busqueda,
            'termino' => $termino_busqueda
        ];
        
    } catch (PrestamoNoEncontradoException $e) {
        $_SESSION['alerta'] = [
            'tipo' => 'info',
            'mensaje' => $e->getMessage()
        ];
    } catch (Exception $e) {
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => $e->getMessage()
        ];
    }
    
    header('Location: ../../Vista/Modulos/prestamos_vista.php');
    exit;
}

// Si no hay acciones específicas, redirigir a la vista
header('Location: ../../Vista/Modulos/prestamos_vista.php');
exit;