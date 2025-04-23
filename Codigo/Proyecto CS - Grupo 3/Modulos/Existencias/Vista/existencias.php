<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Existencias</title>
    <link rel="stylesheet" href="../../../assets/CSS/existencias.css">
</head>
<body>
    <h1>Gestión de Existencias</h1>

    <!-- Formulario de búsqueda -->
    <form method="GET" class="form-busqueda">
        <select name="campo_busqueda">
            <option value="titulo">Título del libro</option>
            <option value="existenciaID">ID Existencia</option>
        </select>
        <input type="text" name="valor_busqueda" placeholder="Buscar..." required>
        <button type="submit">Buscar</button>
    </form>

    <!-- Tabla de existencias -->
    <table class="tabla">
        <thead>
            <tr>
                <th>ID</th>
                <th>Libro ID</th>
                <th>Título</th>
                <th>Ubicación</th>
                <th>Estado</th>
                <th>Disponibilidad</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <!-- Aquí va el PHP para mostrar los resultados -->
            <?php
            // Requiere las funciones del modelo
            require_once "../Modelo/existencias_modelo.php";

            $campo = $_GET['campo_busqueda'] ?? '';
            $valor = $_GET['valor_busqueda'] ?? '';
            $existencias = $campo && $valor ? buscarExistencias($campo, $valor) : obtenerTodasExistencias();

            foreach ($existencias as $e) {
                echo "<tr>
                        <td>{$e['existenciaID']}</td>
                        <td>{$e['libroID']}</td>
                        <td>{$e['title']}</td>
                        <td>{$e['ubicacion']}</td>
                        <td>{$e['estado']}</td>
                        <td>{$e['disponibilidad']}</td>
                        <td>
                            <form method='GET' action='modificar_existencia.php'>
                                <input type='hidden' name='id' value='{$e['existenciaID']}'>
                                <button type='submit' class='btn-modificar'>Modificar</button>
                            </form>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Botón flotante para agregar -->
    <button onclick="mostrarFormulario()" class="btn-agregar">+ Agregar Existencia</button>

    <!-- Formulario flotante -->
    <div class="form-flotante" id="formFlotante">
        <form method="POST" action="../Controlador/existencia_controlador.php" class="formulario-agregar">
            <h2>Nueva Existencia</h2>
            <label>ID del libro:</label>
            <input type="number" name="libroID" required>

            <label>Ubicación:</label>
            <select name="ubicacionID" required>
                <?php
                foreach (obtenerUbicaciones() as $u) {
                    $texto = "{$u['section']}-{$u['aisle']}-{$u['shelving']}-{$u['level']}";
                    echo "<option value='{$u['ubicacionID']}'>{$texto}</option>";
                }
                ?>
            </select>

            <label>Estado:</label>
            <select name="estadoExistenciaID" required>
                <?php
                foreach (obtenerEstadosExistencia() as $e) {
                    echo "<option value='{$e['statusExistenceID']}'>{$e['status']}</option>";
                }
                ?>
            </select>

            <label>Disponibilidad:</label>
            <select name="disponibilidadExistenciaID" required>
                <?php
                foreach (obtenerDisponibilidades() as $d) {
                    echo "<option value='{$d['disponibilidadID']}'>{$d['status']}</option>";
                }
                ?>
            </select>

            <div class="acciones">
                <button type="submit" name="guardar_existencia">Guardar</button>
                <button type="button" onclick="cerrarFormulario()">Cancelar</button>
            </div>
        </form>
    </div>

    <script src="../../../assets/js/existencias.js"></script>
</body>
</html>
