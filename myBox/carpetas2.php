<?php
    session_start();

    // Verifica que el usuario esté autenticado
    if ($_SESSION["autenticado"] != "SI") {
        header("Location: index.php");
        exit();
    }

    // Carpeta base del usuario
    $base = "c:\\mybox\\" . $_SESSION["usuario"];

    // Ruta relativa actual (subcarpetas)
    $ruta_actual = isset($_GET['ruta']) ? urldecode($_GET['ruta']) : '';

    // Ruta absoluta real (sin realpath)
    $ruta = rtrim($base . ($ruta_actual ? '\\' . $ruta_actual : ''), '\\/');

    // Si no existe, vuelve a la raíz del usuario
    if (!is_dir($ruta)) {
        $ruta = $base;
        $ruta_actual = '';
    }

?>
<!doctype html>
<html>
<head>
    <?php include_once('partes/encabe.inc'); ?>
    <title>Mi Cajón de Archivos</title>
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
    <div class="panel panel-primary">
        <div class="panel-heading">
            <strong>Mi Cajón de Archivos</strong>
        </div>
        <div class="panel-body">
            <?php
                // Mostrar botones
                if ($ruta_actual != '' && $ruta_actual != '.' && $ruta_actual != './') {
                    $ruta_padre = dirname($ruta_actual);
                    echo '<a href="carpetas2.php?ruta=' . urlencode($ruta_padre) . '">⬅ Volver</a><br><br>';
                }

                // Botones de agregar archivo y carpeta
                echo '<a href="./agrearchi2.php?rutaActual=' . urlencode($ruta_actual) . '">Agregar archivo &nbsp;&nbsp;&nbsp;</a>';
                echo '<a href="codigos/crearSubDir2.php?rutaActual=' . urlencode($ruta_actual) . '">Agregar Carpeta</a>';
                echo '<br><br>';

                // Listar contenido
                $conta = 0;
                $directorio = opendir($ruta);
                echo '<table class="table table-striped">';
                echo '<tr>
                        <th>Nombre</th>
                        <th>Tamaño</th>
                        <th>Último acceso</th>
                        <th>Archivo</th>
                        <th>Directorio</th>
                        <th>Lectura</th>
                        <th>Escritura</th>
                        <th>Ejecutable</th>
                        <th>Borrar</th>
                    </tr>';

                while ($elem = readdir($directorio)) {
                    if ($elem == '.' || $elem == '..') continue;
                    
                    $ruta_elem = $ruta . '\\' . $elem;
                    $sub_ruta = ($ruta_actual ? $ruta_actual . '\\' : '') . $elem;

                    echo '<tr>';

                    if (is_dir($ruta_elem)) {
                        echo '<th><a href="carpetas2.php?ruta=' . urlencode($sub_ruta) . '">' . htmlspecialchars($elem) . '</a></th>';
                    } elseif (is_file($ruta_elem)) {
                        echo '<th><a href="abrArchi.php?arch=' . urlencode($elem) . '&rutaActual=' . urlencode($ruta_actual) . '">' . htmlspecialchars($elem) . '</a></th>';
                    } else {
                        echo '<th>' . htmlspecialchars($elem) . '</th>';
                    }

                    echo '<th>' . filesize($ruta_elem) . ' bytes</th>';
                    echo '<th>' . date("d/m/y H:i:s", fileatime($ruta_elem)) . '</th>';
                    echo '<th>' . (is_file($ruta_elem) ? 'Sí' : '') . '</th>';
                    echo '<th>' . (is_dir($ruta_elem) ? 'Sí' : '') . '</th>';
                    echo '<th>' . (is_readable($ruta_elem) ? 'Sí' : 'No') . '</th>';
                    echo '<th>' . (is_writable($ruta_elem) ? 'Sí' : 'No') . '</th>';
                    echo '<th>' . (is_executable($ruta_elem) ? 'Sí' : 'No') . '</th>';
                    echo '<th><a href="./codigos/borarchi2.php?carpeta=' . urlencode($ruta_elem) . '">Hacer</a></th>';

                    echo '</tr>';
                    $conta++;
                }

                echo '</table>';
                closedir($directorio);

                if ($conta == 0) {
                    echo 'La carpeta del usuario se encuentra vacía';
                }
            ?>
        </div>
    </div>
</main>

<footer class="row">
</footer>
<?php include_once('partes/final.inc'); ?>
</body>
</html>
