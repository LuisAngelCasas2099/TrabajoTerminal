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

// Consulta para obtener los protocolos
$sql = "SELECT * FROM protocolos WHERE archivo = '$archivo'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $titulo = $row['titulo'];
} else {
    $titulo = "Archivo no encontrado";
}

// Obtener las opciones para los menús desplegables
$sql = "SELECT * FROM opciones";
$result = $conn->query($sql);

$opciones = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $opciones[$row['id']] = $row['opcion'];
    }
}

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
            width: 50%;
            height: 100%;
            float: left;
        }

        .form-container {
            width: 50%;
            height: 100%;
            float: right;
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

    <div class="main-container">
        <div class="pdf-container">
            <iframe id="pdf_preview" style="width:100%;height:100%;" frameborder="0" src="<?php echo $rutaArchivo; ?>"></iframe>
        </div>

        <div class="form-container">
            <h2><?php echo $titulo; ?></h2>
            <form id="asignarForm" method="post">
                <label for="ac1">Actividad 1:</label>
                <select id="ac1" name="ac1">
                    <?php foreach ($opciones as $id => $opcion) : ?>
                        <option value="<?php echo $id; ?>"><?php echo $opcion; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="ac2">Actividad 2:</label>
                <select id="ac2" name="ac2">
                    <?php foreach ($opciones as $id => $opcion) : ?>
                        <option value="<?php echo $id; ?>"><?php echo $opcion; ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="ac3">Actividad 3:</label>
                <select id="ac3" name="ac3">
                    <?php foreach ($opciones as $id => $opcion) : ?>
                        <option value="<?php echo $id; ?>"><?php echo $opcion; ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="submit" value="Guardar">
            </form>
        </div>
    </div>
</body>
</html>
