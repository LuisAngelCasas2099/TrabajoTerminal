<!DOCTYPE html>
<html>
<head>
    <title>Registrar Protocolo de Titulación</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <script>
        function generarCampos() {
            var numEstudiantes = document.getElementById("num_estudiantes").value;
            var contenedor = document.getElementById("estudiantes");

            contenedor.innerHTML = ""; // Limpiar los campos previos

            if (numEstudiantes > 4) {
                alert("El número máximo de estudiantes es 4.");
                document.getElementById("num_estudiantes").value = 4;
                numEstudiantes = 4;
            }

            for (var i = 0; i < numEstudiantes; i++) {
                contenedor.innerHTML += `<h3>Estudiante ${i + 1}</h3>
                <label for="nombre_${i}">Nombre:</label>
                <input type="text" id="nombre_${i}" name="nombre_${i}" required><br><br>

                <label for="apellido_paterno_${i}">Apellidos:</label>
                <input type="text" id="apellidos${i}" name="apellidos${i}" required><br><br>

                <label for="correo_${i}">Correo:</label>
                <input type="text" id="correo${i}" name="correo${i}" required><br><br>`;
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
        <div class="dropdown">
            <button class="dropbtn">Menú</button>
            <div class="dropdown-content">
                <a href="perfil.php">Perfil</a>
                <a href="login.php">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <div class="container">
        <h2>Por favor, complete el formulario:</h2>
        <form id="registroForm" action="upload.php" method="post" enctype="multipart/form-data">
            <label for="num_estudiantes">Número de Estudiantes:</label>
            <input type="number" id="num_estudiantes" name="num_estudiantes" min="1" max="4" required oninput="generarCampos()"><br><br>

            <div id="estudiantes"></div>

            <h3>Datos del Protocolo</h3>
            <label for="titulo">Título del Protocolo:</label>
            <input type="text" id="titulo" name="titulo" required><br><br>

            <label for="correo_director_principal">Correo Director principal:</label>
<input type="text" id="correo_director_principal" name="correo_director_principal" required><br><br>

<label for="correo_director_secundario">Correo Director secundario:</label>
<input type="text" id="correo_director_secundario" name="correo_director_secundario"><br><br>


            <label for="palabras_clave">Palabras Clave:</label>
            <input type="text" id="palabras_clave" name="palabras_clave" required><br><br>

            <label for="resumen">Resumen:</label><br>
            <textarea id="resumen" name="resumen" rows="4" cols="50" required></textarea><br><br>

            <label for="documento_pdf">Subir Documento PDF:</label>
            <input type="file" id="documento_pdf" name="documento_pdf" accept=".pdf" onchange="mostrarPDF(this)" required><br><br>
            
            <iframe id="pdf_preview" style="width:100%;height:500px;" frameborder="0"></iframe>

            <input type="submit" value="Registrar Protocolo">
        </form>
    </div>
</body>
</html>
