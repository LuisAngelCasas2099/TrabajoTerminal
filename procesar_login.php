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

// Verificar las credenciales
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

$sql = "SELECT * FROM usuarios WHERE correo = '$correo' AND contrasena = '$contrasena'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    session_start();
    $_SESSION['nombre'] = $row['nombre'];
    $_SESSION['correo'] = $row['correo'];
    $_SESSION['telefono'] = $row['telefono'];
    $_SESSION['boleta'] = $row['boleta'];
    $response = array('success' => true);
} else {
    $response = array('error' => 'Credenciales incorrectas. Por favor, inténtalo de nuevo.');
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
