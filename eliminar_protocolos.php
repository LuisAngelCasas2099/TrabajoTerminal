<?php
$conexion = new mysqli('localhost', 'Admin', 'gamer4life', 'basededatos');

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$query = "DELETE FROM protocolos";
$resultado = $conexion->query($query);

if ($resultado) {
    echo "Protocolos eliminados correctamente.";
} else {
    echo "Error al eliminar los protocolos: " . $conexion->error;
}

$conexion->close();

header("Location: perfil.php");
exit();
?>
