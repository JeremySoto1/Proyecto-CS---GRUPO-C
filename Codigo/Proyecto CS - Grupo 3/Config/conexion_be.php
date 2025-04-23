<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'gestionbiblioteca';

$enlace = mysqli_connect($server, $username, $password, $database);

if (!$enlace) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
