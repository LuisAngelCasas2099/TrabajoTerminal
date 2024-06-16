<?php
$zip = new ZipArchive();
$zipFileName = 'protocolos.zip';

if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    exit("No se pudo abrir el archivo ZIP.");
}

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator('upload/'),
    RecursiveIteratorIterator::LEAF_CHILDREN
);

foreach ($files as $file) {
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen('upload/') + 1);

        $zip->addFile($filePath, $relativePath);
    }
}

$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zipFileName);
header('Content-Length: ' . filesize($zipFileName));

readfile($zipFileName);

unlink($zipFileName);
?>
