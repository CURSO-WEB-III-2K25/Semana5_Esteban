<?php
session_start();

// Comprueba autenticación
if ($_SESSION["autenticado"] != "SI") {
    header("Location: index.php");
    exit();
}

// Carpeta base del usuario
$base = "c:\\mybox\\" . $_SESSION["usuario"];

// Ruta actual recibida por GET
$rutaActual = isset($_GET['rutaActual']) ? urldecode($_GET['rutaActual']) : '';

// Ruta absoluta
$ruta = realpath($base . '\\' . $rutaActual);
if ($ruta === false) {
    $ruta = $base;
}

// Crear nueva carpeta si se envió el formulario
if (isset($_POST['carpetaNueva']) && !empty(trim($_POST['carpetaNueva']))) {
    $nombre = basename(trim($_POST['carpetaNueva']));
    $rutaNueva = rtrim($ruta, '/\\') . '\\' . $nombre;

    if (!file_exists($rutaNueva) && mkdir($rutaNueva, 0777, true)) {
        // Redirige de nuevo a carpetas.php con la ruta actual
        header("Location: ../carpetas2.php?ruta=" . urlencode($rutaActual));
        exit();
    } else {
        $mensaje = "No se pudo crear la carpeta";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Sub-Carpeta</title>
    <link rel="stylesheet" href="../estilos/styles.css">
</head>
<body>
    <div class="form-container">
        <h2>Crear Nueva Carpeta</h2>

        <?php
        if (isset($mensaje)) {
            echo '<div class="mensaje">' . htmlspecialchars($mensaje) . '</div>';
        }
        ?>

        <form method="POST" action="?rutaActual=<?php echo urlencode($rutaActual); ?>">
            <input type="text" name="carpetaNueva" placeholder="Nombre de la carpeta" required>
            <br><br>
            <button type="submit">Crear Carpeta</button>
            <a href="../carpetas.php?ruta=<?php echo urlencode($rutaActual); ?>"><button type="button">Cancelar</button></a>
        </form>
    </div>
</body>
</html>
