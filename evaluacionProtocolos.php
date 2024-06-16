<?php
require('fpdf/fpdf.php');
session_start();
require('conexion.php'); // Asegúrate de incluir el archivo de conexión a la base de datos

$correo_usuario = $_SESSION['correo'];

$registro = "";
$titulo = "";
$fecha = "";
$respuestas = [];
$observaciones = [];
$aprobado = "";
$recomendaciones = "";
$pdf_path = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registro = $_POST['registro'];
    $titulo = $_POST['titulo'];
    $fecha = $_POST['fecha'];

    // Obtener respuestas
    for ($i = 1; $i <= 10; $i++) {
        $pregunta = "pregunta$i";
        $respuestas[$i] = $_POST[$pregunta];
        $observacion = "observaciones$i";
        $observaciones[$i] = $_POST[$observacion];
    }

    $aprobado = $_POST['aprobado'];
    $recomendaciones = $_POST['recomendaciones'];

    $pdf = new FPDF();
    $pdf->AddPage();

 
    $pdf->Image('encabezado.png', 10, 10, 190);
    $pdf->Ln(20); // Añadir espacio después de la imagen

    // Encabezado
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->MultiCell(0, 10, utf8_decode('Evaluación para Propuestas de Trabajo Terminal'), 0, 'L');
    $pdf->Ln(10);

    // Información del formulario
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0); // Texto negro
    $pdf->MultiCell(0, 10, utf8_decode('Núm. de Registro del TT: ' . $registro), 0, 'L');
    $pdf->MultiCell(0, 10, utf8_decode('Título del TT: ' . $titulo), 0, 'L');
    $pdf->MultiCell(0, 10, utf8_decode('Fecha de evaluación: ' . $fecha), 0, 'L');
    $pdf->Ln(10);

    // Respuestas y observaciones
    $preguntas = [
        "¿El título corresponde al producto esperado?",
        "¿El resumen expresa claramente la propuesta del TT, su importancia y aplicación?",
        "¿Las palabras clave han sido clasificadas adecuadamente?",
        "¿La presentación del problema a resolver es comprensible?",
        "¿El objetivo es preciso y relevante?",
        "¿El planteamiento del problema y la tentativa solución descrita son claros?",
        "¿Sus contribuciones o beneficios están completamente justificados? Originalidad, vinculación con población usuaria potencial, utilidad de los resultados, complejidad en su elaboración a nivel ingeniería, mejoramiento de lo existente.",
        "¿Su viabilidad es adecuada? Tiempos, recursos humanos y materiales, alcances, costos y otros puntos que puedan impedir la culminación exitosa del trabajo",
        "¿La propuesta metodológica es pertinente?",
        "¿El calendario de actividades por estudiantes es adecuado?"
    ];

    $pdf->SetFont('Arial', '', 12);
    for ($i = 1; $i <= 10; $i++) {
        $pdf->SetTextColor(0, 0, 0); // Texto negro
        $pdf->MultiCell(0, 10, utf8_decode($i . '. ' . $preguntas[$i - 1] . ' ' . $respuestas[$i]), 0, 'L');
        $pdf->SetTextColor(0, 0, 0); // Texto negro
        $pdf->MultiCell(0, 10, utf8_decode('Observaciones: ' . $observaciones[$i]), 0, 'L');
        $pdf->Ln(0.5); // Reducir espacio entre líneas
    }

    // Aprobado y recomendaciones
    $pdf->SetTextColor(0, 0, 0); // Texto negro
    $pdf->MultiCell(0, 10, utf8_decode('APROBADO: ' . $aprobado), 0, 'L');
    $pdf->MultiCell(0, 10, utf8_decode('Recomendaciones adicionales: ' . $recomendaciones), 0, 'L');

    // Guardar PDF temporalmente
    $pdf_path = 'acuses/evaluacion_' . uniqid() . '.pdf';
    $pdf->Output('F', $pdf_path);

    // Leer contenido del PDF
    $pdf_content = file_get_contents($pdf_path);

    // Determinar la columna sinodal libre
    $query = "SELECT sinodal1, sinodal2, sinodal3 FROM acuses WHERE numeroTT = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $registro);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    $sinodal_col = null;
    $acuse_col = null;
    $nombre_col = null;
    $aprobado_col = null;
    if (empty($row['sinodal1'])) {
        $sinodal_col = 'sinodal1';
        $acuse_col = 'acuses1';
        $nombre_col = 'nombrea1';
        $aprobado_col = 'aprobado1';
    } elseif (empty($row['sinodal2'])) {
        $sinodal_col = 'sinodal2';
        $acuse_col = 'acuses2';
        $nombre_col = 'nombrea2';
        $aprobado_col = 'aprobado2';
    } elseif (empty($row['sinodal3'])) {
        $sinodal_col = 'sinodal3';
        $acuse_col = 'acuses3';
        $nombre_col = 'nombrea3';
        $aprobado_col = 'aprobado3';
    }

    if ($sinodal_col && $acuse_col && $nombre_col && $aprobado_col) {
        $stmt = $conn->prepare("UPDATE acuses SET $sinodal_col = ?, $acuse_col = ?, $nombre_col = ?, $aprobado_col = ? WHERE numeroTT = ?");
        $null = NULL;
        $stmt->bind_param("sbssi", $correo_usuario, $null, $pdf_path, $aprobado, $registro);
        $stmt->send_long_data(1, $pdf_content);

        if ($stmt->execute()) {
            $message = "PDF guardado exitosamente.";
        } else {
            $message = "Error al guardar el PDF: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $message = "No hay espacio libre para agregar el sinodal.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Evaluación para Propuestas de Trabajo Terminal</title>
    <link rel="stylesheet" type="text/css" href="styles4.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="header">
        <h1>Evaluación para Propuestas de Trabajo Terminal</h1>
        <a href="lista_academia.php" class="regresar">Regresar</a>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Por favor, complete la evaluación:</h2>
            <form id="evaluacionForm" method="post">
                <label for="registro">Núm. de Registro del TT:</label>
                <input type="text" id="registro" name="registro" value="<?= htmlspecialchars($registro) ?>" required><br><br>

                <label for="titulo">Título del TT:</label>
                <textarea id="titulo" name="titulo" rows="4" required><?= htmlspecialchars($titulo) ?></textarea><br><br>

                <label for="fecha">Fecha de evaluación:</label>
                <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($fecha) ?>" required><br><br>

                <?php 
                $preguntas = [
                    "¿El título corresponde al producto esperado?",
                    "¿El resumen expresa claramente la propuesta del TT, su importancia y aplicación?",
                    "¿Las palabras clave han sido clasificadas adecuadamente?",
                    "¿La presentación del problema a resolver es comprensible?",
                    "¿El objetivo es preciso y relevante?",
                    "¿El planteamiento del problema y la tentativa solución descrita son claros?",
                    "¿Sus contribuciones o beneficios están completamente justificados? Originalidad, vinculación con población usuaria potencial, utilidad de los resultados, complejidad en su elaboración a nivel ingeniería, mejoramiento de lo existente.",
                    "¿Su viabilidad es adecuada? Tiempos, recursos humanos y materiales, alcances, costos y otros puntos que puedan impedir la culminación exitosa del trabajo",
                    "¿La propuesta metodológica es pertinente?",
                    "¿El calendario de actividades por estudiantes es adecuado?"
                ];
                for ($i = 1; $i <= 10; $i++): ?>
                    <label for="pregunta<?= $i ?>"><?= $preguntas[$i - 1] ?></label><br>
                    <input type="radio" id="pregunta<?= $i ?>_si" name="pregunta<?= $i ?>" value="SI" required <?= isset($respuestas[$i]) && $respuestas[$i] == 'SI' ? 'checked' : '' ?>>
                    <label for="pregunta<?= $i ?>_si">SI</label>
                    <input type="radio" id="pregunta<?= $i ?>_no" name="pregunta<?= $i ?>" value="NO" required <?= isset($respuestas[$i]) && $respuestas[$i] == 'NO' ? 'checked' : '' ?>>
                    <label for="pregunta<?= $i ?>_no">NO</label><br>
                    <label for="observaciones<?= $i ?>">Observaciones:</label><br>
                    <textarea id="observaciones<?= $i ?>" name="observaciones<?= $i ?>" rows="2"><?= htmlspecialchars($observaciones[$i] ?? '') ?></textarea><br><br>
                <?php endfor; ?>

                <label for="aprobado">APROBADO:</label>
                <input type="radio" id="aprobado_si" name="aprobado" value="SI" required <?= $aprobado == 'SI' ? 'checked' : '' ?>>
                <label for="aprobado_si">SI</label>
                <input type="radio" id="aprobado_no" name="aprobado" value="NO" required <?= $aprobado == 'NO' ? 'checked' : '' ?>>
                <label for="aprobado_no">NO</label><br><br>

                <label for="recomendaciones">Recomendaciones adicionales:</label><br>
                <textarea id="recomendaciones" name="recomendaciones" rows="4"><?= htmlspecialchars($recomendaciones) ?></textarea><br><br>

                <input type="submit" value="Guardar">
                <?php if ($pdf_path): ?>
                    <a href="<?= $pdf_path ?>" download="evaluacion.pdf" class="btn">Descargar PDF</a>
                <?php endif; ?>
                <?php if ($message): ?>
        <div class="message">
        <p>Protocolo guardado correctamente.</p>
        </div>
    <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
>








