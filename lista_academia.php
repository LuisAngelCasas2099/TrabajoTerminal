<?php
// Verificar si se ha iniciado sesión y si la variable de sesión "id" está definida
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

// Establecer la conexión a la base de datos
$servername = "localhost";
$username = "Admin";
$password = "gamer4life";
$dbname = "basededatos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Consulta para obtener los registros de la tabla "protocolos" donde concuerden las columnas mencionadas
$id = $_SESSION['id'];
$sql = "SELECT * FROM protocolos WHERE (Ac1 IN (SELECT academia1 FROM usuarios WHERE id = $id OR id = $id OR id = $id) OR Ac2 IN (SELECT academia2 FROM usuarios WHERE id = $id OR id = $id OR id = $id) OR Ac3 IN (SELECT academia3 FROM usuarios WHERE id = $id OR id = $id OR id = $id))";
$result = $conn->query($sql);

$protocolos = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $protocolos[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Protocolos de la Academia</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="header">
        <h1>Lista de Protocolos de la Academia</h1>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Archivo</th>
        </tr>
        <?php foreach ($protocolos as $protocolo) : ?>
            <tr>
                <td><?php echo $protocolo['idP']; ?></td>
                <td><?php echo $protocolo['titulo']; ?></td>
                <td><?php echo $protocolo['nombre_archivo']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
