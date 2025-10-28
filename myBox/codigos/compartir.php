<?php
    session_start();
    include("conexion.inc");

    // Verificación de usuario autenticado
    if ($_SESSION["autenticado"] != "SI") {
        header("Location: index.php");
        exit();
    }

    // Verifica que se hayan recibido los datos
    if (!isset($_POST["destinatario"]) || !isset($_POST["archivo"])) {
        echo "Error: Faltan datos.";
        exit;
    }

    $usuarioOrigen = $_SESSION["usuario"];
    $usuarioDestino = trim($_POST['destinatario']);
    $archivoOrigen = trim($_POST["archivo"]); // Ruta relativa dentro del usuario origen

    // Verificar que el usuario destinatario exista en la tabla usuarios
    $sql = "SELECT usuario FROM usuarios WHERE usuario = ?";
    $stmt = mysqli_prepare($conex, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuarioDestino);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 0) {
        echo "Error: el usuario '$usuarioDestino' no existe.";
        exit;
    }
    mysqli_stmt_close($stmt);

    // Definición de rutas de origen y destino
    $carpetaBase = "c:\\mybox";

    // Ruta completa de origen
    $rutaOrigen = $carpetaBase . "\\" . $usuarioOrigen . "\\" . $archivoOrigen;

    // Ruta completa de destino (manteniendo subcarpetas)
    $rutaDestino = $carpetaBase . "\\" . $usuarioDestino . "\\" . $archivoOrigen;

    // Verificar si el archivo o carpeta de origen existe
    if (!file_exists($rutaOrigen)) {
        echo "Error: El archivo o carpeta origen no existe.";
        exit;
    }

    // Crear carpeta destino si no existe
    $carpetaDestino = dirname($rutaDestino);
    if (!is_dir($carpetaDestino)) {
        mkdir($carpetaDestino, 0700, true);
    }

    // Función para copiar recursivamente
    function copiarR($origen, $destino) {
        if (is_dir($origen)) {
            @mkdir($destino, 0700, true);
            $archivos = scandir($origen);
            foreach ($archivos as $archivo) {
                if ($archivo != "." && $archivo != "..") {
                    copiarR($origen . "\\" . $archivo, $destino . "\\" . $archivo);
                }
            }
        } else {
            copy($origen, $destino);
        }
    }

    // Copiar archivo o carpeta
    copiarR($rutaOrigen, $rutaDestino);
    echo "¡En hora buena! El contenido fue compartido exitosamente con '$usuarioDestino'. :D";
    header("Location: ../carpetas2.php");
    exit();

    mysqli_close($conex);
?>

