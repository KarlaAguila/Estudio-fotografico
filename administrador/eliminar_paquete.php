<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estudio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener el ID del paquete a eliminar
$id_paquete = $_POST['id_paquete'];

// Eliminar el paquete
$sql = "DELETE FROM Paquetes WHERE id_paquete = '$id_paquete'";

if ($conn->query($sql) === TRUE) {
    echo "Paquete eliminado correctamente.";
} else {
    echo "Error al eliminar paquete: " . $conn->error;
}

$conn->close();
?>
