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

// Obtener el nombre del archivo PDF a mostrar
$archivo = $_GET['archivo'];
$rutaArchivo = "uploads/" . $archivo;

// Opciones para los menús desplegables
$opciones = array(
    "Ciencias Sociales",
    "Ciencias Básicas",
    "Ingeniería de Software",
    "Ciencias de la Computación",
    "Sistemas Distribuidos",
    "Sistemas Digitales",
    "Fundamentos de Sistemas Electrónicos",
    "Inteligencia Artificial",
    "Ciencia de Datos",
    "Proyectos Estratégicos y Toma de Decisiones"
);

// Guardar los valores de los menús desplegables en la base de datos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ac1 = $_POST['ac1'];
    $ac2 = $_POST['ac2'];
    $ac3 = $_POST['ac3'];

    $sql = "UPDATE protocolos SET Ac1 = '$ac1', Ac2 = '$ac2', Ac3 = '$ac3' WHERE archivo = '$archivo'";
    if ($conn->query($sql) === TRUE) {
        echo "Los valores se asignaron correctamente.";
    } else {
        echo "Error al asignar los valores: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Asignar Protocolo</title>
    <link rel="stylesheet" type="text/css" href="styles3.css">
    <style>
        .pdf-container {
            width: 100%;
            height: 600px;
            margin-bottom: 20px;
        }

        .pdf-container embed {
            width: 100%;
            height: 100%;
        }

        .form-container {
            width: 100%;
            padding: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select {
            width: 100%;
            padding: 5px;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Asignar Protocolo</h1>
    </div>

    <div class="pdf-container">
        <embed src="<?php echo htmlspecialchars($rutaArchivo); ?>" type="application/pdf" />
    </div>

    <div class="form-container">
        <form id="asignarForm" method="post">
            <label for="ac1">Actividad 1:</label>
            <select id="ac1" name="ac1">
                <?php foreach ($opciones as $opcion) : ?>
                    <option value="<?php echo htmlspecialchars($opcion); ?>"><?php echo htmlspecialchars($opcion); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="ac2">Actividad 2:</label>
            <select id="ac2" name="ac2">
                <?php foreach ($opciones as $opcion) : ?>
                    <option value="<?php echo htmlspecialchars($opcion); ?>"><?php echo htmlspecialchars($opcion); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="ac3">Actividad 3:</label>
            <select id="ac3" name="ac3">
                <?php foreach ($opciones as $opcion) : ?>
                    <option value="<?php echo htmlspecialchars($opcion); ?>"><?php echo htmlspecialchars($opcion); ?></option>
                <?php endforeach; ?>
            </select>

            <input type="submit" value="Guardar">
        </form>
    </div>
</body>
</html>