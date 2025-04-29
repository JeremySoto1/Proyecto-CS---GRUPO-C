<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Existencias</title>
    <link rel="stylesheet" href="../../../assets/CSS/existencias.css">
    <link rel="stylesheet" href="../../../assets/css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../../../assets/js/sidebar.js"></script> <!-- para el comportamiento de submenú -->

</head>
<body>

<?php include '../../../Helpers/templates/sidebar.php'; ?>
    
    <div class="main-content">
        <div class="content">
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
                <?php foreach ($existencias as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['existenciaID']) ?></td>
                        <td><?= htmlspecialchars($e['libroID']) ?></td>
                        <td><?= htmlspecialchars($e['title']) ?></td>
                        <td><?= htmlspecialchars($e['ubicacion']) ?></td>
                        <td><?= htmlspecialchars($e['estado']) ?></td>
                        <td><?= htmlspecialchars($e['disponibilidad']) ?></td>
                        <td>
                            <form method='GET' action='modificar_existencia.php'>
                                <input type='hidden' name='id' value='<?= htmlspecialchars($e['existenciaID']) ?>'>
                                <button type='button' class='btn-modificar'
        onclick='mostrarFormularioModificar(<?= json_encode($e) ?>)'>Modificar</button>

                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Botón flotante para agregar -->
        <button onclick="mostrarFormulario()" class="btn-agregar">+ Agregar Existencia</button>

        <!-- Formulario flotante -->
        <div class="form-flotante" id="formFlotante">
        <form method="POST" action="../Controlador/existencia_controlador.php" class="formulario-agregar">
            <h2 id="formTitulo">Nueva Existencia</h2>
                
            <!-- Campos ocultos necesarios -->
            <input type="hidden" name="accion" value="guardar">
            <input type="hidden" id="existenciaID" name="existenciaID">
                
            <label>ID del libro:</label>
            <input type="number" id="libroID" name="libroID" required>

            <label>Ubicación:</label>
            <select id="ubicacionID" name="ubicacionID" required>
                <?php foreach ($ubicaciones as $u): ?>
                    <?php $texto = "{$u['section']}-{$u['aisle']}-{$u['shelving']}-{$u['level']}"; ?>
                    <option value="<?= htmlspecialchars($u['ubicacionID']) ?>"><?= htmlspecialchars($texto) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Estado:</label>
            <select id="estadoExistenciaID" name="estadoExistenciaID" required>
                <?php foreach ($estados as $e): ?>
                    <option value="<?= htmlspecialchars($e['statusExistenceID']) ?>"><?= htmlspecialchars($e['status']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Disponibilidad:</label>
            <select id="disponibilidadExistenciaID" name="disponibilidadExistenciaID" required>
                <?php foreach ($disponibilidades as $d): ?>
                    <option value="<?= htmlspecialchars($d['disponibilidadID']) ?>"><?= htmlspecialchars($d['status']) ?></option>
                <?php endforeach; ?>
            </select>

            <div class="acciones">
                <button type="submit" name="guardar_existencia">Guardar</button>
                <button type="button" onclick="cerrarFormulario()">Cancelar</button>
            </div>
        </form>
    </div>

        <!-- Formulario de Modificar Existencia (oculto al inicio) -->
    <div id="formModificar" class="form-modificar" style="display: none;">
        <h2>Modificar Existencia</h2>
         <form method="POST" action="../Controlador/existencia_controlador.php">
            <input type="hidden" name="accion" value="modificar">
            <input type="hidden" id="existenciaID_mod" name="existenciaID">

            <label>ID del libro:</label>
            <input type="number" id="libroID_mod" name="libroID" required>

            <label>Ubicación:</label>
            <select id="ubicacionID_mod" name="ubicacionID" required>
                <?php foreach ($ubicaciones as $u): ?>
                    <?php $texto = "{$u['section']}-{$u['aisle']}-{$u['shelving']}-{$u['level']}"; ?>
                    <option value="<?= $u['ubicacionID'] ?>"><?= $texto ?></option>
                <?php endforeach; ?>
            </select>

            <label>Estado:</label>
            <select id="estadoExistenciaID_mod" name="estadoExistenciaID" required>
                <?php foreach ($estados as $e): ?>
                    <option value="<?= $e['statusExistenceID'] ?>"><?= $e['status'] ?></option>
                <?php endforeach; ?>
            </select>

            <label>Disponibilidad:</label>
            <select id="disponibilidadExistenciaID_mod" name="disponibilidadExistenciaID" required>
                <?php foreach ($disponibilidades as $d): ?>
                    <option value="<?= $d['disponibilidadID'] ?>"><?= $d['status'] ?></option>
                <?php endforeach; ?>
            </select>

            <div class="acciones">
                <button type="submit">Guardar Cambios</button>
                <button type="button" onclick="cerrarFormularioModificar()">Cancelar</button>
            </div>
            </form>
            </div>
            </div>
    </div>


    <script src="../../../assets/js/existencias.js"></script>
    <script>
function mostrarFormularioModificar(datos) {
    // Mostrar el formulario
    document.getElementById("formModificar").style.display = "block";

    // Llenar los campos con los datos
    document.getElementById("existenciaID_mod").value = datos.existenciaID;
    document.getElementById("libroID_mod").value = datos.libroID;
    document.getElementById("ubicacionID_mod").value = datos.ubicacionID;
    document.getElementById("estadoExistenciaID_mod").value = datos.estadoExistenciaID;
    document.getElementById("disponibilidadExistenciaID_mod").value = datos.disponibilidadExistenciaID;
}

function cerrarFormularioModificar() {
    document.getElementById("formModificar").style.display = "none";
}
</script>

</body>
</html>