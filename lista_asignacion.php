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

// Consulta para obtener los protocolos
$sql = "SELECT * FROM protocolos WHERE Ac1 = 0 AND Ac2 = 0 AND Ac3 = 0";
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
    <title>Lista de Protocolos</title>
    <link rel="stylesheet" type="text/css" href="styles3.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .pdf-container {
            width: 100%;
            height: 600px;
            margin-bottom: 20px;
        }

        .pdf-container embed {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lista de Protocolos</h1>
    </div>

    <table>
        <tr>
            <th>Título</th>
            <th>Archivo</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($protocolos as $protocolo) : ?>
            <tr>
                <td><?php echo $protocolo['titulo']; ?></td>
                <td><?php echo $protocolo['nombre_archivo']; ?></td>
                <td>
                    <a href="asignar_protocolo.php?archivo=<?php echo $protocolo['nombre_archivo']; ?>">Asignar Protocolo</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
