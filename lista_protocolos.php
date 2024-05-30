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

// Consulta para obtener los archivos PDF de la tabla "protocolos"
$sql = "SELECT * FROM protocolos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Protocolos</title>
    <style>
        .archivo {
            margin: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
</head>
<body>
    <h1>Lista de Protocolos</h1>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $nombreArchivo = $row['titulo'];
            //$rutaArchivo = $row['archivo'];
    ?>
            <div class="archivo">
                <p><?php echo $nombreArchivo; ?></p>
                <a href="descomprimir_archivo.php?archivo=<?php echo $rutaArchivo; ?>"><button>Visualizar</button></a>
            </div>
    <?php
        }
    } else {
        echo "No se encontraron protocolos.";
    }

    $conn->close();
    ?>
</body>
</html>
