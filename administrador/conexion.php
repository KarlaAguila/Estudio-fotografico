<?php
$servername = "localhost";
$username = "root"; // Reemplaza con tu usuario si es diferente
$password = ""; // Si tienes contraseña, agrégala aquí
$database = "Estudio"; // Reemplaza con tu base de datos

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}
?>
