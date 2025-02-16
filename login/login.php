<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "Estudio");

if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión."]));
}

$email = $_POST['email'];
$password = $_POST['password'];

// Consulta segura usando prepared statements
$sql = $conexion->prepare("SELECT nombre_usuario FROM Usuarios WHERE correo_electronico = ? AND contraseña = ?");
$sql->bind_param("ss", $email, $password);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $_SESSION['usuario'] = $usuario['nombre_usuario'];
    echo json_encode(["success" => true]); // Envío respuesta JSON exitosa
} else {
    echo json_encode(["success" => false, "message" => "Correo o contraseña incorrectos."]);
}

$sql->close();
$conexion->close();
?>

