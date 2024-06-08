<?php
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}

// Establecer la conexión a la base de datos y obtener los protocolos (este código ya debería estar en tu archivo lista_academia.php)
$servername = "localhost";
$username = "Admin";
$password = "gamer4life";
$dbname = "basededatos";


$id = $_SESSION['id'];

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
    </style>
</head>
<body>
    <div class="header">
        <h1>Lista de Protocolos</h1>
        <div class="dropdown">
            <button class="dropbtn">Menú</button>
            <div class="dropdown-content">
                <?php if ($id == 3 || $id == 4): ?>
                    <a href="lista_asignacion.php">Lista Protocolos</a>
                <?php endif; ?>
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
                <a href="login.php">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <table>
        <tr>
            <th>Título</th>
            <th>Archivo</th>
            <th>Acciones</th>
        </tr>
        <?php if (!empty($protocolos)) : ?>
            <?php foreach ($protocolos as $protocolo): ?>
                <tr>
                    <td><?php echo htmlspecialchars($protocolo['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($protocolo['nombre_archivo']); ?></td>
                    <td>
                        <a href="visualizador.php?archivo=<?php echo urlencode($protocolo['nombre_archivo']); ?>">Visualizar Protocolo</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No hay protocolos disponibles para evaluar</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
