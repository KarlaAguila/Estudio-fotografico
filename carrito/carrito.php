<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/login.html");
    exit;
}

$usuario_id = $_SESSION['id_usuario']; // ID del usuario en sesión

$conexion = new mysqli("localhost", "root", "", "estudio");
if ($conexion->connect_error) {
    die("Error de conexión.");
}

// Obtener el carrito del usuario
$sql = "SELECT * FROM CarritoCompras WHERE id_usuario = $usuario_id AND estado_c = 'pendiente'";
$resultado = $conexion->query($sql);
$carrito = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="styles.css">
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

    <?php if ($carrito): ?>
        <p>Número de paquetes: <?= $carrito['n_paquetes'] ?></p>
        <p>Total a pagar: $<?= number_format($carrito['total_c'], 2) ?></p>
        <button class="btn eliminar-carrito">Vaciar Carrito</button>
    <?php else: ?>
        <p>Tu carrito está vacío.</p>
    <?php endif; ?>
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
                const restrictedPages = ['paquetes.html', 'promociones.html', 'carrito.html', 'perfil.html'];
                const currentPage = window.location.pathname.split('/').pop();
        
                if (restrictedPages.includes(currentPage) && !isLoggedIn) {
                    alert('Debes iniciar sesión para acceder a esta sección.');
                    window.location.href = '../login/login.html';
                }
            });

    document.querySelector(".eliminar-carrito").addEventListener("click", function() {
        fetch("eliminar_carrito.php", {
            method: "POST"
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Carrito eliminado.");
                window.location.reload();
            } else {
                alert("Error al eliminar carrito.");
            }
        });
    });
</script>

</body>
</html>
