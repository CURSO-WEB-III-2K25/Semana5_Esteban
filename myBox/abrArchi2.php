<?php
session_start();

// Verifica autenticación
if ($_SESSION["autenticado"] != "SI") {
    header("Location: index.php");
    exit();
}

// Verifica parámetros
if (!isset($_GET['arch'])) {
    die("No se especificó ningún archivo.");
}

$archivo = urldecode($_GET['arch']);
$rutaActual = isset($_GET['rutaActual']) ? urldecode($_GET['rutaActual']) : '';

// Carpeta base del usuario
$base = realpath("C:/mybox/" . $_SESSION["usuario"]);
if ($base === false) {
    die("Directorio del usuario no encontrado.");
}

// Normaliza separadores y limpia paths peligrosos
$rutaActual = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $rutaActual);
$archivo = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $archivo);

// Construye ruta completa
$rutaCompleta = $base;
if ($rutaActual !== '' && $rutaActual !== '.' && $rutaActual !== './') {
    $rutaCompleta .= DIRECTORY_SEPARATOR . $rutaActual;
}
$rutaCompleta .= DIRECTORY_SEPARATOR . $archivo;

// Obtiene ruta real y valida
$rutaCompleta = realpath($rutaCompleta);

if ($rutaCompleta === false || strpos($rutaCompleta, $base) !== 0) {
    die("Archivo no encontrado o acceso no autorizado.");
}

if (!is_file($rutaCompleta)) {
    die("El elemento no es un archivo válido.");
}

// Obtiene tipo MIME, tamaño y extensión
$mime = mime_content_type($rutaCompleta);
$tamanio = filesize($rutaCompleta);
$extension = strtolower(pathinfo($rutaCompleta, PATHINFO_EXTENSION));

// Archivos que se muestran en navegador
$mostrarEnNavegador = ['pdf', 'jpg', 'jpeg', 'png'];

// Envía el archivo
if (in_array($extension, $mostrarEnNavegador)) {
    // Se muestra directamente en el navegador
    header("Content-Type: " . $mime);
    header("Content-Length: " . $tamanio);
    readfile($rutaCompleta);
} else {
    // Otros archivos se descargan
    header("Content-Disposition: attachment; filename=\"" . basename($rutaCompleta) . "\"");
    header("Content-Type: " . $mime);
    header("Content-Length: " . $tamanio);
    readfile($rutaCompleta);
}
exit();
?>
