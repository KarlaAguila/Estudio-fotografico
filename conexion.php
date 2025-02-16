<?php
$servername = "localhost"; // Tu servidor de base de datos (generalmente 'localhost')
$username = "root";        // Tu usuario de base de datos
$password = "";            // Tu contraseña de base de datos (por defecto suele estar vacía)
$dbname = "estudio";       // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
