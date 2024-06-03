<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "Admin";
$password = "gamer4life";
$dbname = "basededatos";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Obtener el archivo BLOB de la base de datos
        $stmt = $conn->prepare("SELECT archivo FROM protocolos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar si se encontró el archivo
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $pdfData = $row['archivo'];

            // Enviar encabezados HTTP para el archivo PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="documento.pdf"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');

            // Imprimir el contenido del archivo PDF
            echo $pdfData;
        } else {
            echo "No se encontró el archivo.";
        }
    } else {
        echo "ID de archivo no especificado.";
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>