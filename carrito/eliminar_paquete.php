<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    die(json_encode(["success" => false, "message" => "No has iniciado sesión."]));
}

$conexion = new mysqli("localhost", "root", "", "estudio");
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión."]));
}

$id_paquete = $_POST['id_paquete'];
$usuario_id = $_SESSION['id_usuario'];

// Eliminar el paquete del carrito
$sql = "DELETE FROM DetallesCarrito WHERE id_paquete = $id_paquete";
$conexion->query($sql);

// Recalcular el total del carrito
$conexion->query("UPDATE CarritoCompras SET total_c = (SELECT SUM(precio_unitario * cantidad) FROM DetallesCarrito WHERE id_carrito = id_carrito) WHERE id_usuario = $usuario_id");

echo json_encode(["success" => true]);
?>
