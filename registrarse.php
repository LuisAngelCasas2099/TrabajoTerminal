<!DOCTYPE html>
<html>
<head>
    <title>Registrarse</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script>
        function limitarLongitud(input, maxLength) {
            if (input.value.length > maxLength) {
                input.value = input.value.slice(0, maxLength);
            }
        }
    </script>
</head>
<body>
<div class="header">
    <img src="Logo_Escom.png" class="logo-left">
    <h1>Sistema de gestión de protocolos de titulación</h1>
    <img src="Logo_Ipn.png" class="logo-right">
</div>

<div class="container">
    <h2>Registrarse</h2>
    <form action="guardar_usuario.php" method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>
        <label for="apellido">Apellidos:</label>
        <input type="text" id="apellido" name="apellido" required><br><br>
        <label for="boleta">Número de boleta:</label>
        <input type="text" id="boleta" name="boleta" pattern="[0-9]{1,10}" title="Solo se permiten números y máximo 10 dígitos" oninput="limitarLongitud(this, 10)" required><br><br>
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" required><br><br>
        <label for="telefono">Número Telefónico:</label>
        <input type="text" id="telefono" name="telefono" pattern="[0-9]{1,10}" title="Solo se permiten números y máximo 10 dígitos" oninput="limitarLongitud(this, 10)" required><br><br>
        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required><br><br>
        <input type="submit" value="Registrarse">
    </form>
    <br>
    <a href="login.php">Volver a Iniciar Sesión</a>
</div>

<div class="footer">
    <p>Desarrollado para la Escuela Superior de Computo</p>
</div>
</body>
</html>
