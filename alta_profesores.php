<?php
$conexion = mysqli_connect('localhost', 'Admin', 'gamer4life', 'basededatos');
$query = "SELECT * FROM usuarios WHERE id = 0";
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alta de profesores</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
</head>
<body>
    <header>
        <div class="titulo">
            <h1>Alta de profesores</h1>
        </div>
        <div class="botones">
            <a href="perfil.php">Perfil</a>
            <a href="login.php">Cerrar sesión</a>
        </div>
    </header>

    <main>
        <table class="tabla">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo</th>
                    <th>Menú 1</th>
                    <th>Menú 2</th>
                    <th>Menú 3</th>
                    <th>Guardar</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($fila = mysqli_fetch_assoc($resultado)) { ?>
                    <tr>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['apellido']; ?></td>
                        <td><?php echo $fila['correo']; ?></td>
                        <td>
                            <select name="menu1_<?php echo $fila['correo']; ?>">
                                <option value="">Nulo</option>
                                <?php for ($i = 1; $i <= 11; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select name="menu2_<?php echo $fila['correo']; ?>">
                                <option value="">Nulo</option>
                                <?php for ($i = 1; $i <= 11; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <select name="menu3_<?php echo $fila['correo']; ?>">
                                <option value="">Nulo</option>
                                <?php for ($i = 1; $i <= 11; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td>
                            <button class="guardar" value="<?php echo $fila['correo']; ?>">Guardar</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>

    <script>
        const botonesGuardar = document.querySelectorAll('.guardar');
        botonesGuardar.forEach(boton => {
            boton.addEventListener('click', () => {
                const correo = boton.value;
                const menu1 = boton.parentNode.parentNode.querySelector('[name="menu1_' + correo + '"]').value;
                const menu2 = boton.parentNode.parentNode.querySelector('[name="menu2_' + correo + '"]').value;
                const menu3 = boton.parentNode.parentNode.querySelector('[name="menu3_' + correo + '"]').value;
                const datos = new FormData();
                datos.append('correo', correo);
                datos.append('menu1', menu1);
                datos.append('menu2', menu2);
                datos.append('menu3', menu3);
                fetch('guardar_alta_profesores.php', {
                    method: 'POST',
                    body: datos
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                })
                .catch(error => console.error(error));
            });
        });
    </script>
</body>
</html>

<?php
mysqli_close($conexion);
?>
