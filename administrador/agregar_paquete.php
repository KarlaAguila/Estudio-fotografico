<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Paquete</title>
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
        <h1>Añadir Nuevo Paquete</h1>
        <form action="guardar_paquete.php" method="POST" enctype="multipart/form-data">
            <label for="nombre_paquete">Nombre del Paquete:</label>
            <input type="text" name="nombre_paquete" id="nombre_paquete" required><br><br>

            <label for="precio">Precio:</label>
            <input type="text" name="precio" id="precio" required><br><br>

            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" id="descripcion" />


            <label for="imagenes">Imagen:</label>
            <input type="file" name="imagenes" id="imagenes" accept="image/*" required><br><br>

            <button type="submit" class="btn">Añadir Paquete</button>
        </form>
    </main>
</body>
</html>
