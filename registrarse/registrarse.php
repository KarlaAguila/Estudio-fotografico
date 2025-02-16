<?php
session_start();

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "Estudio");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener datos del formulario
$nombre = $conexion->real_escape_string(trim($_POST['name']));
$email = $conexion->real_escape_string(trim($_POST['email']));
$password = $conexion->real_escape_string(trim($_POST['password']));
$repeat_password = $conexion->real_escape_string(trim($_POST['repeat-password']));
$telefono = $conexion->real_escape_string(trim($_POST['phone']));

// Validar que las contraseñas coincidan
if ($password !== $repeat_password) {
    echo "<script>alert('Las contraseñas no coinciden.'); window.location.href='registrarse.html';</script>";
    exit();
}

// Insertar en la base de datos
$sql = "INSERT INTO Usuarios (correo_electronico, nombre_usuario, numero_telefono, contraseña) 
        VALUES ('$email', '$nombre', '$telefono', '$password')";

if ($conexion->query($sql) === TRUE) {
    echo "<script>alert('Registro exitoso.'); window.location.href='../login/login.html';</script>";
} else {
    echo "<script>alert('Error al intentar registrar.'); window.location.href='registrarse.html';</script>";
}

$conexion->close();
?>


