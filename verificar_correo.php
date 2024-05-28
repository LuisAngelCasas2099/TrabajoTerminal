<?php
// Verificar si se ha enviado el formulario
if(isset($_POST['submit'])) {
    // Obtener el correo electrónico enviado desde el formulario
    $email = $_POST['correo'];

    // Conexión a la base de datos (reemplaza los valores con los de tu configuración)
    $servername = "localhost";
    $username = "Admin";
    $password = "gamer4life";
    $dbname = "basededatos";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    // Consulta SQL para verificar si el correo existe en la base de datos
    $sql = "SELECT * FROM usuarios WHERE correo = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // El correo existe en la base de datos
        header('Location: RecuperarContraseña.php?success');
        exit();
    } else {
        // El correo no está registrado
        header('Location: RecuperarContraseña.php?error');
        exit();
    }

    $conn->close();
} else {
    // Si se intenta acceder directamente a verificar_correo.php sin enviar el formulario, redirigir a RecuperarContrasena.php
    header('Location: RecuperarContraseña.php');
    exit();
}
?>
