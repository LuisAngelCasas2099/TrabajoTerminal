<?php
// Obtener la ruta del archivo a descomprimir
$rutaArchivo = $_GET['archivo'];

// Descomprimir el archivo
$zip = new ZipArchive;
if ($zip->open($rutaArchivo) === TRUE) {
    $zip->extractTo('ruta_de_destino');
    $zip->close();
    echo 'El archivo se descomprimió correctamente.';
} else {
    echo 'Error al descomprimir el archivo.';
}

// Redirigir a la página "asignacion_academia.php"
header("Location: asignacion_academia.php");
?>
