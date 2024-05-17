<?php

$nombre = $apellido_paterno = $apellido_materno = $numero_boleta = $titulo = $directores = $descripcion = "";
$target_file = "";

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
    $pdfFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($pdfFileType != "pdf") {
        echo "Solo se permiten archivos PDF.";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["documento_pdf"]["tmp_name"], $target_file)) {
            echo "El archivo " . basename($_FILES["documento_pdf"]["name"]) . " ha sido subido.";
        } else {
            echo "Hubo un error al subir el archivo.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrar Protocolo de Titulación</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: space-between;
        }
        .header, .container, .footer {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .header {
            background-color: #f8f8f8;
            border-bottom: 1px solid #ddd;
        }
        .container {
            background-color: #ffffff;
            display: flex;
        }
        .form-container {
            flex: 1;
        }
        .pdf-viewer {
            flex: 1;
            padding-left: 20px;
        }
        .footer {
            background-color: #f8f8f8;
            border-top: 1px solid #ddd;
            text-align: center;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label, input, textarea {
            margin-bottom: 10px;
            text-align: left;
        }
        input[type="submit"] {
            width: fit-content;
            align-self: flex-start;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        iframe {
            width: 100%;
            height: 600px;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Registro de Protocolo de Titulación</h1>
    </div>

    <div class="container">
        <div class="form-container">
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
                <input type="file" id="documento_pdf" name="documento_pdf" accept=".pdf" required onchange="previewPDF()"><br><br>

                <input type="submit" value="Registrar Protocolo">
            </form>
            <?php if ($target_file): ?>
                <h2>Los datos recibidos son:</h2>
                <p>Nombre: <?= $nombre ?></p>
                <p>Apellido Paterno: <?= $apellido_paterno ?></p>
                <p>Apellido Materno: <?= $apellido_materno ?></p>
                <p>Número de Boleta: <?= $numero_boleta ?></p>
                <p>Título del Protocolo: <?= $titulo ?></p>
                <p>Directores: <?= $directores ?></p>
                <p>Descripción Breve: <?= $descripcion ?></p>
                <p>Archivo PDF: <a href="<?= $target_file ?>">Descargar</a></p>
            <?php endif; ?>
        </div>
        <div class="pdf-viewer">
            <iframe id="pdfPreview" frameborder="0"></iframe>
        </div>
    </div>

    <div class="footer">
        <p>Desarrollado para la Escuela Superior de Computo</p>
    </div>

    <script>
        function previewPDF() {
            const file = document.getElementById('documento_pdf').files[0];
            const preview = document.getElementById('pdfPreview');
            const reader = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }
    </script>
</body>
</html>
