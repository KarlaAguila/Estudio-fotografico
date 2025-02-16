<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["success" => false, "message" => "No has iniciado sesión."]);
    exit;
}

$usuario_id = $_SESSION['id_usuario'];

$conexion = new mysqli("localhost", "root", "", "estudio");
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión."]));
}

$sql = "DELETE FROM CarritoCompras WHERE id_usuario = $usuario_id AND estado_c = 'pendiente'";
$conexion->query($sql);

echo json_encode(["success" => true]);

$conexion->close();
?>

