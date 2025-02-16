<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["success" => false, "message" => "No has iniciado sesión."]);
    exit;
}

$usuario_id = $_SESSION['id_usuario'];

$conexion = new mysqli("localhost", "root", "", "estudio");
if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conexion->connect_error]));
}

$id_paquete = $_POST['id_paquete'];
$precio = $_POST['precio'];

// Verificar si el usuario ya tiene un carrito activo
$sql = "SELECT id_carrito FROM CarritoCompras WHERE id_usuario = $usuario_id AND estado_c = 'pendiente'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {
    $carrito = $resultado->fetch_assoc();
    $id_carrito = $carrito['id_carrito'];

    // Verificar si el paquete ya está en el carrito
    $sql = "SELECT cantidad FROM DetallesCarrito WHERE id_carrito = $id_carrito AND id_paquete = $id_paquete";
    $resultado = $conexion->query($sql);

    if ($resultado->num_rows > 0) {
        // Si el paquete ya está en el carrito, aumentar la cantidad
        $conexion->query("UPDATE DetallesCarrito SET cantidad = cantidad + 1 WHERE id_carrito = $id_carrito AND id_paquete = $id_paquete");
    } else {
        // Si el paquete no está en el carrito, agregarlo
        $conexion->query("INSERT INTO DetallesCarrito (id_carrito, id_paquete, cantidad, precio_unitario) VALUES ($id_carrito, $id_paquete, 1, $precio)");
    }

    // Actualizar el total del carrito
    $conexion->query("UPDATE CarritoCompras SET total_c = total_c + $precio WHERE id_carrito = $id_carrito");

} else {
    // Si el usuario no tiene un carrito activo, crear uno
    $conexion->query("INSERT INTO CarritoCompras (id_usuario, total_c, estado_c) VALUES ($usuario_id, $precio, 'pendiente')");
    $id_carrito = $conexion->insert_id;

    $conexion->query("INSERT INTO DetallesCarrito (id_carrito, id_paquete, cantidad, precio_unitario) VALUES ($id_carrito, $id_paquete, 1, $precio)");
}

echo json_encode(["success" => true]);
$conexion->close();
?>


