<?php
// Definir el id del usuario actual
$id = 3; // Cambiar por el id del usuario actual

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
$stmt = $conn->prepare("SELECT * FROM protocolos WHERE Ac1 = 0 AND Ac2 = 0 AND Ac3 = 0");
$stmt->execute();
$result = $stmt->get_result();

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
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
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

        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            margin: 10px 2px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="mensaje-bienvenida">
            <h2>Bienvenido, <?php if ($id == 0) {
                echo "docente";
            } elseif ($id == 3) {
                echo "jefe";
            } ?>
        </div>
        <h1>Lista de Protocolos</h1>
        <div class="dropdown">
            <button class="dropbtn">Menú</button>
            <div class="dropdown-content">
                <?php if ($id == 0): ?>
                    <a href="lista_academia.php">Lista Protocolos</a>
                <?php endif; ?>
                <?php if ($id == 3 || $id == 4): ?>
                    <a href="alta_profesores.php">Alta de profesores</a>
                <?php endif; ?>
                <a href="perfil.php">Perfil</a>
                <?php if ($id == 1): ?>
                    <a href="registroProtocolo.php">Registro de Protocolo</a>
                <?php endif; ?>
                <?php if ($id == 0): ?>
                    <a href="evaluacionProtocolos.php">Evaluacion Protocolos </a>
                <?php endif; ?>
                <a href="login.php">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <table>
        <tr>
            <th>Título</th>
            <th>Archivo</th>
            <th>Palabras clave</th>
            <th>Asignar</th>
            <th>Reasignar</th>
        </tr>
        <?php if (!empty($protocolos)) : ?>
            <?php foreach ($protocolos as $protocolo): ?>
                <tr>
                    <td><?php echo htmlspecialchars($protocolo['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($protocolo['nombre_archivo']); ?></td>
                    <td><?php echo htmlspecialchars($protocolo['palabrasclave']); ?></td>
                    <td>
                        <a href="asignar_protocolo.php?archivo=<?php echo htmlspecialchars($protocolo['nombre_archivo']); ?>">Asignar Protocolo</a>
                    </td>
                    <td>
                        <a href="asignar_protocolo.php?archivo=<?php echo htmlspecialchars($protocolo['nombre_archivo']); ?>">Reasignar Protocolo</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No hay protocolos disponibles para asignar</td>
            </tr>
        <?php endif; ?>
    </table>

    <form action="enviar_notificacion.php" method="post" style="text-align: center; margin-top: 20px;">
        <button type="submit" class="button">Enviar notificación</button>
    </form>
</body>

</html>
