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

// Recibir los datos del formulario
$nombre_paquete = $_POST['nombre_paquete'];
$precio = $_POST['precio'];
$descripcion = $_POST['descripcion'];
$imagen = addslashes(file_get_contents($_FILES['imagenes']['tmp_name']));

// Insertar en la base de datos
$sql = "INSERT INTO Paquetes (nombre_paquete, precio, imagenes, descripcion) VALUES ('$nombre_paquete', '$precio', '$imagen', '$descripcion')";

if ($conn->query($sql) === TRUE) {
    echo "Nuevo paquete agregado correctamente.";
    header("Location: gestion_paquetes.php"); // Redirigir después de agregar
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
