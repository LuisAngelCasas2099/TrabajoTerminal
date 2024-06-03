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

session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}

$nombre = $_SESSION['nombre'];
$correo = $_SESSION['correo'];
$telefono = $_SESSION['telefono'];
$boleta = $_SESSION['boleta'];
$id = $_SESSION['id'];
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
    <div>
    <h1>Lista de Protocolos</h1>
    <div class="dropdown">
            <button class="dropbtn">Menú</button>
            <div class="dropdown-content">
                <?php if ($id == 0) : ?>
                    <a href="lista_asignacion.php">Lista Protocolos</a>
                <?php endif; ?>
                <?php if ($id == 2 || $id == 3) : ?>
                    <a href="alta_profesores.php">Alta de profesores</a>
                <?php endif; ?>
                <?php if ($id == 2 || $id == 3) : ?>
                    <a href="lista_protocolos.php">Lista para asignación</a>
                <?php endif; ?>
                <a href="perfil.php">Perfil</a>
                <?php if ($id == 1) : ?>
                <a href="registroProtocolo.php">Registro de Protocolo</a>
                <?php endif; ?>
                <a href="visualizador.php">Visualizador</a>
                <?php if ($id == 2 || $id == 3) : ?>
                <a href="evaluacionProtocolos.php">Evaluacion Protocolos </a>
                <?php endif; ?>
                <a href="login.php">Cerrar Sesión</a>
            </div>
        </div>
    </div>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $nombreArchivo = $row['titulo'];
            //$rutaArchivo = $row['archivo'];
    ?>
            <div class="archivo">
                <p><?php echo $nombreArchivo; ?></p>
                <a href="asignar_protocolo.php?archivo=<?php echo $rutaArchivo; ?>"><button>Visualizar</button></a>
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
