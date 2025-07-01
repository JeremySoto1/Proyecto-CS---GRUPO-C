<?php
session_start();
require_once __DIR__ . '/../../Modelo/Modulos/prestamos_modelo.php';
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
    <link rel="stylesheet" href="../../assets//CSS//prestamos.css">
    <link rel="stylesheet" href="../../assets/CSS/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../../assets/js/sidebar.js"></script> 
      <style>
        .alerta {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.5s, fadeOut 0.5s 2.5s forwards;
        }

        .alerta-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alerta-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alerta-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
    </style>
</head>
<body>
<?php include '../Helpers/templates/sidebar.php'; ?>
<?php if (isset($_SESSION['alerta'])): ?>
        <div class="alerta alerta-<?= $_SESSION['alerta']['tipo'] ?>">
            <?= htmlspecialchars($_SESSION['alerta']['mensaje']) ?>
        </div>
        <?php unset($_SESSION['alerta']); ?>
    <?php endif; ?>

    <div id="alerta" style="display:none;" class="alert" role="alert"></div>


<div class="main-content">
    <div class="header">
        <h1>Gestión de Préstamos</h1>
        <div class="user-info">
            <span><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></span>
                <a href="../../index.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
        </div>
    </div>
    <div class="content">
<!-- Buscador -->
<form method="POST" action="../../Controlador/Modulos/prestamos_controlador.php">
    <label for="tipo_busqueda">Buscar por:</label>
    <select id="tipo_busqueda" name="tipo_busqueda" required>
        <option value="cedula">Cédula</option>
        <option value="nombre_apellido">Nombre y Apellido</option>
    </select>
    
    <label for="termino_busqueda">Término de búsqueda:</label>
    <input type="text" id="termino_busqueda" name="termino_busqueda" placeholder="Buscar..." required>
    
    <button type="submit" name="accion" value="buscar">Buscar</button>
    <button type="button" onclick="window.location.href='prestamos_vista.php'">Mostrar Todos</button>
    <!-- Botón agregar -->
<button id="mostrarFormulario">Agregar Préstamo</button>
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

<!-- Modal Formulario Préstamo -->
<?php include 'modal_prestamo.php'; ?>
<?php include 'modal_devolucion.php'; ?>
    </div>
</div>

<script src="../../assets/js/prestamos.js"></script>
<script>
        // Cerrar automáticamente los mensajes de alerta después de 3 segundos
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alerta');
            alerts.forEach(alert => {
                alert.style.display = 'none';
            });
        }, 3000);
    </script>
</body>
</html>