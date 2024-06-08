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

// Obtener los valores enviados por el formulario POST
$archivo = $_POST['archivo'];
$ac1 = $_POST['ac1'];
$ac2 = $_POST['ac2'];
$ac3 = $_POST['ac3'];

// Actualizar las columnas "Ac1", "Ac2" y "Ac3" en la fila correspondiente del archivo seleccionado en la base de datos
$stmt = $conn->prepare("UPDATE protocolos SET Ac1 = ?, Ac2 = ?, Ac3 = ? WHERE nombre_archivo = ?");
$stmt->bind_param("iiis", $ac1, $ac2, $ac3, $archivo);

if ($stmt->execute()) {
    echo "Los datos se actualizaron correctamente.";
} else {
    echo "Error al actualizar los datos: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
