<!DOCTYPE html>
<html>
<head>
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .success-message {
            background-color: green;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            display: <?php echo isset($_GET['success']) ? 'block' : 'none'; ?>;
        }
        .error-message {
            background-color: red;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            display: <?php echo isset($_GET['error']) ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1> Recuperación de contraseña</h1>
    </div>

    <div class="container">
        <h2>Recuperar Contraseña</h2>
        <div class="success-message">Se ha enviado un correo</div>
        <div class="error-message">Correo no registrado o error al enviar el correo</div>
        
        <form method="post" action="verificar_correo.php">
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit" class="button" name="submit">Recuperar Contraseña</button>
        </form>
        <br>
        <a href="Login.php">Regresar al inicio</a>
    </div>
</body>
</html>
