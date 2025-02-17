<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "Estudio");

if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión."]));
}

if (!isset($_SESSION['id_usuario'])) {
    die(json_encode(["success" => false, "message" => "Usuario no autenticado."]));
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener la información del usuario
$sql = $conexion->prepare("SELECT correo_electronico, nombre_usuario, numero_telefono FROM Usuarios WHERE id_usuario = ?");
$sql->bind_param("i", $id_usuario);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    echo json_encode(["success" => true, "usuario" => $usuario]);
} else {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado."]);
}

$sql->close();
$conexion->close();
?>
