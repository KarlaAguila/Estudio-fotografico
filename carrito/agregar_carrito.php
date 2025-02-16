<?php
    session_start();
    ini_set('display_errors', 1);  // Activa la visualización de errores
    error_reporting(E_ALL);        // Muestra todos los errores posibles

    if (!isset($_SESSION['id_usuario'])) {  // Verifica si el ID de usuario está guardado en la sesión
        echo json_encode(["success" => false, "message" => "No has iniciado sesión."]);
        exit;
    }

    $usuario_id = $_SESSION['id_usuario']; // Obtener el id_usuario desde la sesión

    $conexion = new mysqli("localhost", "root", "", "estudio");
    if ($conexion->connect_error) {
        die(json_encode(["success" => false, "message" => "Error de conexión: " . $conexion->connect_error]));
    }

    $id_paquete = $_POST['id_paquete'];
    $precio = $_POST['precio'];

    // Verificar si ya existe un carrito para este usuario
    $sql = "SELECT id_carrito FROM CarritoCompras WHERE id_usuario = $usuario_id AND estado_c = 'pendiente'";
    $resultado = $conexion->query($sql);

    if (!$resultado) {
        die(json_encode(["success" => false, "message" => "Error en la consulta: " . $conexion->error]));
    }

    if ($resultado->num_rows > 0) {
        $carrito = $resultado->fetch_assoc();
        $id_carrito = $carrito['id_carrito'];
        if (!$conexion->query("UPDATE CarritoCompras SET n_paquetes = n_paquetes + 1, total_c = total_c + $precio WHERE id_carrito = $id_carrito")) {
            die(json_encode(["success" => false, "message" => "Error al actualizar el carrito: " . $conexion->error]));
        }
    } else {
        if (!$conexion->query("INSERT INTO CarritoCompras (id_usuario, n_paquetes, total_c) VALUES ($usuario_id, 1, $precio)")) {
            die(json_encode(["success" => false, "message" => "Error al insertar el carrito: " . $conexion->error]));
        }
    }

    echo json_encode(["success" => true]);

    $conexion->close();

?>


