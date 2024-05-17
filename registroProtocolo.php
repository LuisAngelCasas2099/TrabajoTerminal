<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $numero_boleta = $_POST['numero_boleta'];
    $titulo = $_POST['titulo'];
    $directores = $_POST['directores'];
    $descripcion = $_POST['descripcion'];

    
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["documento_pdf"]["name"]);
    $uploadOk = 1;
    $pdfFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    
    if($pdfFileType != "pdf") {
        echo "Solo se permiten archivos PDF.";
        $uploadOk = 0;
    }

    
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["documento_pdf"]["tmp_name"], $target_file)) {
            echo "El archivo ". basename( $_FILES["documento_pdf"]["name"]). " ha sido subido.";
        } else {
            echo "Hubo un error al subir el archivo.";
        }
    }
    echo "<h2>Los datos recibidos son:</h2>";
    echo "<p>Nombre: $nombre</p>";
    echo "<p>Apellido Paterno: $apellido_paterno</p>";
    echo "<p>Apellido Materno: $apellido_materno</p>";
    echo "<p>Número de Boleta: $numero_boleta</p>";
    echo "<p>Título del Protocolo: $titulo</p>";
    echo "<p>Directores: $directores</p>";
    echo "<p>Descripción Breve: $descripcion</p>";
    echo "<p>Archivo PDF: <a href='$target_file'>Descargar</a></p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Protocolo de Titulación</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="header">
        <h1>Registro de Protocolo de Titulación</h1>
    </div>

    <div class="container">
        <h2>Por favor, complete el formulario:</h2>
        <form id="registroForm" method="post" enctype="multipart/form-data">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>

            <label for="apellido_paterno">Apellido Paterno:</label>
            <input type="text" id="apellido_paterno" name="apellido_paterno" required><br><br>

            <label for="apellido_materno">Apellido Materno:</label>
            <input type="text" id="apellido_materno" name="apellido_materno" required><br><br>

            <label for="numero_boleta">Número de Boleta:</label>
            <input type="text" id="numero_boleta" name="numero_boleta" required><br><br>

            <label for="titulo">Título del Protocolo:</label>
            <input type="text" id="titulo" name="titulo" required><br><br>

            <label for="directores">Directores:</label>
            <input type="text" id="directores" name="directores" required><br><br>

            <label for="descripcion">Descripción Breve:</label><br>
            <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea><br><br>

            <label for="documento_pdf">Subir Documento PDF:</label>
            <input type="file" id="documento_pdf" name="documento_pdf" accept=".pdf" required><br><br>

            <input type="submit" value="Registrar Protocolo">
        </form>
    </div>

    <div class="footer">
        <p>Desarrollado para la Escuela Superior de Computo</p>
    </div>
</body>
</html>