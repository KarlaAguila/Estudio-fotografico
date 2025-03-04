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
    <title>Paquetes</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Forum&display=swap" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="../img/logo.png" alt="Logo Photography" width="80px">
        </div>
        <nav class="navbar">
            <a href="../inicio/inicio.html">INICIO</a>
            <a href="../paquetes/paquetes.php" class="protected">PAQUETES</a>
            <a href="../promociones/promociones.html" class="protected">PROMOCIONES</a>
            <a href="../carrito/carrito.php" class="protected">CARRITO</a>
            <a href="../perfil/perfil.html" class="protected">PERFIL</a>
            <button id="authButton" class="btn cerrar-sesion"></button>
        </nav>
    </header>

    <main class="main-content">
        <div class="paquetes-container">
            <?php foreach($paquetes as $paquete): ?>
                <div class="paquete">
                    <img src="data:image/jpeg;base64,<?= base64_encode($paquete['imagenes']) ?>" alt="<?= htmlspecialchars($paquete['nombre_paquete']) ?>" class="paquete-imagen">
                    <h3><?= htmlspecialchars($paquete['nombre_paquete']) ?></h3>
                    <p>Precio: $<?= number_format($paquete['precio'], 2) ?></p>
                    <p><?= htmlspecialchars($paquete['descripcion']) ?></p>
                    <button class="btn agregar-carrito" data-id="<?= $paquete['id_paquete'] ?>" data-precio="<?= $paquete['precio'] ?>">Agregar al carrito</button>

                </div>
            <?php endforeach; ?>
        </div>
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
                    authButton.textContent = 'INICIAR SESIÓN';
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


        //Agregar al carrito de compras
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".agregar-carrito").forEach(button => {
                button.addEventListener("click", function () {
                    const paqueteId = this.getAttribute("data-id");
                    const precio = this.getAttribute("data-precio");

                    fetch("../carrito/agregar_carrito.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id_paquete=${paqueteId}&precio=${precio}`
                })
                .then(response => response.text()) // Cambia a response.text() para ver el contenido completo
                .then(data => {
                    console.log(data);  // Imprime el contenido recibido
                    try {
                        const jsonData = JSON.parse(data);  // Intenta parsear el JSON
                        if (jsonData.success) {
                            alert("Agregado al carrito correctamente.");
                        } else {
                            alert("Error al agregar al carrito.");
                        }
                    } catch (error) {
                        console.error("Error al parsear JSON:", error);
                        alert("Hubo un error al procesar la respuesta.");
                    }
                })
                .catch(error => {
                    console.error("Error en el fetch:", error);
                });

                });
            });
        });
    </script>


    <style>
        .paquetes-container {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: 20px;
            padding: 20px;
            white-space: nowrap;
        }

        .paquete {
            flex: 0 0 auto;
            width: 350px;
            height: 500px;
            padding: 15px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .paquete:hover {
            transform: scale(1.05);
        }

        .paquete-imagen {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }

        .paquete h3, .paquete p {
            margin: 10px 0;
        }
    </style>
</body>
</html>

