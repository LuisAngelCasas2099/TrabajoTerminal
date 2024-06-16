<?php
// Establecer la conexión a la base de datos
$servername = "localhost";
$username = "Admin";
$password = "gamer4life";
$dbname = "basededatos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Consulta para obtener los correos
$stmt = $conn->prepare("SELECT correo FROM usuarios WHERE correo LIKE '%@ipn.mx%'");
$stmt->execute();
$result = $stmt->get_result();

$correos = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $correos[] = $row['correo'];
    }
}

$stmt->close();
$conn->close();

$subject = "Notificación de protocolos";
$message = "Los protocolos han sido enviados para su visualización y revisión. Favor de revisar el sistema. Gracias";
$headers = "From: no-reply@tu-dominio.com";

foreach ($correos as $correo) {
    mail($correo, $subject, $message, $headers);
}

header("Location: lista_asignacion.php?notificacion=enviada");
exit();
?>
