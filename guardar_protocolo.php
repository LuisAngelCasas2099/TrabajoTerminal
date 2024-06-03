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
$sql = "UPDATE protocolos SET Ac1 = $ac1, Ac2 = $ac2, Ac3 = $ac3 WHERE nombre_archivo = '$archivo'";
if ($conn->query($sql) === TRUE) {
    echo "Los datos se actualizaron correctamente.";
} else {
    echo "Error al actualizar los datos: " . $conn->error;
}

$conn->close();
?>
