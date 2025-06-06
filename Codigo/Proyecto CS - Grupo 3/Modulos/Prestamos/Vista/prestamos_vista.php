<?php
session_start();
require_once '../Modelo/prestamos_modelo.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Obtener préstamos (normales o resultados de búsqueda)
$prestamos = $_SESSION['resultados_busqueda'] ?? obtenerPrestamos();

// Limpiar resultados de búsqueda después de mostrarlos
unset($_SESSION['resultados_busqueda']);

// Obtener libros solo si es necesario
$libros = obtenerLibros();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Préstamos</title>
    <link rel="stylesheet" href="../../../assets/CSS/prestamos.css">
</head>
<body>

<h1>Gestión de Préstamos</h1>

<!-- Buscador -->
<form method="POST" action="../Controlador/prestamos_controlador.php">
    <label for="tipo_busqueda">Buscar por:</label>
    <select id="tipo_busqueda" name="tipo_busqueda" required>
        <option value="cedula">Cédula</option>
        <option value="nombre_apellido">Nombre y Apellido</option>
    </select>
    
    <label for="termino_busqueda">Término de búsqueda:</label>
    <input type="text" id="termino_busqueda" name="termino_busqueda" placeholder="Buscar..." required>
    
    <button type="submit" name="accion" value="buscar">Buscar</button>
    <button type="button" onclick="window.location.href='prestamos_vista.php'">Mostrar Todos</button>
</form>

<!-- Tabla de Préstamos -->
<section class="Mostrar_Datos">
    <table>
        <thead>
            <tr>
                <th>Prestamo ID</th>
                <th>Fecha Emision</th>
                <th>Fecha Finalización</th>
                <th>Fecha Devolucion</th>
                <th>Estado Prestamo</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Cedula</th>
                <th>ExistenciaID</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestamos as $prestamo): ?>
                <?php 
    // Depuración: Ver los datos de cada préstamo
    error_log("Prestamo ID: ".$prestamo['prestamoID'].", Existencia ID: ".($prestamo['existenciaID'] ?? 'NO DEFINIDO'));
    ?>
                <tr id="fila-<?=$prestamo['prestamoID']?>">
                    <td class="IDPrestamo"><?= $prestamo['prestamoID'] ?></td>
                    <td class="FechaPrestamo"><?= $prestamo['fecha_prestamo'] ?></td>
                    <td class="FechaLimite"><?= $prestamo['fecha_finalizacion'] ?></td>
                    <td class="FechaDevolucion"><?= $prestamo['fecha_devolucion'] ?></td>
                    <td class="EstadoPrestamo"><?= $prestamo['estado'] ?? $prestamo['estadoprestamoID'] ?></td>
                    <td class="NombreLector"><?= $prestamo['nombre'] ?></td>
                    <td class="ApellidoLector"><?= $prestamo['apellido'] ?></td>
                    <td class="CedulaLector"><?= $prestamo['cedula'] ?></td>
                    <td class="Existencia"><?= $prestamo['existenciaID'] ?></td>
                    <td>
                        <?php if ($prestamo['estado'] == 'Activo' && !empty($prestamo['existenciaID'])): ?>
                        <button type="button" 
                        class="btn-devolver" 
                        data-prestamo="<?= $prestamo['prestamoID'] ?>" 
                        data-existencia="<?= $prestamo['existenciaID'] ?>">
                            Devolver
                        </button>
                      <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<!-- Botón agregar -->
<button id="mostrarFormulario">Agregar Préstamo</button>

<!-- Modal Formulario Préstamo -->
<?php include 'modal_prestamo.php'; ?>

<!-- Al final del body, después del modal de préstamo -->
<?php include 'modal_devolucion.php'; ?>


<script src="../../../assets/js/prestamos.js"></script>

</body>
</html>