<?php
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}

$nombre = $_SESSION['nombre'];
$correo = $_SESSION['correo'];
$telefono = $_SESSION['telefono'];
$boleta = $_SESSION['boleta'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Perfil</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="menu">
            <div class="dropdown">
                <button class="dropbtn">Menú</button>
                <div class="dropdown-content">
                    <a href="inicio.php">Inicio</a>
                    <a href="#">Perfil</a>
                    <a href="#">Protocolo</a>
                </div>
            </div>
        </div>
        <h1>Bienvenido, <?php echo $nombre; ?></h1>
        <h2 class="titulo">Perfil</h2>
        
        <div class="datos">
            <div class="datos-box">
                <p><strong>Nombre:</strong> <?php echo $nombre; ?></p>
                <p><strong>Correo:</strong> <?php echo $correo; ?></p>
                <p><strong>Teléfono:</strong> <?php echo $telefono; ?></p>
                <p><strong>Boleta:</strong> <?php echo $boleta; ?></p>
                <button class="modificar-btn">Modificar Datos</button>
            </div>
            <div class="protocolo">
                <h3>Protocolo</h3>
                <p>Aquí va el contenido del protocolo...</p>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer">
            <p>Desarrollado para la Escuela Superior de Computo</p>
        </div>
    </footer>
</body>
</html>
