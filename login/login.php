<?php
session_start();
$conexion = new mysqli("localhost", "root", "", "Estudio");

if ($conexion->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión."]));
}

$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para obtener el id_usuario además del nombre_usuario
$sql = $conexion->prepare("SELECT id_usuario, nombre_usuario FROM Usuarios WHERE correo_electronico = ? AND contraseña = ?");
$sql->bind_param("ss", $email, $password);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows > 0) {
    $usuario = $resultado->fetch_assoc();
    $_SESSION['usuario'] = $usuario['nombre_usuario'];  // Guardar nombre_usuario
    $_SESSION['id_usuario'] = $usuario['id_usuario'];  // Guardar id_usuario
    echo json_encode(["success" => true]); // Enviar respuesta JSON exitosa
} else {
    echo json_encode(["success" => false, "message" => "Correo o contraseña incorrectos."]);
}

$sql->close();
$conexion->close();

?>

