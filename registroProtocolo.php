<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $num_estudiantes = $_POST['num_estudiantes'];
    $num_directores = $_POST['num_directores'];

    $estudiantes = [];
    for ($i = 0; $i < $num_estudiantes; $i++) {
        $estudiantes[] = [
            'nombre' => $_POST["nombre_$i"],
            'apellido_paterno' => $_POST["apellido_paterno_$i"],
            'apellido_materno' => $_POST["apellido_materno_$i"],
            'numero_boleta' => $_POST["numero_boleta_$i"]
        ];
    }

    $directores = [];
    for ($i = 0; $i < $num_directores; $i++) {
        $directores[] = [
            'nombre' => $_POST["director_nombre_$i"],
            'apellido' => $_POST["director_apellido_$i"]
        ];
    }

    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $resumen = $_POST['resumen'];
    $palabras_clave = $_POST['palabras_clave'];

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

    echo "<h2>Los datos recibidos son:</h2>";

    foreach ($estudiantes as $index => $estudiante) {
        echo "<h3>Estudiante " . ($index + 1) . "</h3>";
        echo "<p>Nombre: " . $estudiante['nombre'] . "</p>";
        echo "<p>Apellido Paterno: " . $estudiante['apellido_paterno'] . "</p>";
        echo "<p>Apellido Materno: " . $estudiante['apellido_materno'] . "</p>";
        echo "<p>Número de Boleta: " . $estudiante['numero_boleta'] . "</p>";
    }

    foreach ($directores as $index => $director) {
        echo "<h3>Director " . ($index + 1) . "</h3>";
        echo "<p>Nombre: " . $director['nombre'] . "</p>";
        echo "<p>Apellido: " . $director['apellido'] . "</p>";
    }

    echo "<p>Título del Protocolo: $titulo</p>";
    echo "<p>Descripción Breve: $descripcion</p>";
    echo "<p>Resumen: $resumen</p>";
    echo "<p>Palabras Clave: $palabras_clave</p>";
    echo "<p>Archivo PDF: <a href='$target_file'>Descargar</a></p>";
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Registrar Protocolo de Titulación</title>
    <link rel="stylesheet" type="text/css" href="styles3.css">
    <script>
        function generarCampos() {
            var numEstudiantes = document.getElementById("num_estudiantes").value;
            var estudiantesContenedor = document.getElementById("estudiantes");
            estudiantesContenedor.innerHTML = "";

            if (numEstudiantes > 4) {
                alert("El número máximo de estudiantes es 4.");
                document.getElementById("num_estudiantes").value = 4;
                numEstudiantes = 4;
            }

            for (var i = 0; i < numEstudiantes; i++) {
                estudiantesContenedor.innerHTML += `<h3>Estudiante ${i + 1}</h3>
                <label for="nombre_${i}">Nombre:</label>
                <input type="text" id="nombre_${i}" name="nombre_${i}" required><br><br>

                <label for="apellido_paterno_${i}">Apellido Paterno:</label>
                <input type="text" id="apellido_paterno_${i}" name="apellido_paterno_${i}" required><br><br>

                <label for="apellido_materno_${i}">Apellido Materno:</label>
                <input type="text" id="apellido_materno_${i}" name="apellido_materno_${i}" required><br><br>

                <label for="numero_boleta_${i}">Número de Boleta:</label>
                <input type="text" id="numero_boleta_${i}" name="numero_boleta_${i}" required><br><br>`;
            }

            var numDirectores = document.getElementById("num_directores").value;
            var directoresContenedor = document.getElementById("directores");
            directoresContenedor.innerHTML = "";

            if (numDirectores > 2) {
                alert("El número máximo de directores es 2.");
                document.getElementById("num_directores").value = 2;
                numDirectores = 2;
            }

            for (var i = 0; i < numDirectores; i++) {
                directoresContenedor.innerHTML += `<h3>Director ${i + 1}</h3>
                <label for="director_nombre_${i}">Nombre:</label>
                <input type="text" id="director_nombre_${i}" name="director_nombre_${i}" required><br><br>

                <label for="director_apellido_${i}">Apellido:</label>
                <input type="text" id="director_apellido_${i}" name="director_apellido_${i}" required><br><br>`;
            }
        }

        function mostrarPDF(input) {
            var file = input.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById("pdf_preview");
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>Registro de Protocolo de Titulación</h1>
    </div>

    <div class="main-container">
        <div class="pdf-container">
            <iframe id="pdf_preview" style="width:100%;height:100%;" frameborder="0"></iframe>
        </div>

        <div class="form-container">
            <h2>Por favor, complete el formulario:</h2>
            <form id="registroForm" method="post" enctype="multipart/form-data">
                <label for="num_estudiantes">Número de Estudiantes:</label>
                <input type="number" id="num_estudiantes" name="num_estudiantes" min="1" max="4" required oninput="generarCampos()"><br><br>

                <div id="estudiantes"></div>

                <h3>Datos del Protocolo</h3> <!-- Nuevo subtítulo -->
                
                <label for="num_directores">Número de Directores:</label>
                <input type="number" id="num_directores" name="num_directores" min="1" max="2" required oninput="generarCampos()"><br><br>

                <div id="directores"></div>

                <label for="titulo">Título del Protocolo:</label>
                <input type="text" id="titulo" name="titulo" required><br><br>

                <label for="descripcion">Descripción Breve:</label><br>
                <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea><br><br>

                <label for="resumen">Resumen:</label><br>
                <textarea id="resumen" name="resumen" rows="4" cols="50" required></textarea><br><br>

                <label for="palabras_clave">Palabras Clave:</label>
                <input type="text" id="palabras_clave" name="palabras_clave" required><br><br>

                <label for="documento_pdf">Subir Documento PDF:</label>
                <input type="file" id="documento_pdf" name="documento_pdf" accept=".pdf" onchange="mostrarPDF(this)" required><br><br>

                <input type="submit" value="Registrar Protocolo">
            </form>
        </div>
    </div>
</body>
</html>
