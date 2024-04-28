<?php
// Establecer la conexión a la base de datos
$servername = "localhost";
$username = "Admin";
$password = "gamer4life";
$dbname = "basededatos";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Recibir los datos del formulario
$nombre = $_POST['nombre'];
$boleta = $_POST['boleta'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$contrasena = $_POST['contrasena'];

// Insertar los datos en la base de datos
$sql = "INSERT INTO usuarios (nombre, boleta, correo, telefono, contrasena) VALUES ('$nombre', '$boleta', '$correo', '$telefono', '$contrasena')";

if ($conn->query($sql) === TRUE) {
    echo "Usuario registrado exitosamente.";
    header("Location: Login.php");
    exit;
} else {
    echo "Error al registrar el usuario: " . $conn->error;
}

$conn->close();
?>
