<?php
session_start();

if (!isset($_SESSION['nombre'])) {
    header("Location: login.php");
    exit();
}

$nombre = $_SESSION['nombre'];
$correo = $_SESSION['correo'];
$telefono = $_SESSION['telefono'];
$boleta = $_SESSION['boleta'];
$id = $_SESSION['id']; // Obtener el valor de "id" de la sesión
$idP = isset($_SESSION['idP']) ? $_SESSION['idP'] : null; // Asegurarse de que idP esté establecido

// Establecer la conexión a la base de datos
$servername = "localhost";
$username = "Admin";
$password = "gamer4life";
$dbname = "basededatos";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

$numeroTT = null;

if ($idP) {
    // Obtener numeroTT asociado al idP del usuario
    $stmt = $conn->prepare("SELECT numeroTT FROM protocolos WHERE idP = ?");
    $stmt->bind_param("i", $idP);
    $stmt->execute();
    $numeroTT_result = $stmt->get_result();
    if ($numeroTT_result->num_rows > 0) {
        $numeroTT = $numeroTT_result->fetch_assoc()['numeroTT'];
    }
    $stmt->close();
}

$puede_subir_pdf = false;
$sinodal_con_aprobado_n = null;
$acuse = null;

if ($numeroTT) {
    // Obtener acuses asociados al numeroTT del usuario
    $stmt = $conn->prepare("SELECT * FROM acuses WHERE numeroTT = ?");
    $stmt->bind_param("i", $numeroTT);
    $stmt->execute();
    $acuse = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($acuse) {
        if ($acuse['aprobado1'] === 'N') {
            $puede_subir_pdf = true;
            $sinodal_con_aprobado_n = 1;
        } elseif ($acuse['aprobado2'] === 'N') {
            $puede_subir_pdf = true;
            $sinodal_con_aprobado_n = 2;
        } elseif ($acuse['aprobado3'] === 'N') {
            $puede_subir_pdf = true;
            $sinodal_con_aprobado_n = 3;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['guardar'])) {
    $sinodal_n = $sinodal_con_aprobado_n;
    if ($sinodal_n !== null && isset($_FILES['archivo_pdf'])) {
        $archivo_pdf = $_FILES['archivo_pdf'];
        $pdf_path = 'acuses/' . basename($archivo_pdf['name']);
        move_uploaded_file($archivo_pdf['tmp_name'], $pdf_path);
        $pdf_content = file_get_contents($pdf_path);

        $sinodal_col = "sinodal" . $sinodal_n;
        $acuse_col = "acuses" . $sinodal_n;
        $nombre_col = "nombrea" . $sinodal_n;

        $stmt = $conn->prepare("UPDATE acuses SET $acuse_col = ?, $nombre_col = ? WHERE numeroTT = ?");
        $null = NULL;
        $stmt->bind_param("bsi", $null, $pdf_path, $numeroTT);
        $stmt->send_long_data(0, $pdf_content);
        $stmt->execute();
        $stmt->close();

        // Enviar correo
        $correo_sinodal = $acuse[$sinodal_col];
        $subject = "Reenvío de Protocolo";
        $message = "El protocolo ha sido reenviado, favor de evaluarlo";
        $headers = "From: no-reply@tu-dominio.com";
        mail($correo_sinodal, $subject, $message, $headers);
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Perfil</title>
    <link rel="stylesheet" type="text/css" href="styles4.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
</head>
<body>
    <div class="header">
        <div class="mensaje-bienvenida">
            <h2>Bienvenido, <?php if ($id == 0) {
                echo "docente";
            } elseif ($id == 1) {
                echo "alumno";
            } ?>
                <?php echo $nombre; ?></h2>
        </div>
        <h1 class="titulo">Perfil</h1>
        <div class="dropdown">
            <button class="dropbtn">Menú</button>
            <div class="dropdown-content">
                <?php if ($id == 3 || $id == 4): ?>
                    <a href="lista_asignacion.php">Lista Protocolos</a>
                <?php endif; ?>
                <?php if ($id == 0): ?>
                    <a href="lista_academia.php">Lista Protocolos</a>
                <?php endif; ?>
                <?php if ($id == 3 || $id == 4): ?>
                    <a href="alta_profesores.php">Alta de profesores</a>
                <?php endif; ?>
                <?php if ($id == 3 || $id == 4): ?>
                    <a href="insertarUsuario.php">Insertar Usuario</a>
                <?php endif; ?>
                <a href="perfil.php">Perfil</a>
                <?php if ($id == 1): ?>
                    <a href="registroProtocolo.php">Registro de Protocolo</a>
                <?php endif; ?>
                <a href="login.php">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <div class="container-align-left">
        <div class="datos">
            <div class="datos-box">
                <p><strong>Correo:</strong> <?php echo $correo; ?></p>
                <p><strong>Teléfono:</strong> <span id="telefono"><?php echo $telefono; ?></span></p>
                <p><?php if ($id == 1): ?><strong>Boleta:</strong> <span id="boleta"><?php echo $boleta; ?></span><?php endif; ?></p>
                <p><?php if ($id == 0): ?><strong>Numero de empleado:</strong> <span id="boleta"><?php echo $boleta; ?></span><?php endif; ?></p>
                <button class="modificar-btn" onclick="habilitarEdicion()">Modificar Datos</button>
                <div id="contenedor-modificar" class="campo-modificar">
                    <label for="nuevo-nombre">Nuevo Nombre:</label>
                    <input type="text" id="nuevo-nombre">
                    <label for="nuevo-telefono">Nuevo Teléfono:</label>
                    <input type="text" id="nuevo-telefono">
                    <button class="modificar-btn" onclick="mostrarModalConfirmacion()">Guardar</button>
                    <button class="cancelar-btn" onclick="cancelarModificacion()">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <label for="password">Ingresa tu contraseña:</label>
            <input type="password" id="password">
            <button class="modificar-btn" onclick="confirmarCambios()">Confirmar</button>
            <button class="cancelar-btn" onclick="cerrarModal()">Regresar</button>
        </div>
    </div>

    <?php if ($id == 1) { ?>
        <div class="protocolo">
            <h3>Protocolo</h3>
            <button class="modificar-btn" onclick="visualizarInformacion()">Visualizar Información</button>
        </div>
    <?php } ?>
    </div>

    <?php if ($id == 2 || $id == 3) { ?>
        <div class="botones-admin">
            <form method="post" action="descargar_protocolos.php">
                <button type="submit">Descargar Protocolos</button>
            </form>
            <form method="post" action="eliminar_protocolos.php">
                <button type="submit">Eliminar Protocolos</button>
            </form>
        </div>
    <?php } ?>

    <script>
        function visualizarInformacion() {
            window.location.href = "perfil.php?visualizar=1";
        }

        function habilitarEdicion() {
            document.getElementById('contenedor-modificar').style.display = 'block';
        }

        function mostrarModalConfirmacion() {
            document.getElementById('modal').style.display = 'block';
        }

        function cancelarModificacion() {
            document.getElementById('contenedor-modificar').style.display = 'none';
        }

        function confirmarCambios() {
            var contrasena = document.getElementById('password').value;
            var nuevoNombre = document.getElementById('nuevo-nombre').value;
            var nuevoTelefono = document.getElementById('nuevo-telefono').value;

            // Realizar la consulta SQL para comparar la contraseña con el correo asociado
            // y actualizar los datos si la contraseña es correcta
            // Aquí debes reemplazar 'tu_tabla' con el nombre de tu tabla en la base de datos
            // y 'tu_correo' con el correo asociado al usuario que inició sesión
            var sql = "SELECT * FROM usuarios WHERE correo = correo AND contrasena = '" + contrasena + "'";

            // Ejecutar la consulta SQL
            // Aquí debes ejecutar la consulta y procesar el resultado para verificar la contraseña

            // Si la contraseña es correcta, actualizar los datos en la base de datos
            if (contraseñaCorrecta) {
                var sqlUpdate = "UPDATE usuarios SET ";
                if (nuevoNombre !== '') {
                    sqlUpdate += "nombre = '" + nuevoNombre + "'";
                }
                if (nuevoTelefono !== '') {
                    if (nuevoNombre !== '') {
                        sqlUpdate += ", ";
                    }
                    sqlUpdate += "telefono = '" + nuevoTelefono + "'";
                }
                sqlUpdate += " WHERE correo = 'correo'";

                // Ejecutar la consulta de actualización
                // Aquí debes ejecutar la consulta de actualización en la base de datos

                // Mostrar mensaje de éxito
                alert("Datos actualizados correctamente");
            } else {
                // Mostrar mensaje de error
                document.getElementById('mensaje-error').innerText = "Contraseña incorrecta";
            }
        }

        function cerrarModal() {
            document.getElementById('modal').style.display = 'none';
        }
    </script>

    <?php if (isset($_GET['visualizar']) && $_GET['visualizar'] == 1) { ?>
        <div class="acuse-container">
            <h3>Acuses</h3>
            <?php if ($acuse) { ?>
                <?php for ($i = 1; $i <= 3; $i++) {
                    if (!empty($acuse["nombrea$i"])) { ?>
                        <p><a href="acuses/<?php echo $acuse["nombrea$i"]; ?>" target="_blank">Ver Acuse <?php echo $i; ?></a></p>
                    <?php }
                } ?>
                <?php if ($puede_subir_pdf) { ?>
                    <form method="post" enctype="multipart/form-data">
                        <label for="archivo_pdf">Subir nuevo PDF:</label>
                        <input type="file" id="archivo_pdf" name="archivo_pdf" accept=".pdf" required>
                        <button type="submit" name="guardar">Guardar</button>
                    </form>
                <?php } ?>
            <?php } else { ?>
                <p>No hay acuses disponibles.</p>
            <?php } ?>
        </div>
    <?php } ?>

</body>
</html>
