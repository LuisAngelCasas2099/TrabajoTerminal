<?php
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];
$protocoloRegistrado = false; // Flag para verificar si el protocolo fue registrado

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establecer la conexión a la base de datos
    $servername = "localhost";
    $username = "Admin";
    $password = "gamer4life";
    $dbname = "basededatos";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("La conexión a la base de datos falló: " . $conn->connect_error);
    }

    $num_estudiantes = $_POST['num_estudiantes'];
    $num_directores = $_POST['num_directores'];

    $estudiantes = [];
    for ($i = 0; $i < $num_estudiantes; $i++) {
        $estudiantes[] = [
            'nombre' => $_POST["nombre_$i"],
            'apellido' => $_POST["apellido$i"],
            'correo' => $_POST["correo$i"]
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
    $palabrasclave = $_POST['palabrasclave'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["documento_pdf"]["name"]);
    $uploadOk = 1;
    $pdfFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($pdfFileType != "pdf") {
        echo "Solo se permiten archivos PDF.";
        $uploadOk = 0;
    }

    if ($_FILES["documento_pdf"]["size"] > 5000000) {
        echo "El archivo es demasiado grande. Solo se permiten archivos de hasta 5MB.";
        $uploadOk = 0;
    }

    // Obtener el idP del protocolo registrado
    $idP = null;
    if ($uploadOk == 1) {
        // Insertar el protocolo en la base de datos
        $stmt = $conn->prepare("INSERT INTO protocolos (titulo, palabrasclave, resumen, archivo, nombre_archivo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $titulo, $palabrasclave, $resumen, $target_file, $_FILES["documento_pdf"]["name"]);

        if ($stmt->execute()) {
            $idP = $stmt->insert_id;
            $protocoloRegistrado = true; // Señalización de que el protocolo fue registrado
        } else {
            echo "Hubo un error al insertar el protocolo en la base de datos.";
        }
        $stmt->close();
    }

    // Asignar el idP a los estudiantes
    if ($idP != null) {
        foreach ($estudiantes as $index => $estudiante) {
            $correo = $estudiante['correo'];
            $stmt = $conn->prepare("UPDATE usuarios SET idP = ? WHERE correo = ?");
            $stmt->bind_param("is", $idP, $correo);
            if (!$stmt->execute()) {
                echo "Hubo un error al asignar el idP al estudiante con correo $correo.";
            }
            $stmt->close();
        }
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["documento_pdf"]["tmp_name"], $target_file)) {
            // No redirigir aquí, manejaremos la redirección con JavaScript
        } else {
            echo "Hubo un error al subir el archivo.";
        }
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Registrar Protocolo de Titulación</title>
    <link rel="stylesheet" type="text/css" href="styles3.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
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

                <label for="apellido${i}">Apellidos:</label>
                <input type="text" id="apellido${i}" name="apellido${i}" required><br><br>

                <label for="correo${i}">Correo:</label>
                <input type="text" id="correo${i}" name="correo${i}" required><br><br>`;
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
            reader.onload = function (e) {
                var preview = document.getElementById("pdf_preview");
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
        
        function mostrarMensaje() {
            alert("Protocolo registrado correctamente");
            window.location.href = "perfil.php";
        }
    </script>
</head>

<body>
    <div class="header">
        <h1>Registro de Protocolo de Titulación</h1>
        <div class="dropdown">
            <button class="dropbtn">Menú</button>
            <div class="dropdown-content">
                <?php if ($id == 0): ?>
                    <a href="lista_protocolos.php">Lista Protocolos</a>
                <?php endif; ?>
                <?php if ($id == 2 || $id == 3): ?>
                    <a href="alta_profesores.php">Alta de profesores</a>
                <?php endif; ?>
                <?php if ($id == 2 || $id == 3): ?>
                    <a href="lista_protocolos.php">Lista Protocolos</a>
                <?php endif; ?>
                <a href="perfil.php">Perfil</a>
                <?php if ($id == 1): ?>
                    <a href="registroProtocolo.php">Registro de Protocolo</a>
                <?php endif; ?>
                <?php if ($id == 2 || $id == 3): ?>
                    <a href="evaluacionProtocolos.php">Evaluacion Protocolos </a>
                <?php endif; ?>
                <a href="login.php">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="pdf-container">
            <iframe id="pdf_preview" style="width:100%;height:100%;" frameborder="0"></iframe>
        </div>

        <div class="form-container">
            <h2>Por favor, complete el formulario:</h2>
            <form id="registroForm" method="post" enctype="multipart/form-data">
                <label for="num_estudiantes">Número de Estudiantes:</label>
                <input type="number" id="num_estudiantes" name="num_estudiantes" min="1" max="4" required
                    oninput="generarCampos()"><br><br>

                <div id="estudiantes"></div>

                <h3>Datos del Protocolo</h3> <!-- Nuevo subtítulo -->

                <label for="num_directores">Número de Directores:</label>
                <input type="number" id="num_directores" name="num_directores" min="1" max="2" required
                    oninput="generarCampos()"><br><br>

                <div id="directores"></div>

                <label for="titulo">Título del Protocolo:</label>
                <input type="text" id="titulo" name="titulo" required><br><br>

                <label for="descripcion">Descripción Breve:</label><br>
                <textarea id="descripcion" name="descripcion" rows="4" cols="50" required></textarea><br><br>

                <label for="resumen">Resumen:</label><br>
                <textarea id="resumen" name="resumen" rows="4" cols="50" required></textarea><br><br>

                <label for="palabrasclave">Palabras Clave:</label>
                <input type="text" id="palabrasclave" name="palabrasclave" required><br><br>

                <label for="documento_pdf">Subir Documento PDF:</label>
                <input type="file" id="documento_pdf" name="documento_pdf" accept=".pdf" onchange="mostrarPDF(this)"
                    required><br><br>

                <input type="submit" value="Registrar Protocolo">
            </form>
        </div>
    </div>

    <?php if ($protocoloRegistrado): ?>
        <script>
            mostrarMensaje();
        </script>
    <?php endif; ?>
</body>

</html>
