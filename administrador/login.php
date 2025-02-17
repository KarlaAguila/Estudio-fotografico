<?php
    session_start();
    header('Content-Type: application/json');

    $usuario_correcto = "Administrador8";
    $contrasena_correcta = "administrador";

    // Obtener los datos del formulario
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Registrar en logs para depuración
    error_log("Usuario recibido: " . $username);
    error_log("Contraseña recibida: " . $password);

    if ($username === $usuario_correcto && $password === $contrasena_correcta) {
        $_SESSION['admin_logged_in'] = true;  // Almacena sesión
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Usuario o contraseña incorrectos"]);
    }
?>


