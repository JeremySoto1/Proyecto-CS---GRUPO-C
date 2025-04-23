<?php
if (!isset($libros)) {
  require_once '../Modelo/libros_modelo.php';
  $libros = obtenerLibros();
  $librosDeshabilitados = obtenerLibrosDeshabilitados();
  $librosHabilitados = obtenerLibrosHabilitados();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Gestión de Libros</title>
  <link rel="stylesheet" href="../../../assets/CSS/libro.css" />
</head>
<body>
<h1>Gestión de Libros</h1>

<!-- Formulario de búsqueda -->
<form method="POST" action="../Controlador/libros_controlador.php">
    <select name="campo_busqueda" required>
        <option value="titulo">Buscar por Título</option>
        <option value="autor">Buscar por Autor</option>
        <option value="id">Buscar por ID</option>
    </select>
    <input type="text" name="valor_busqueda" placeholder="Buscar..." required>
    <button type="submit" name="buscar">Buscar</button>
</form>

<!-- TABLA -->
<section class="Mostrar_Datos">
  <table>
    <thead>
      <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Año</th>
        <th>Páginas</th>
        <th>Género</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($libros as $libro): ?>
        <tr id="fila-<?= $libro['libroID'] ?>">
          <td class="titulo"><?= $libro['title'] ?></td>
          <td class="autor"><?= $libro['author'] ?></td>
          <td class="anio"><?= $libro['year'] ?></td>
          <td class="paginas"><?= $libro['pages_no'] ?></td>
          <td class="gender" data-id="<?= $libro['gender'] ?>"><?= $libro['gender'] ?></td>
          <td>
            <button onclick="editarLibro(<?= $libro['libroID'] ?>)">Modificar</button>
            <form method="POST" action="../Controlador/libros_controlador.php" style="display:inline;">
              <input type="hidden" name="eliminarID" value="<?= $libro['libroID'] ?>">
              <button type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>

<!-- BOTÓN -->
<button id="mostrarFormulario">Agregar Libro</button>

<!-- FORMULARIO -->
<section id="formularioLibro" style="display: none;">
  <form id="form_libro" action="../Controlador/libros_controlador.php" method="POST">
    <input type="hidden" name="libroID" id="libroID">
    <div><label>Título:</label><input type="text" name="title" id="title" required></div>
    <div><label>Autor:</label><input type="text" name="author" id="author" required></div>
    <div><label>Año:</label><input type="number" name="year" id="year" required></div>
    <div><label>Páginas:</label><input type="number" name="pages_no" id="pages_no" required></div>
    <div>
      <label>Género:</label>
      <select name="gender" id="gender" required>
        <option value="1">Ciencia Ficción</option>
        <option value="2">Romance</option>
        <option value="3">Historia</option>
        <option value="4">Drama</option>
        <option value="5">Terror</option>
        <option value="6">Científico</option>
      </select>
    </div>
    <button type="submit" name="guardar">Guardar Libro</button>
  </form>
</section>

<!-- Tabla DESHABILITADOS OCULTA -->
<!-- Botón para mostrar/ocultar libros deshabilitados -->
<button onclick="toggleDeshabilitados()">Ver Libros Deshabilitados</button>
  <div id="tablaDeshabilitados" class="oculto" style="display: none;">
    <h2>Libros Deshabilitados</h2>
    <table>
      <thead>
        <tr><th>Título</th><th>Autor</th><th>Año</th><th>Páginas</th><th>Género</th><th>Acciones</th></tr>
      </thead>
      <tbody>
        <?php foreach ($librosDeshabilitados as $libro): ?>
          <tr>
            <td><?= $libro['title'] ?></td>
            <td><?= $libro['author'] ?></td>
            <td><?= $libro['year'] ?></td>
            <td><?= $libro['pages_no'] ?></td>
            <td><?= $libro['gender'] ?></td>
            <td>
              <form method="POST" action="../Controlador/libros_controlador.php" style="display:inline;">
                <input type="hidden" name="estadoID" value="<?= $libro['libroID'] ?>">
                <input type="hidden" name="nuevoEstado" value="1">
                <button type="submit">Habilitar</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<script src="../../../assets/js/libros.js" defer></script>

</body>
</html>
