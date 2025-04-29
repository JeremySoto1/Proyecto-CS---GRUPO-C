<?php
require_once "../Controlador/existencia_controlador.php";

// Obtener datos para la vista
$datos = obtenerDatosVista();

// Extraer variables para la vista
$existencias = $datos['existencias'];
$ubicaciones = $datos['ubicaciones'];
$estados = $datos['estados'];
$disponibilidades = $datos['disponibilidades'];

// Incluir la vista
require_once "../Vista/existencias_vista.php";
?>