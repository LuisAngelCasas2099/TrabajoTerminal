<?php
session_start();

$nombre = $_SESSION['nombre'];

if (isset($_SESSION['correo']) && isset($_SESSION['contrasena'])) {
    $correo = $_SESSION['correo'];
    $contrasena = $_SESSION['contrasena'];

    // Establecer la conexión a la base de datos
    $servername = "localhost";
    $username = "Admin";
    $password = "gamer4life";
    $dbname = "basededatos";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // Consulta para obtener el nombre asociado al correo del usuario
    $sql = "SELECT nombre FROM usuarios WHERE correo = '$correo'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nombre = $row['nombre'];
        } else {
            $nombre = "Usuario no encontrado en la base de datos";
        }
    } else {
        $nombre = "Error en la consulta SQL: " . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inicio</title>
    <link rel="icon" type="image/png" href="Logo_Escom.png">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>Bienvenido <?php echo $nombre; ?></div>
        <div class="dropdown">
            <button class="dropbtn">Opciones</button>
            <div class="dropdown-content">
                <a href="#">Inicio</a>
                <a href="#">Registro de Protocolo</a>
                <a href="#">Propuestas de Protocolos</a>
                <a href="#">Evaluación</a>
                <a href="#">Cambiar Contraseña</a>
                <a href="perfil.php">Perfil</a>
                <a href="Login.php">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Contenido de la página de inicio -->
    </div>

    <div class="footer">
        <p>Desarrollado para la Escuela Superior de Computo</p>
    </div>
    
</body>
</html>
