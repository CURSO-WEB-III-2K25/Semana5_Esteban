<?php
session_start();

// Comprueba que el usuario esté autenticado
if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] != "SI") {
    header("Location: ../index.php");
    exit();
}

// Ruta base donde se guardarán las carpetas de los usuarios
$rutaBase = "C:\\mybox"; // En Windows, usa doble barra invertida \\

// Ruta del usuario (ej: C:\mybox\laura)
$rutaUsuario = $rutaBase . "\\" . $_SESSION["usuario"];

// Si la carpeta base no existe, créala
if (!is_dir($rutaBase)) {
    mkdir($rutaBase, 0700, true);
}

// Intentar crear la carpeta del usuario
if (!mkdir($rutaUsuario, 0700, true)) {
    echo 'ERROR:<br>No se pudo crear el directorio para almacenar archivos.<br>';
    echo 'Verifique permisos o contacte al administrador.<br>';
    echo 'Ruta: ' . $rutaUsuario;
} else {
    header("Location: ../carpetas2.php");
    exit();
}
?>
