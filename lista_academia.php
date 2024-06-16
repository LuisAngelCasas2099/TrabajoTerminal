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

$correo = $_SESSION['correo'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Obtener las academias del usuario según su correo
$stmt = $conn->prepare("SELECT academia1, academia2, academia3 FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->bind_result($academia1, $academia2, $academia3);
$stmt->fetch();
$stmt->close();

// Consulta para obtener los protocolos filtrados por las academias del usuario
$stmt = $conn->prepare("
    SELECT * FROM protocolos
    WHERE (Ac1 = ? OR Ac1 = ? OR Ac1 = ?)
       OR (Ac2 = ? OR Ac2 = ? OR Ac2 = ?)
       OR (Ac3 = ? OR Ac3 = ? OR Ac3 = ?)");
$stmt->bind_param("iiiiiiiii", $academia1, $academia2, $academia3, $academia1, $academia2, $academia3, $academia1, $academia2, $academia3);
$stmt->execute();
$result = $stmt->get_result();

$protocolos = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $protocolos[] = $row;
    }
}

$conn->close();

// Mapeo de las academias
$academias_map = array(
    1 => "Ciencias sociales",
    2 => "Ciencias básicas",
    3 => "Ingeniería de software",
    4 => "Ciencias de la computación",
    5 => "Sistemas distribuidos",
    6 => "Sistemas digitales",
    7 => "Fundamentos de sistemas electrónicos",
    8 => "Inteligencia artificial",
    9 => "Ciencia de datos",
    10 => "Proyectos estratégicos y toma de decisiones"
);

function obtenerNombreAcademias($ac1, $ac2, $ac3, $map) {
    $academias = [];
    if (isset($map[$ac1])) $academias[] = $map[$ac1];
    if (isset($map[$ac2])) $academias[] = $map[$ac2];
    if (isset($map[$ac3])) $academias[] = $map[$ac3];
    return implode(", ", $academias);
}
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
        <div class="mensaje-bienvenida">
            <h2>Bienvenido, <?php if ($_SESSION['id'] == 0) { echo "docente"; } elseif ($_SESSION['id'] == 3) { echo "jefe"; } ?></h2>
        </div>
        <h1>Lista de Protocolos</h1>
        <div class="dropdown">
            <button class="dropbtn">Menú</button>
            <div class="dropdown-content">
                <?php if ($_SESSION['id'] == 3 || $_SESSION['id'] == 4): ?>
                    <a href="lista_asignacion.php">Lista Protocolos</a>
                <?php endif; ?>
                <?php if ($_SESSION['id'] == 0): ?>
                    <a href="lista_academia.php">Lista Protocolos</a>
                <?php endif; ?>
                <?php if ($_SESSION['id'] == 3 || $_SESSION['id'] == 4): ?>
                    <a href="alta_profesores.php">Alta de profesores</a>
                <?php endif; ?>
                <a href="perfil.php">Perfil</a>
                <?php if ($_SESSION['id'] == 1): ?>
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
            <th>Academias</th>
            <th>Acciones</th>
        </tr>
        <?php if (!empty($protocolos)) : ?>
            <?php foreach ($protocolos as $protocolo): ?>
                <tr>
                    <td><?php echo htmlspecialchars($protocolo['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($protocolo['nombre_archivo']); ?></td>
                    <td><?php echo htmlspecialchars(obtenerNombreAcademias($protocolo['Ac1'], $protocolo['Ac2'], $protocolo['Ac3'], $academias_map)); ?></td>
                    <td>
                        <a href="visualizador.php?archivo=<?php echo urlencode($protocolo['nombre_archivo']); ?>">Visualizar Protocolo</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No hay protocolos disponibles para evaluar</td>
            </tr>
        <?php endif; ?>
    </table>
</body>
</html>
