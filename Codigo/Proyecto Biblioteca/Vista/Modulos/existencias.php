<?php
require_once __DIR__ . '/../../Controlador/Modulos/existencia_controlador.php';

// Obtener datos para la vista
$datos = obtenerDatosVista();

// Extraer variables para la vista
$existencias = $datos['existencias'];
$ubicaciones = $datos['ubicaciones'];
$estados = $datos['estados'];
$disponibilidades = $datos['disponibilidades'];

// Incluir la vista
require_once "existencias_vista.php";
?>