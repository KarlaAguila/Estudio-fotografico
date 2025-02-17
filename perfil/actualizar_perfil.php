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
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    // Actualizar la información del usuario incluyendo el correo
    $sql = $conexion->prepare("UPDATE Usuarios SET nombre_usuario = ?, numero_telefono = ?, correo_electronico = ? WHERE id_usuario = ?");
    $sql->bind_param("sssi", $nombre, $telefono, $correo, $id_usuario);

    if ($sql->execute()) {
        echo json_encode(["success" => true, "message" => "Perfil actualizado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al actualizar perfil."]);
    }

    $sql->close();
    $conexion->close();

?>
