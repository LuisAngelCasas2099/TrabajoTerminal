<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#loginForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                fetch('procesar_login.php', {
                    method: 'POST',
                    body: new FormData(form)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        const errorMessage = document.createElement('div');
                        errorMessage.textContent = data.error;
                        errorMessage.style.color = 'white';
                        errorMessage.style.backgroundColor = 'red';
                        errorMessage.style.padding = '10px';
                        form.insertBefore(errorMessage, form.firstChild);
                    } else {
                        window.location.href = 'perfil.php';
                    }
                });
            });
        });
    </script>
</head>
<body>
    <div class="header">
        <h1>Sistema de gestión de protocolos de titulación</h1>
    </div>

    <div class="container">
        <h2>Iniciar Sesión</h2>
        <form id="loginForm" method="post">
            <label for="correo">Correo:</label>
            <input type="text" id="correo" name="correo" required><br>
            
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required><br>
            
            <input type="submit" value="Iniciar Sesión">
        </form>
        <br>
        <a href="RecuperarContraseña.php">¿Olvidaste tu contraseña?</a> | <a href="registrarse.php">Registrarse</a>
    </div>

    <div class="footer">
        <p>Desarrollado para la Escuela Superior de Cómputo</p>
    </div>
</body>
</html>
