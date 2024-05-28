<?php
$conexion = mysqli_connect('localhost', 'Admin', 'gamer4life', 'basededatos');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo = $_POST['correo'];
    $menu1 = $_POST['menu1'];
    $menu2 = $_POST['menu2'];
    $menu3 = $_POST['menu3'];

    $query = "UPDATE usuarios SET academia1 = '$menu1', academia2 = '$menu2', academia3 = '$menu3' WHERE correo = '$correo'";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado) {
        echo 'Los datos se han guardado correctamente';
    } else {
        echo 'Ha ocurrido un error al guardar los datos';
    }
}

mysqli_close($conexion);
?>
