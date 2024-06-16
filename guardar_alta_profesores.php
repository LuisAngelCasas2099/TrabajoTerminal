<?php
$conexion = mysqli_connect('localhost', 'Admin', 'gamer4life', 'basededatos');

$id = $_POST['id'];
$menu1 = $_POST['menu1'];
$menu2 = $_POST['menu2'];
$menu3 = $_POST['menu3'];

// Convertir los valores "Nulo" a NULL
$menu1 = $menu1 === "" ? "NULL" : "'$menu1'";
$menu2 = $menu2 === "" ? "NULL" : "'$menu2'";
$menu3 = $menu3 === "" ? "NULL" : "'$menu3'";

$query = "UPDATE usuarios SET academia1 = $menu1, academia2 = $menu2, academia3 = $menu3 WHERE id = $id";
$resultado = mysqli_query($conexion, $query);

if ($resultado) {
    echo 'Datos guardados correctamente';
} else {
    echo 'Error al guardar los datos';
}

mysqli_close($conexion);
?>
