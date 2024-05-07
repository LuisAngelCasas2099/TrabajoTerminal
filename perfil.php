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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Perfil</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
</head>
<body>
    <div class="header">
        <div class="mensaje-bienvenida">
            <h2>Bienvenido, <?php echo $nombre; ?></h2>
        </div>
        <h1 class="titulo">Perfil</h1>
        <div class="dropdown">
            <button class="dropbtn">Menú</button>
            <div class="dropdown-content">
                <a href="inicio.php">Inicio</a>
                <a href="#">Perfil</a>
                <a href="#">Protocolo</a>
            </div>
        </div>
    </div>

    <div class="container-align-left">
        <div class="datos">
            <div class="datos-box">
                <p><strong>Nombre:</strong> <span id="nombre"><?php echo $nombre; ?></span></p>
                <p><strong>Correo:</strong> <?php echo $correo; ?></p>
                <p><strong>Teléfono:</strong> <span id="telefono"><?php echo $telefono; ?></span></p>
                <p><strong>Boleta:</strong> <span id="boleta"><?php echo $boleta; ?></span></p>
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
    
            <div class="protocolo">
                <h3>Protocolo</h3>
                <button class="modificar-btn">Visualizar Información</button>
                <p>Aquí va el contenido del protocolo...</p>
            </div>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <label for="password">Ingresa tu contraseña:</label>
            <input type="password" id="password">
            <button class="modificar-btn" onclick="confirmarCambios()">Confirmar</button>
        </div>
    </div>

    <footer>
        <div class="footer">
            <p>Desarrollado para la Escuela Superior de Computo</p>
        </div>
    </footer>

    <script>
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
    
</body>
</html>
