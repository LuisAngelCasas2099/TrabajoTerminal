<?php
require('fpdf/fpdf.php');

$registro = "";
$titulo = "";
$fecha = "";
$respuestas = [];
$observaciones = [];
$aprobado = "";
$recomendaciones = "";

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

    // Crear PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Encabezado
    $pdf->SetFont('Arial','B',14);
    $pdf->Cell(0,10,'Resultados de la Evaluacion',0,1,'C');
    $pdf->Ln(10);

    // Información del formulario
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,'Núm. de Registro del TT: '.$registro,0,1);
    $pdf->Cell(0,10,'Título del TT: '.$titulo,0,1);
    $pdf->Cell(0,10,'Fecha de evaluación: '.$fecha,0,1);
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

    for ($i = 1; $i <= 10; $i++) {
        $pdf->Cell(0,10,$i.'. '.$preguntas[$i-1].': '.$respuestas[$i],0,1);
        $pdf->Cell(0,10,'Observaciones: '.$observaciones[$i],0,1);
        $pdf->Ln(5);
    }

    // Aprobado y recomendaciones
    $pdf->Cell(0,10,'APROBADO: '.$aprobado,0,1);
    $pdf->Cell(0,10,'Recomendaciones adicionales: '.$recomendaciones,0,1);

    // Guardar PDF
    $pdf->Output('F', 'evaluacion.pdf');
    echo 'PDF generado exitosamente. <a href="evaluacion.pdf" download>Descargar PDF</a>';
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Evaluación para Propuestas de Trabajo Terminal</title>
    <link rel="stylesheet" type="text/css" href="styles4.css">
</head>
<body>
    <div class="header">
        <h1>Evaluación para Propuestas de Trabajo Terminal</h1>
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
                    <label for="pregunta<?= $i ?>"> <?= $i ?>. <?= $preguntas[$i-1] ?></label>
                    <select id="pregunta<?= $i ?>" name="pregunta<?= $i ?>">
                        <option value="SI" <?= (isset($respuestas[$i]) && $respuestas[$i] == "SI") ? "selected" : "" ?>>SI</option>
                        <option value="NO" <?= (isset($respuestas[$i]) && $respuestas[$i] == "NO") ? "selected" : "" ?>>NO</option>
                    </select><br>
                    <label for="observaciones<?= $i ?>">Observaciones:</label><br>
                    <textarea id="observaciones<?= $i ?>" name="observaciones<?= $i ?>" rows="4"><?= isset($observaciones[$i]) ? htmlspecialchars($observaciones[$i]) : '' ?></textarea>
                    <br><br>
                <?php endfor; ?>

                <label for="aprobado">APROBADO:</label>
                <select id="aprobado" name="aprobado">
                    <option value="SI" <?= ($aprobado == "SI") ? "selected" : "" ?>>SI</option>
                    <option value="NO" <?= ($aprobado == "NO") ? "selected" : "" ?>>NO</option>
                </select><br><br>

                <label for="recomendaciones">Recomendaciones adicionales:</label><br>
                <textarea id="recomendaciones" name="recomendaciones" rows="4"><?= htmlspecialchars($recomendaciones) ?></textarea><br><br>

                <input type="submit" value="Enviar Evaluación">
            </form>
            <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <h2>Resultados de la Evaluación:</h2>
                <p>Núm. de Registro del TT: <?= htmlspecialchars($registro) ?></p>
                <p>Título del TT: <?= htmlspecialchars($titulo) ?></p>
                <p>Fecha de evaluación: <?= htmlspecialchars($fecha) ?></p>
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <p><?= $i ?>. <?= $preguntas[$i-1] ?>: <?= htmlspecialchars($respuestas[$i]) ?></p>
                    <p>Observaciones: <?= htmlspecialchars($observaciones[$i]) ?></p>
                <?php endfor; ?>
                <p>APROBADO: <?= htmlspecialchars($aprobado) ?></p>
                <p>Recomendaciones adicionales: <?= htmlspecialchars($recomendaciones) ?></p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>