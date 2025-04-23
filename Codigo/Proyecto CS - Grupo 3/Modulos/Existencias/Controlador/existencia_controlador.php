<?php
require_once "../Modelo/existencias_modelo.php";

$accion = $_POST['accion'] ?? '';

$libroID = $_POST['libroID'] ?? null;
$ubicacionID = $_POST['ubicacionID'] ?? null;
$estadoExistenciaID = $_POST['estadoExistenciaID'] ?? null;
$disponibilidadExistenciaID = $_POST['disponibilidadExistenciaID'] ?? null;

if ($accion === 'guardar') {
    $stmt = mysqli_prepare($enlace, "CALL insertar_existencia(?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "iiii", $libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID);
    mysqli_stmt_execute($stmt);
    mysqli_next_result($enlace);
    header("Location: ../Vista/existencias.php?exito=1");
    exit();
}

if ($accion === 'modificar') {
    $existenciaID = $_POST['existenciaID'];
    if (actualizarExistencia($existenciaID, $libroID, $ubicacionID, $estadoExistenciaID, $disponibilidadExistenciaID)) {
        header("Location: ../Vista/existencias.php?modificado=1");
    } else {
        echo "Error al modificar.";
    }
}
?>
