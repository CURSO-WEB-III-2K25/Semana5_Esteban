<?php
session_start();

// Verifica autenticación
if ($_SESSION["autenticado"] != "SI") {
    header("Location: index.php");
    exit();
}

// Carpeta base del usuario
$base = "c:\\mybox\\" . $_SESSION["usuario"];

// Ruta relativa actual (dentro de la carpeta del usuario)
$rutaActual = isset($_GET['rutaActual']) ? urldecode($_GET['rutaActual']) : '';

// Construir ruta absoluta de destino
$rutaDestino = realpath($base . ($rutaActual ? '\\' . $rutaActual : ''));

if ($rutaDestino === false || !is_dir($rutaDestino)) {
    die("Error: la ruta de destino no existe o no es válida.");
}

$Accion_Formulario = $_SERVER['PHP_SELF'];

// Si se envió el formulario
if (isset($_POST["OC_Aceptar"]) && $_POST["OC_Aceptar"] == "frmArchi") {

    if (!isset($_FILES['txtArchi']) || $_FILES['txtArchi']['error'] != UPLOAD_ERR_OK) {
        die("Error al subir el archivo. Intente nuevamente.");
    }

    // Nombre del archivo limpio
    $nombreArchivo = str_replace(' ', '_', $_FILES['txtArchi']['name']);
    $destinoCompleto = $rutaDestino . DIRECTORY_SEPARATOR . $nombreArchivo;

    // Mover archivo subido
    if (move_uploaded_file($_FILES['txtArchi']['tmp_name'], $destinoCompleto)) {
        chmod($destinoCompleto, 0644);

        // Redirigir de vuelta a la carpeta actual
        header("Location: carpetas2.php?ruta=" . urlencode($rutaActual));
        exit();
    } else {
        echo "No se pudo mover el archivo. Verifique permisos en $rutaDestino";
    }
}
?>
<!doctype html>
<html>
    <head>
        <?php include_once('partes/encabe.inc'); ?>
        <title>Agregar archivo</title>
    </head>
    <body class="container cuerpo">
        <header class="row">
            <div class="row">
                <div class="col-lg-6 col-sm-6">
                    <img src="imagenes/encabe.png" alt="logo institucional" width="100%">
                </div>
            </div>
            <div class="row">
                <?php include_once('partes/menu.inc'); ?>
            </div>
            <br />
        </header>

        <main class="row">
            <div class="panel panel-primary datos1">
                <div class="panel-heading">
                    <strong>Agregar archivo</strong>
                </div>
                <div class="panel-body">
                    <form action="<?php echo htmlspecialchars($Accion_Formulario . '?rutaActual=' . urlencode($rutaActual)); ?>" 
                        method="post" enctype="multipart/form-data" name="frmArchi">
                        <fieldset>
                            <label><strong>Archivo</strong></label>
                            <input name="txtArchi" type="file" id="txtArchi" size="60" required />
                            <input type="submit" name="Submit" value="Cargar" />
                        </fieldset>
                        <input type="hidden" name="OC_Aceptar" value="frmArchi" />
                    </form>
                </div>
            </div>
        </main>

        <footer class="row"></footer>
        <?php include_once('partes/final.inc'); ?>
    </body>
</html>
