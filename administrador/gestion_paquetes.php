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

    // Consultar los paquetes
    $sql = "SELECT id_paquete, nombre_paquete, precio, imagenes, descripcion FROM Paquetes";
    $result = $conn->query($sql);

    $paquetes = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $paquetes[] = $row;
        }
    }

    $conn->close();
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion paquetes</title>
    <link rel="stylesheet" href="gestion_paquetes.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Forum&display=swap" rel="stylesheet">
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
            <h1>Gestión de Paquetes</h1>
            <button class="btn" onclick="window.location.href='agregar_paquete.php'">Añadir Nuevo Paquete</button>
        </section>

        <div class="paquetes-container">
            <?php foreach($paquetes as $paquete): ?>
                <div class="paquete">
                    <img src="data:image/jpeg;base64,<?= base64_encode($paquete['imagenes']) ?>" alt="<?= htmlspecialchars($paquete['nombre_paquete']) ?>" class="paquete-imagen">
                    <h3><?= htmlspecialchars($paquete['nombre_paquete']) ?></h3>
                    <p>Precio: $<?= number_format($paquete['precio'], 2) ?></p>
                    <p><?= htmlspecialchars($paquete['descripcion']) ?></p>
                    <button class="btn editar-paquete" data-id="<?= $paquete['id_paquete'] ?>">Editar</button>
                    <button class="btn eliminar-paquete" data-id="<?= $paquete['id_paquete'] ?>">Eliminar</button>
                </div>
            <?php endforeach; ?>
        </div>
    </main>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

            // Configurar botón de autenticación
            const authButton = document.getElementById('authButton');
            if (isLoggedIn) {
                authButton.textContent = 'CERRAR SESIÓN';
                authButton.onclick = function() {
                    localStorage.setItem('isLoggedIn', 'false');
                    window.location.href = '../inicio/inicio.html';
                };
                document.getElementById('loginBtn').style.display = 'none';
                document.getElementById('registerBtn').style.display = 'none';
            } else {
                authButton.onclick = function() {
                    window.location.href = '../login/login.html';
                };
            }

            // Bloquear navegación en enlaces protegidos
            document.querySelectorAll('.protected').forEach(link => {
                link.addEventListener('click', function(event) {
                    if (!isLoggedIn) {
                        event.preventDefault();
                        alert('Debes iniciar sesión para acceder a esta sección.');
                        window.location.href = 'login.html';
                    }
                });
            });

            // Redirigir si intenta acceder directamente a páginas protegidas
            const restrictedPages = [
                'inicio.html',
                'gestion_paquetes.php',
                'pagos.html',
                'perfil.html'
            ];

            restrictedPages.forEach(page => {
                if (window.location.pathname.includes(page) && !isLoggedIn) {
                    alert('Debes iniciar sesión para acceder a esta sección.');
                    window.location.href = 'login.html';
                }
            });
        });


         // Eliminar paquete
        document.querySelectorAll('.eliminar-paquete').forEach(button => {
            button.addEventListener('click', function() {
                const paqueteId = this.getAttribute('data-id');
                if (confirm('¿Seguro que deseas eliminar este paquete?')) {
                    fetch('eliminar_paquete.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: 'id_paquete=' + paqueteId
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        location.reload(); // Recargar la página para reflejar los cambios
                    });
                }
            });
        });

        // Redirigir a la página de edición
        document.querySelectorAll('.editar-paquete').forEach(button => {
            button.addEventListener('click', function() {
                const paqueteId = this.getAttribute('data-id');
                window.location.href = `editar_paquete.php?id=${paqueteId}`;
            });
        });
    </script>
</body>
</html>
