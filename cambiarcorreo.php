<!DOCTYPE html>
<html>
<head>
    <title>Cambiar Correo</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Cambiar Correo</h1>
        <form method="post">
            <label for="correo_anterior">Correo Anterior:</label>
            <input type="email" id="correo_anterior" name="correo_anterior" required>
            
            <label for="correo_nuevo">Nuevo Correo:</label>
            <input type="email" id="correo_nuevo" name="correo_nuevo" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" name="actualizar" value="Actualizar Correo">
        </form>

        <?php
        $dsn = 'mysql:host=localhost;dbname=basededatos';
        $usuario = 'Admin';
        $contrasena_bd = 'gamer4life';

        if(isset($_POST['actualizar'])) {
            $correo_anterior = $_POST['correo_anterior'];
            $correo_nuevo = $_POST['correo_nuevo'];
            $password = $_POST['password'];

            $opciones = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            try {
                $conexion = new PDO($dsn, $usuario, $contrasena_bd, $opciones);

                $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE correo = :correo AND password = :password);
                $consulta->bindParam(':correo', $correo_anterior);
                $consulta->bindParam(':password', $password);
                $consulta->execute();

                $resultado = $consulta->fetch();

                if($resultado) {
                    $actualizar = $conexion->prepare("UPDATE usuarios SET correo = :nuevo_correo WHERE correo = :correo_anterior");
                    $actualizar->bindParam(':nuevo_correo', $correo_nuevo);
                    $actualizar->bindParam(':correo_anterior', $correo_anterior);
                    $actualizar->execute();

                    echo "<script>alert('Correo cambiado exitosamente');</script>";
                } else {
                    echo "<script>alert('Correo o contraseña incorrectos');</script>";
                }

            } catch(PDOException $e) {
                echo "Error al conectar a la base de datos: " . $e->getMessage();
            }
        }
        ?>
    </div>

    <footer>
    <div class="footer">
        <p>Desarrollado para la Escuela Superior de Computo</p>
    </div>
    </footer>
</body>
</html>
