<?php
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

// Inicializar variables para los valores seleccionados
$ac1 = $ac2 = $ac3 = "";

// Manejar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ac1 = $_POST['ac1'];
    $ac2 = $_POST['ac2'];
    $ac3 = $_POST['ac3'];

    // Mostrar un mensaje con los valores seleccionados
    $mensaje = "Academia 1: $ac1<br>Academia 2: $ac2<br>Academia 3: $ac3";

    // Redirigir a la página lista_protocolos.php
    header("Location: lista_protocolos.php");
    exit();
}

// Obtener el archivo PDF de la URL
$archivo = isset($_GET['archivo']) ? $_GET['archivo'] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Visualizador</title>
    <link rel="stylesheet" type="text/css" href="styles3.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9ebee;
            color: #1c1e21;
            margin: 0;
            padding: 0;
            position: relative;
            height: 100vh; /* Asegura que el cuerpo ocupe toda la altura de la ventana */
            overflow: hidden; /* Evita el desplazamiento de la página principal */
        }

        .header {
            background-color: #4267b2;
            padding: 10px;
            text-align: center;
            color: white;
            position: relative;
        }

        h1 {
            color: white;
            font-size: 24px;
            margin: 0;
        }

        .main-container {
            display: flex;
            height: 100vh; /* Altura total de la ventana */
            overflow: hidden; /* Evita el desplazamiento en el contenedor principal */
        }

        .pdf-container {
            flex: 1; /* Ocupa todo el espacio disponible */
            margin: 10px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow-y: auto; /* Agregado para permitir desplazamiento vertical */
        }

        .pdf-container embed {
            width: 100%;
            height: 100%; /* Ocupa toda la altura disponible */
        }

        .mensaje {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
        }

        .evaluar-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px;
        }

        .evaluar-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Visualizador</h1>
        <a href="evaluacionProtocolos.php" class="evaluar-btn">Evaluar</a>
    </div>

    <div class="main-container">
        <div class="pdf-container">
            <?php if (!empty($archivo)): ?>
                <embed src="uploads/<?php echo htmlspecialchars($archivo); ?>" type="application/pdf" />
            <?php else: ?>
                <p>No se ha proporcionado un archivo para visualizar.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
