<?php
session_start();

// Verificar si el usuario ha iniciado sesión y tiene los permisos adecuados
if (!isset($_SESSION['id']) || ($_SESSION['id'] != 3 && $_SESSION['id'] != 4)) {
    header("Location: login.php");
    exit();
}

// Manejar el envío del formulario
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Asegurar que todas las variables POST estén definidas
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $apellido = isset($_POST['apellido']) ? $_POST['apellido'] : '';
    $correo = isset($_POST['correo']) ? $_POST['correo'] : '';
    $contrasena = isset($_POST['contrasena']) ? $_POST['contrasena'] : '';
    $tipo_usuario = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : '';
    $boleta = isset($_POST['boleta']) ? $_POST['boleta'] : '';
    $telefono = isset($_POST['telefono']) ? $_POST['telefono'] : '';

    // Asignar el valor de "id" según el tipo de usuario
    switch ($tipo_usuario) {
        case "Alumno":
            $id = 1;
            break;
        case "Docente":
            $id = 0;
            break;
        case "Personal CATT":
            $id = 3;
            break;
        default:
            $id = null; // Valor por defecto en caso de error
            break;
    }

    // Validar los campos del formulario
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($contrasena) || $id === null || ($id !== 3 && empty($boleta)) || ($id === 1 && empty($telefono))) {
        $message = "Todos los campos son obligatorios.";
    } else {
        // Establecer la conexión a la base de datos
        $servername = "localhost";
        $username = "Admin";
        $password = "gamer4life";
        $dbname = "basededatos";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Error de conexión a la base de datos: " . $conn->connect_error);
        }

        // Insertar el nuevo usuario en la base de datos
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, correo, contrasena, id, boleta, telefono) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiss", $nombre, $apellido, $correo, $contrasena, $id, $boleta, $telefono);

        if ($stmt->execute()) {
            $message = "Usuario insertado exitosamente.";
        } else {
            $message = "Error al insertar el usuario: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Insertar Usuario</title>
    <link rel="stylesheet" type="text/css" href="styles4.css">
    <script>
        function mostrarCampoAdicional() {
            var tipoUsuario = document.getElementById('tipo_usuario').value;
            var campoBoleta = document.getElementById('campo-boleta');
            var campoTelefono = document.getElementById('campo-telefono');
            var labelBoleta = document.getElementById('label-boleta');
            if (tipoUsuario === 'Alumno') {
                labelBoleta.innerText = 'Boleta:';
                campoBoleta.style.display = 'block';
                campoTelefono.style.display = 'block';
            } else if (tipoUsuario === 'Docente') {
                labelBoleta.innerText = 'Número de empleado:';
                campoBoleta.style.display = 'block';
                campoTelefono.style.display = 'none';
            } else {
                campoBoleta.style.display = 'none';
                campoTelefono.style.display = 'none';
            }
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>Insertar Usuario</h1>
        <a href="perfil.php" class="regresar">Regresar</a>
    </div>

    <div class="container">
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        
        <form method="post" action="insertarUsuario.php">
            <label for="tipo_usuario">Tipo de Usuario:</label>
            <select id="tipo_usuario" name="tipo_usuario" required onchange="mostrarCampoAdicional()">
                <option value="">Seleccionar uno</option>
                <option value="Alumno">Alumno</option>
                <option value="Docente">Docente</option>
                <option value="Personal CATT">Personal CATT</option>
            </select><br><br>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required><br><br>

            <label for="apellido">Apellidos:</label>
            <input type="text" id="apellido" name="apellido" required><br><br>

            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required><br><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required><br><br>

            <div id="campo-boleta" style="display:none;">
                <label id="label-boleta" for="boleta"></label>
                <input type="text" id="boleta" name="boleta" maxlength="10"><br><br>
            </div>

            <div id="campo-telefono" style="display:none;">
                <label id="label-telefono" for="telefono">Teléfono:</label>
                <input type="text" id="telefono" name="telefono" maxlength="10"><br><br>
            </div>

            <input type="submit" value="Insertar Usuario">
        </form>
    </div>
</body>
</html>
