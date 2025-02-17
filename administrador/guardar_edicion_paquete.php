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

// Verificar que los datos fueron enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_paquete = $_POST['id_paquete'];
    $nombre_paquete = $_POST['nombre_paquete'];
    $precio = $_POST['precio'];
    $descripcion = $_POST['descripcion'];
    $disponibilidad = $_POST['disponibilidad'];

    // Manejar las imágenes
    if (!empty($_FILES['imagenes']['name'][0])) {
        $imagenes = $_FILES['imagenes'];
        $imagenes_blob = [];

        foreach ($imagenes['tmp_name'] as $index => $tmp_name) {
            $image_data = file_get_contents($tmp_name);
            $imagenes_blob[] = $image_data;
        }
    }

    // Actualizar los datos en la base de datos
    $sql = "UPDATE Paquetes SET nombre_paquete=?, precio=?, descripcion=?, disponibilidad=? WHERE id_paquete=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssii", $nombre_paquete, $precio, $descripcion, $disponibilidad, $id_paquete);
    $stmt->execute();

    // Si hay imágenes, actualizar también
    if (!empty($imagenes_blob)) {
        foreach ($imagenes_blob as $index => $image_data) {
            $sql = "UPDATE Paquetes SET imagenes=? WHERE id_paquete=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("bi", $image_data, $id_paquete);
            $stmt->execute();
        }
    }

    // Redirigir al listado de paquetes después de la actualización
    header('Location: gestion_paquetes.php');
    exit();
}

// Verificar si la conexión está abierta antes de cerrarla
if ($conn) {
    $conn->close();
}
