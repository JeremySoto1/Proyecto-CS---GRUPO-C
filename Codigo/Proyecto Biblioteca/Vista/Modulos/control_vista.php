<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Multas y Sanciones</title>
    <link rel="stylesheet" href="../../assets/CSS/multa.css">
    <link rel="stylesheet" href="../../assets/CSS/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <style>
        .alert {
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

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
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
    <script src="../../assets/js/sidebar.js"></script> 
</head>
<body>
<?php include '../Helpers/templates/sidebar.php'; ?>
  <div class="main-content">
    <div class="header">
        <h1>Gestión de Multas y Sanciones</h1>
        <div class="user-info">
                <span><?= htmlspecialchars($_SESSION['nombre'] . ' ' . $_SESSION['apellido']) ?></span>
                <a href="../../index.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </div>
        </div>
<div class="content">
    <div id="alert-container"></div>
<!-- Sección 1: Búsqueda y datos del lector -->
<section class="seccion-busqueda">
    <h2>Buscar Lector</h2>
    <form id="form-buscar">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required>
        <button type="submit">Buscar</button>
    </form>

    <div id="info-lector" class="info-lector">
        <div class="columnas-lector">
            <div class="columna-izq">
                <label>Nombre: <span id="nombre"></span></label>
                <label>Apellido: <span id="apellido"></span></label>
                <label>Cédula: <span id="cedulaMostrar"></span></label>
            </div>
            <div class="columna-der">
                <label>Email: <span id="email"></span></label>
                <label>Teléfono: <span id="telefono"></span></label>
                <label>Dirección: <span id="direccion"></span></label>
        </div>
    </div>
    </div>
</section>

<!-- Sección 2: Tabla de multas -->
<section class="seccion-multas">
    <h2>Detalle de Multas</h2>
    <table id="tablaMultas">
        <thead>
            <tr>
                <th>MultaID</th>
                <th>Monto</th>
                <th>Motivo</th>
                <th>Fecha Emisión</th>
                <th>Estado Multa</th>
                <th>LectorID</th>
                <th>PréstamoID</th>
            </tr>
        </thead>
        <tbody>
            <!-- Se rellenará dinámicamente -->
        </tbody>
    </table>

    <button id="cancelarDeuda">Cancelar Deuda</button>

    <!-- Estado del lector -->
    <div class="estado-lector">
        <div id="estadoBloqueo">Estado de Bloqueo: <strong>No definido</strong></div>
        <div id="estadoMultas">Estado de Multas: <strong>No definido</strong></div>
    </div>
</section>
</div>
  </div>
  

<script src="../../assets/js/multas.js"></script>
<script>
    // Función para mostrar alertas
    function mostrarAlerta(mensaje, tipo = 'danger') {
        const alertContainer = document.getElementById('alert-container');
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo}`;
        alertDiv.textContent = mensaje;
        alertContainer.appendChild(alertDiv);
        
        // Eliminar la alerta después de 3 segundos
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
</script>
</body>
</html>
