<!DOCTYPE html>
<html>
<head>
    <title>Asignación de Academia</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="styles2.css">
</head>
<body>
    <div class="header">
        <h1>Asignación de Academia</h1>
        <div class="dropdown">
            <button class="dropbtn">Menú</button>
            <div class="dropdown-content">
                <a href="perfil.php">Perfil</a>
                <a href="login.php">Cerrar Sesión</a>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="formulario">
            <form method="post" action="guardar_asignacion.php">
                <label for="titulo">Título del Protocolo:</label>
                <input type="text" id="titulo" name="titulo" readonly>

                <label for="directores">Directores:</label>
                <input type="text" id="directores" name="directores" readonly>

                <label for="resumen">Resumen:</label>
                <textarea id="resumen" name="resumen" rows="5" readonly></textarea>

                <label for="academia">Academia:</label>
                <select id="academia" name="academia">
                    <option value="">Seleccione una opción</option>
                    <option value="academia1">Academia 1</option>
                    <option value="academia2">Academia 2</option>
                    <option value="academia3">Academia 3</option>
                </select>

                <label for="semestre">Semestre:</label>
                <select id="semestre" name="semestre">
                    <option value="">Seleccione una opción</option>
                    <option value="semestre1">Semestre 1</option>
                    <option value="semestre2">Semestre 2</option>
                    <option value="semestre3">Semestre 3</option>
                </select>

                <label for="carrera">Carrera:</label>
                <select id="carrera" name="carrera">
                    <option value="">Seleccione una opción</option>
                    <option value="carrera1">Carrera 1</option>
                    <option value="carrera2">Carrera 2</option>
                    <option value="carrera3">Carrera 3</option>
                </select>

                <button type="submit">Guardar</button>
            </form>
        </div>

        <div class="pdf">
            <iframe src="ruta_del_archivo.pdf"></iframe>
        </div>
    </div>
</body>
</html>
