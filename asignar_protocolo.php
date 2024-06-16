<?php
require_once 'conexion.php';

if (!isset($_GET['archivo'])) {
    echo "No se encontró el protocolo";
    exit();
}

$archivo = $_GET['archivo'];

$stmt = $conn->prepare("SELECT * FROM protocolos WHERE nombre_archivo = ?");
$stmt->bind_param("s", $archivo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
} else {
    echo "No se encontró el protocolo";
    exit();
}

$opciones = array(
    "Ciencias sociales" => 1,
    "Ciencias básicas" => 2,
    "Ingeniería de software" => 3,
    "Ciencias de la computación" => 4,
    "Sistemas distribuidos" => 5,
    "Sistemas digitales" => 6,
    "Fundamentos de sistemas electrónicos" => 7,
    "Inteligencia artificial" => 8,
    "Ciencia de datos" => 9,
    "Proyectos estratégicos y toma de decisiones" => 10
);

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ac1 = $_POST['ac1'];
    $ac2 = $_POST['ac2'];
    $ac3 = $_POST['ac3'];
    $numeroTT = $_POST['numeroTT'];

    // Mapear las opciones seleccionadas a sus valores numéricos
    $ac1_val = $opciones[$ac1];
    $ac2_val = $opciones[$ac2];
    $ac3_val = $opciones[$ac3];

    // Verificar que el número de Trabajo Terminal existe en la tabla `acuses`
    $stmt = $conn->prepare("SELECT numeroTT FROM acuses WHERE numeroTT = ?");
    $stmt->bind_param("s", $numeroTT);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // El número de TT ya existe en `acuses`, realizar la actualización en `protocolos`
        $stmt = $conn->prepare("UPDATE protocolos SET Ac1 = ?, Ac2 = ?, Ac3 = ?, numeroTT = ? WHERE nombre_archivo = ?");
        $stmt->bind_param("sssss", $ac1_val, $ac2_val, $ac3_val, $numeroTT, $archivo);

        if ($stmt->execute()) {
            header("Location: lista_asignacion.php");
            exit();
        } else {
            $mensaje = "Error al asignar protocolo: " . $stmt->error;
        }
    } else {
        // El número de TT no existe en `acuses`, insertarlo y actualizar `protocolos`
        $conn->begin_transaction();
        try {
            $stmt = $conn->prepare("INSERT INTO acuses (numeroTT) VALUES (?)");
            $stmt->bind_param("s", $numeroTT);

            if ($stmt->execute()) {
                $stmt = $conn->prepare("UPDATE protocolos SET Ac1 = ?, Ac2 = ?, Ac3 = ?, numeroTT = ? WHERE nombre_archivo = ?");
                $stmt->bind_param("sssss", $ac1_val, $ac2_val, $ac3_val, $numeroTT, $archivo);

                if ($stmt->execute()) {
                    $conn->commit();
                    header("Location: lista_asignacion.php");
                    exit();
                } else {
                    $conn->rollback();
                    $mensaje = "Error al asignar protocolo: " . $stmt->error;
                }
            } else {
                $conn->rollback();
                $mensaje = "Error al insertar en acuses: " . $stmt->error;
            }
        } catch (Exception $e) {
            $conn->rollback();
            $mensaje = "Transaction failed: " . $e->getMessage();
        }
    }
    $stmt->close();
} else {
    $ac1 = array_search($fila['Ac1'], $opciones);
    $ac2 = array_search($fila['Ac2'], $opciones);
    $ac3 = array_search($fila['Ac3'], $opciones);
    $numeroTT = $fila['numeroTT'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Asignar Protocolo</title>
    <link rel="stylesheet" type="text/css" href="styles3.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9ebee;
            color: #1c1e21;
            margin: 0;
            padding: 0;
            position: relative;
            height: 100vh;
            overflow: hidden;
        }
        .header {
            background-color: #4267b2;
            padding: 10px;
            text-align: center;
            color: white;
            position: relative;
        }

        .logo-left {
            position: absolute;
            top: 10px;
            left: 10px;
            height: 40px;
        }

        .logo-right {
            position: absolute;
            top: 10px;
            right: 10px;
            height: 40px;
        }

        h1 {
            color: white;
            font-size: 24px;
            margin: 0;
        }

        .main-container {
            display: flex;
            height: calc(100vh - 100px);
            margin: 20px;
            overflow: hidden;
        }

        .pdf-container {
            flex: 2;
            margin: 10px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .pdf-container embed {
            width: 100%;
            height: 100%;
        }

        .form-container {
            flex: 1;
            margin: 10px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
            overflow-y: auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select, input[type="text"] {
            width: 100%;
            padding: 5px;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .mensaje {
            margin-top: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Asignar Protocolo</h1>
    </div>
    <div class="main-container">
        <div class="form-container">
            <form id="asignarForm" method="post">
                <label for="ac1">Academia 1:</label>
                <select id="ac1" name="ac1">
                    <?php foreach ($opciones as $opcion => $valor) : ?>
                        <option value="<?php echo htmlspecialchars($opcion); ?>" <?php echo ($ac1 === $opcion) ? 'selected' : ''; ?>><?php echo htmlspecialchars($opcion); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="ac2">Academia 2:</label>
                <select id="ac2" name="ac2">
                    <?php foreach ($opciones as $opcion => $valor) : ?>
                        <option value="<?php echo htmlspecialchars($opcion); ?>" <?php echo ($ac2 === $opcion) ? 'selected' : ''; ?>><?php echo htmlspecialchars($opcion); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="ac3">Academia 3:</label>
                <select id="ac3" name="ac3">
                    <?php foreach ($opciones as $opcion => $valor) : ?>
                        <option value="<?php echo htmlspecialchars($opcion); ?>" <?php echo ($ac3 === $opcion) ? 'selected' : ''; ?>><?php echo htmlspecialchars($opcion); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="numeroTT">Número de Trabajo Terminal:</label>
                <input type="text" id="numeroTT" name="numeroTT" value="<?php echo htmlspecialchars($numeroTT); ?>" required>

                <input type="submit" value="Guardar">
            </form>

            <?php if (!empty($mensaje)) : ?>
                <div class="mensaje">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="pdf-container">
            <?php if (!empty($archivo)): ?>
                <embed src="uploads/<?php echo htmlspecialchars($archivo); ?>" type="application/pdf" />
            <?php else: ?>
                <p>No se ha proporcionado un archivo para visualizar.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
