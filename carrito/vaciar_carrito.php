<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "estudio");
$usuario_id = $_SESSION['id_usuario'];

$conexion->query("DELETE FROM DetallesCarrito WHERE id_carrito IN (SELECT id_carrito FROM CarritoCompras WHERE id_usuario = $usuario_id)");
$conexion->query("UPDATE CarritoCompras SET total_c = 0 WHERE id_usuario = $usuario_id");

echo json_encode(["success" => true]);
?>
