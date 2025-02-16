<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    die("Debes iniciar sesión para ver tu carrito.");
}

$usuario_id = $_SESSION['id_usuario'];

$conexion = new mysqli("localhost", "root", "", "estudio");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el carrito activo
$sql = "SELECT id_carrito, total_c FROM CarritoCompras WHERE id_usuario = $usuario_id AND estado_c = 'pendiente'";
$resultado = $conexion->query($sql);

if ($resultado->num_rows == 0) {
    die("Tu carrito está vacío.");
}

$carrito = $resultado->fetch_assoc();
$id_carrito = $carrito['id_carrito'];
$total = $carrito['total_c'];

// Obtener los productos del carrito
$sql = "SELECT d.id_paquete, p.nombre_paquete, d.cantidad, d.precio_unitario
        FROM DetallesCarrito d
        JOIN Paquetes p ON d.id_paquete = p.id_paquete
        WHERE d.id_carrito = $id_carrito";
$productos = $conexion->query($sql);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Forum&display=swap" rel="stylesheet">
</head>
<body>

    <header class="header">
            <div class="logo">
                <img src="../img/logo.png" alt="Logo Photography" width="80px">
            </div>
            <nav class="navbar">
                <a href="../inicio/inicio.html" >INICIO</a>
                <a href="../paquetes/paquetes.php" class="protected">PAQUETES</a>
                <a href="../promociones/promociones.html" class="protected">PROMOCIONES</a>
                <a href="../carrito/carrito.php" class="protected">CARRITO</a>
                <a href="../perfil/perfil.html" class="protected">PERFIL</a>
                <button id="authButton" class="btn cerrar-sesion"></button>
            </nav>
    </header>
    <main>
        <h2>Carrito de Compras</h2>

        <table border="1">
            <tr>
                <th>Paquete</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
                <th>Acción</th>
            </tr>

            <?php while ($producto = $productos->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($producto['nombre_paquete']) ?></td>
                    <td><?= $producto['cantidad'] ?></td>
                    <td>$<?= number_format($producto['precio_unitario'], 2) ?></td>
                    <td>$<?= number_format($producto['cantidad'] * $producto['precio_unitario'], 2) ?></td>
                    <td><button onclick="eliminarPaquete(<?= $producto['id_paquete'] ?>)">Eliminar</button></td>
                </tr>
            <?php endwhile; ?>

        </table>

        <p><strong>Total del carrito: </strong>$<?= number_format($total, 2) ?></p>

        <button onclick="vaciarCarrito()">Vaciar Carrito</button>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
        
                // Configurar botón de autenticación
                const authButton = document.getElementById('authButton');
                if (authButton) {
                    if (isLoggedIn) {
                        authButton.textContent = 'CERRAR SESIÓN';
                        authButton.onclick = function() {
                            localStorage.setItem('isLoggedIn', 'false'); // Cerrar sesión
                            window.location.href = '../inicio/inicio.html'; // Redirigir al inicio
                        };
                    } else {
                        authButton.textContent = 'hola';
                        authButton.onclick = function() {
                            window.location.href = '../login/login.html';
                        };
                    }
                }
        
                // Bloquear navegación en enlaces protegidos
                document.querySelectorAll('.protected').forEach(link => {
                    link.addEventListener('click', function(event) {
                        if (!isLoggedIn) {
                            event.preventDefault();
                            alert('Debes iniciar sesión para acceder a esta sección.');
                            window.location.href = '../login/login.html';
                        }
                    });
                });
        
                // Redirigir si el usuario intenta acceder directamente a páginas protegidas
                const restrictedPages = ['paquetes.php', 'promociones.html', 'carrito.php', 'perfil.html'];
                const currentPage = window.location.pathname.split('/').pop();
        
                if (restrictedPages.includes(currentPage) && !isLoggedIn) {
                    alert('Debes iniciar sesión para acceder a esta sección.');
                    window.location.href = '../login/login.html';
                }
            });
     //************************************************ */   
    function eliminarPaquete(idPaquete) {
        if (!confirm("¿Deseas eliminar este paquete del carrito?")) return;

        fetch("eliminar_paquete.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id_paquete=" + idPaquete
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Paquete eliminado.");
                location.reload();
            } else {
                alert("Error al eliminar.");
            }
        });
    }

    function vaciarCarrito() {
        if (!confirm("¿Deseas vaciar todo el carrito?")) return;

        fetch("vaciar_carrito.php", {
            method: "POST"
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Carrito vaciado.");
                location.reload();
            } else {
                alert("Error al vaciar carrito.");
            }
        });
    }
    </script>

</body>
</html>

