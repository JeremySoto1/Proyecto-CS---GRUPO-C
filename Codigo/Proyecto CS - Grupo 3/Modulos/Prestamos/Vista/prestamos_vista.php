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
    <select name="campo_busqueda" required>
        <option value="lectorCedula">Buscar por Cédula</option>
        <option value="estado_prestamo">Buscar por Estado</option>
    </select>
    <input type="text" name="valor_busqueda" placeholder="Buscar..." required>
    <button type="submit" name="buscar">Buscar</button>
    <button type="button" onclick="window.location.href='prestamos.php'">Mostrar Todos</button>
</form>

<!-- Tabla de Préstamos -->
<section class="Mostrar_Datos">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre Lector</th>
                <th>Cédula Lector</th>
                <th>Libros</th>
                <th>Fecha Préstamo</th>
                <th>Fecha Límite</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestamos as $prestamo): ?>
                <tr id="fila-<?=$prestamo['prestamoID']?>">
                    <td class="IDPrestamo"><?= $prestamo['prestamoID'] ?></td>
                    <td class="NombreLector"><?= $prestamo['lectorNombre'] ?></td>
                    <td class="CedulaLector"><?= $prestamo['lectorCedula'] ?></td>
                    <td class="LibrosPrestados"><?= $prestamo['libros'] ?></td>
                    <td class="FechaPrestamo"><?= $prestamo['fecha_prestamo'] ?></td>
                    <td class="FechaLimite"><?= $prestamo['fecha_devolucion'] ?></td>
                    <td class="EstadoPrestamo"><?= $prestamo['estadoprestamoID'] ?></td>
                    <td>
                        <?php if ($prestamo['estadoprestamoID'] == 'activo'): ?>
                            <form method="POST" action="../Controlador/prestamos_controlador.php" style="display:inline;">
                                <input type="hidden" name="devolverID" value="<?= $prestamo['prestamoID'] ?>">
                                <button type="submit" class="btn-devolver">Devolver</button>
                            </form>
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
<div id="prestamoModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="cerrarModal()">&times;</span>
        <form id="form_prestamo" action="../Controlador/prestamos_controlador.php" method="POST">
        <input type="hidden" id="prestamoID" name="prestamoID">    
        <input type="hidden" name="guardar" value="1">
            
            <h2>Información del Lector</h2>
            <label>Cédula:</label>
            <input type="text" id="cedula" required>
            <button type="button" onclick="buscarLector()">Buscar Lector</button>

            <input type="hidden" name="lectorID" id="lectorID">
            <p>Nombre: <span id="lectorNombre">-</span></p>
            <p>Email: <span id="lectorEmail">-</span></p>

            <h2>Libros</h2>
            <label>Buscar Libro:</label>
            <input type="text" id="busquedaLibro" placeholder="Titulo o Autor">
            <button type="button" onclick="buscarLibros()">Buscar</button>

            <div id="resultadosLibros" style="margin: 10px; max-height: 200px; overflow-y: auto;">
                <!-- Los resultados de la búsqueda aparecerán aquí -->
            </div>

            <h2>Fechas</h2>
            <label>Fecha Préstamo:</label>
            <input type="date" name="fecha_prestamo" required>

            <label>Fecha Límite:</label>
            <input type="date" name="fecha_limite" required>

            <button type="submit">Guardar Préstamo</button>
            <button type="button" onclick="cerrarModal()">Cancelar</button>
        </form>
    </div>
</div>

<script src="../../../assets/js/prestamos.js"></script>

</body>
</html>