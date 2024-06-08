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
$apellido = $_POST['apellido'];
$boleta = $_POST['boleta'];
$correo = $_POST['correo'];
$telefono = $_POST['telefono'];
$contrasena = $_POST['contrasena'];

// Asignar el valor correspondiente a la columna "id" de la tabla "usuarios"
if (strpos($correo, '@alumno.ipn.mx') !== false) {
    $id = 1;
} elseif (strpos($correo, '@ipn.mx') !== false) {
    $id = 0;
} else {
    $id = null;
}

// Insertar los datos en la base de datos
$sql = "INSERT INTO usuarios (nombre, apellido, boleta, correo, telefono, contrasena, id) VALUES ('$nombre', '$apellido', '$boleta', '$correo', '$telefono', '$contrasena', '$id')";

if ($conn->query($sql) === TRUE) {
    echo "Usuario registrado exitosamente.";
    header("Location: Login.php");
    exit;
} else {
    echo "Error al registrar el usuario: " . $conn->error;
}

$conn->close();
?>
