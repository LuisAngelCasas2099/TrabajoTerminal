<?php
// Establecer la conexión a la base de datos (debes completar con tus propios datos de conexión)
$servername = "localhost";
$username = "Admin";
$password = "gamer4life";
$dbname = "basededatos";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Obtener el nombre del usuario asociado al correo y contraseña ingresados
$correo = $_POST['correo']; // Suponiendo que obtienes el correo y contraseña del formulario de inicio de sesión
$contrasena = $_POST['contrasena'];

// Consulta SQL para obtener el nombre del usuario
$sql = "SELECT nombre FROM usuarios WHERE correo = '$correo' AND contrasena = '$contrasena'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $nombreUsuario = $row['nombre'];
} else {
    $nombreUsuario = "Usuario no encontrado"; // Mensaje de error si no se encuentra el usuario
}

$conn->close();
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
        <div>Bienvenido <?php echo $nombreUsuario; ?></div>
        <div class="dropdown">
            <button class="dropbtn">Opciones</button>
            <div class="dropdown-content">
                <a href="#">Inicio</a>
                <a href="#">Registro de Protocolo</a>
                <a href="#">Propuestas de Protocolos</a>
                <a href="#">Evaluación</a>
                <a href="#">Cambiar Contraseña</a>
                <a href="cambiarcorreo.php">Perfil</a>
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
