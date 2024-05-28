<?php
require 'vendor/autoload.php';
require 'fpdf/fpdf.php';

// Datos de conexión a la base de datos
$servername = "localhost";
$username = "Admin";
$password = "gamer4life";
$dbname = "basededatos";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $palabras_clave = $_POST['palabras_clave'];
    $resumen = $_POST['resumen'];
    $correos = [
        $_POST['correo_director_principal'] ?? null,
        $_POST['correo_director_secundario'] ?? null
    ];

    // Agregar correos de los estudiantes
    for ($i = 0; $i < 4; $i++) {
        if (isset($_POST["correo$i"])) {
            $correos[] = $_POST["correo$i"];
        }
    }

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Crear el directorio si no existe
    }

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
            $uploadOk = 0;
        }
    }

    if ($uploadOk == 1) {
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Leer el archivo PDF
            $pdfData = file_get_contents($target_file);

            // Insertar el registro en la tabla protocolos
            $stmt = $conn->prepare("INSERT INTO protocolos (titulo, palabrasclave, resumen, archivo) VALUES (:titulo, :palabras_clave, :resumen, :archivo)");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':palabras_clave', $palabras_clave);
            $stmt->bindParam(':resumen', $resumen);
            $stmt->bindParam(':archivo', $pdfData, PDO::PARAM_LOB);
            $stmt->execute();

            // Obtener el último idP insertado
            $idP = $conn->lastInsertId();

            // Actualizar la tabla usuarios con el nuevo idP para los correos ingresados
            foreach ($correos as $correo) {
                if ($correo) {
                    $stmt = $conn->prepare("UPDATE usuarios SET idP = :idP WHERE correo = :correo");
                    $stmt->bindParam(':idP', $idP, PDO::PARAM_INT);
                    $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }

            echo "Se ha asignado correctamente el idP a las filas de usuarios.";

            // Redirigir a la página perfil.php
            header("Location: perfil.php");
            exit();
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }
}
?>
