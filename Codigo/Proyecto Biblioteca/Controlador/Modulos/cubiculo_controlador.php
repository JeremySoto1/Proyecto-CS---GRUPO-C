<?php
session_start();
include_once "../../Modelo/Modulos/cubiculo_modelo.php";
require_once __DIR__.'/../Helpers/Servicios/CubiculoValidatorService.php';

// Crear instancia del validador
$validator = new CubiculoValidatorService($enlace);

// Leer datos JSON si vienen por fetch con Content-Type: application/json
$input = json_decode(file_get_contents("php://input"), true);

// Fusionar $_POST/$_GET con JSON recibido
$accion = $input['accion'] ?? $_POST['accion'] ?? $_GET['accion'] ?? '';

try {
    switch ($accion) {
        case 'listar':
            $data = CubiculoModelo::listarCubiculos();
            header('Content-Type: application/json');
            echo json_encode($data);
            break;

        case 'registrarCubiculo':
            $nombre = $input['nombre'] ?? $_POST['nombre'];
            $equipamento = $input['equipamento'] ?? $_POST['equipamento'];
            $capacidad = $input['capacidad'] ?? $_POST['capacidad'];
            
            if (empty($nombre) || empty($equipamento) || empty($capacidad)) {
                throw new Exception("Todos los campos son requeridos");
            }
            
            header('Content-Type: application/json');
            echo json_encode(CubiculoModelo::registrarCubiculo($nombre, $equipamento, $capacidad));
            break;

        case 'buscarLector':
            $cedula = $_GET['cedula'] ?? '';
            if (empty($cedula)) {
                throw new Exception("Debe ingresar una cédula");
            }
            
            $lector = CubiculoModelo::buscarLectorPorCedula($cedula);
            $validator->validarLectorExistente($lector);
            $validator->validarEstadoLector($lector);
            
            header('Content-Type: application/json');
            echo json_encode($lector);
            break;

        case 'alquilarCubiculo':
            $lectorID = $input['lectorID'] ?? $_POST['lectorID'];
            $cubiculoID = $input['cubiculoID'] ?? $_POST['cubiculoID'];
            
            if (empty($lectorID) || empty($cubiculoID)) {
                throw new Exception("Datos incompletos para el alquiler");
            }
            
            // Validar disponibilidad del cubículo
            $validator->validarDisponibilidadCubiculo($cubiculoID);
            
            header('Content-Type: application/json');
            echo json_encode(["success" => CubiculoModelo::alquilarCubiculo($lectorID, $cubiculoID)]);
            break;

        case 'devolverCubiculo':
            $cubiculoID = $input['cubiculoID'] ?? $_POST['cubiculoID'];
            
            if (empty($cubiculoID)) {
                throw new Exception("No se especificó el cubículo a devolver");
            }
            
            header('Content-Type: application/json');
            echo json_encode(["success" => CubiculoModelo::devolverCubiculo($cubiculoID)]);
            break;

        default:
            header('Content-Type: application/json');
            echo json_encode(["error" => "Acción no válida"]);
            break;
    }
} catch (LectorNoEncontradoException $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => $e->getMessage()]);
} catch (LectorBloqueadoException $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => $e->getMessage()]);
} catch (CubiculoNoDisponibleException $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => $e->getMessage()]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode(["error" => $e->getMessage()]);
}