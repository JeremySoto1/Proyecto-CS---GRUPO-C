<?php
require_once '../Modelo/libros_modelo.php';

if (isset($_POST['guardar'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $pages_no = $_POST['pages_no'];
    $genderID = $_POST['gender'];
    insertarLibro($title, $author, $year, $pages_no, $genderID);
    header('Location: ../Vista/libros.php');
}

if (isset($_POST['modificar'])) {
    $libroID = $_POST['libroID'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $pages_no = $_POST['pages_no'];
    $genderID = $_POST['gender'];
    modificarLibro($libroID, $title, $author, $year, $pages_no, $genderID);
    header('Location: ../Vista/libros.php');
}

if (isset($_POST['eliminarID'])) {
    $libroID = $_POST['eliminarID'];
    eliminarLibro($libroID);
    header('Location: ../Vista/libros.php');
}

if (isset($_POST['estadoID']) && isset($_POST['nuevoEstado'])) {
  $libroID = $_POST['estadoID'];
  $nuevoEstado = $_POST['nuevoEstado']; 
  cambiarEstadoLibro($libroID, $nuevoEstado);
  header('Location: ../Vista/libros.php');
}


if (isset($_POST['buscar'])) {
  $campo_busqueda = $_POST['campo_busqueda'];
  $valor_busqueda = $_POST['valor_busqueda'];

  $libros = buscarLibros($campo_busqueda, $valor_busqueda);
  $librosDeshabilitados = obtenerLibrosDeshabilitados();
  $librosHabilitados = obtenerLibrosHabilitados();

  include '../Vista/libros.php';
  exit;
}

?>
