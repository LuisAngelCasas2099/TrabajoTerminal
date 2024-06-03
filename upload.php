<?php
    include_once 'conexion.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

        $sql = "INSERT INTO protocolos (titulo, descripcion, resumen, palabras_clave, archivo_pdf) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $titulo, $descripcion, $resumen, $palabras_clave, $target_file);
        $stmt->execute();

        $protocolo_id = $stmt->insert_id;

        foreach ($estudiantes as $index => $estudiante) {
            $sql = "INSERT INTO estudiantes (nombre, apellido, correo, protocolo_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $estudiante['nombre'], $estudiante['apellido'], $estudiante['correo'], $protocolo_id);
            $stmt->execute();
        }

        foreach ($directores as $index => $director) {
            $sql = "INSERT INTO directores (nombre, apellido, protocolo_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $director['nombre'], $director['apellido'], $protocolo_id);
            $stmt->execute();
        }

        echo "<h2>Los datos recibidos son:</h2>";

        foreach ($estudiantes as $index => $estudiante) {
            echo "<h3>Estudiante " . ($index + 1) . "</h3>";
            echo "<p>Nombre: " . $estudiante['nombre'] . "</p>";
            echo "<p>Apellido: " . $estudiante['apellido'] . "</p>";
            echo "<p>Correo: " . $estudiante['correo'] . "</p>";
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
