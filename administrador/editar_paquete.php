<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "estudio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se pasó un ID de paquete a editar
if (isset($_GET['id'])) {
    $id_paquete = $_GET['id'];

    // Consultar el paquete con el ID proporcionado
    $sql = "SELECT * FROM Paquetes WHERE id_paquete = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_paquete);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $paquete = $result->fetch_assoc();
    } else {
        echo "Paquete no encontrado";
        exit();
    }
} else {
    echo "No se proporcionó un ID de paquete";
    exit();
}

// Cerrar la conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paquete</title>
    <link rel="stylesheet" href="gestion_paquetes.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="../img/logo.png" alt="Logo Photography" width="80px">
        </div>
        <nav class="navbar">
            <a href="inicio.html">INICIO</a>
            <a href="gestion_paquetes.php" class="protected">GESTION DE PAQUETES</a>
            <a href="pagos.html" class="protected">PAGOS</a>
            <a href="perfil.html" class="protected">PERFIL</a>
            <button id="authButton" class="btn iniciar-sesion">INICIAR SESION</button>
        </nav>
    </header>

    <main class="main-content">
        <section class="hero">
            <h1>Editar Paquete</h1>
            <form action="guardar_edicion_paquete.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_paquete" value="<?= $paquete['id_paquete'] ?>">

                <label for="nombre_paquete">Nombre del Paquete:</label>
                <input type="text" id="nombre_paquete" name="nombre_paquete" value="<?= htmlspecialchars($paquete['nombre_paquete']) ?>" required>

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" value="<?= $paquete['precio'] ?>" required>

                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required><?= htmlspecialchars($paquete['descripcion']) ?></textarea>

                <label for="disponibilidad">Disponibilidad:</label>
                <select id="disponibilidad" name="disponibilidad">
                    <option value="1" <?= $paquete['disponibilidad'] == 1 ? 'selected' : '' ?>>Disponible</option>
                    <option value="0" <?= $paquete['disponibilidad'] == 0 ? 'selected' : '' ?>>No Disponible</option>
                </select>

                <label for="imagenes">Imagenes:</label>
                <input type="file" id="imagenes" name="imagenes[]" multiple accept="image/*">

                <button type="submit" class="btn">Guardar Cambios</button>
            </form>
        </section>
    </main>

    <script>
        // Verificar si el usuario está autenticado
        document.addEventListener('DOMContentLoaded', function() {
            const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

            const authButton = document.getElementById('authButton');
            if (isLoggedIn) {
                authButton.textContent = 'CERRAR SESIÓN';
                authButton.onclick = function() {
                    localStorage.setItem('isLoggedIn', 'false');
                    window.location.href = 'inicio.html';
                };
            } else {
                authButton.onclick = function() {
                    window.location.href = 'login.html';
                };
            }
        });
    </script>
</body>
</html>
