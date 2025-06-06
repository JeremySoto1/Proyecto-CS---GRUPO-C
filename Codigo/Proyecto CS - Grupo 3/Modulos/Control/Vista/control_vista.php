<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Multas y Sanciones</title>
    <link rel="stylesheet" href="../../../assets/CSS/multa.css">
</head>
<body>

<h1>Gestión de Multas y Sanciones</h1>

<!-- Sección 1: Búsqueda y datos del lector -->
<section class="seccion-busqueda">
    <h2>Buscar Lector</h2>
    <form id="form-buscar">
        <label for="cedula">Cédula:</label>
        <input type="text" id="cedula" name="cedula" required>
        <button type="submit">Buscar</button>
    </form>

    <div id="info-lector" class="info-lector">
        <label>Nombre: <span id="nombre"></span></label>
        <label>Apellido: <span id="apellido"></span></label>
        <label>Cédula: <span id="cedulaMostrar"></span></label>
        <label>Email: <span id="email"></span></label>
        <label>Teléfono: <span id="telefono"></span></label>
        <label>Dirección: <span id="direccion"></span></label>
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

<script src="../../../assets/js/multas.js"></script>
</body>
</html>
