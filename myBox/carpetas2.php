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
                        <th>Compartir</th>
                        <th>Borrar</th>
                    </tr>';

                while ($elem = readdir($directorio)) {
                    if ($elem == '.' || $elem == '..') continue;
                    
                    $ruta_elem = $ruta . '\\' . $elem;
                    $sub_ruta = ($ruta_actual ? $ruta_actual . '\\' : '') . $elem;
                    echo '<tr>';

                    if (is_dir($ruta_elem)) {
                        //aqui poner imagen de una carpeta
                        echo '<th><span><img src=" imagenes/folder.svg"></span>&nbsp;<a href="carpetas2.php?ruta=' . urlencode($sub_ruta) . '">' . htmlspecialchars($elem) . '</a></th>';

                    } elseif (is_file($ruta_elem)) {
                        //en esta linea debo poner validar las imagenes de los archivos
                        $ext = strtolower(pathinfo($elem, PATHINFO_EXTENSION));
                        $icon = "file.png";

                        if ($ext === 'pdf') {
                            $icon = "filetype-pdf.svg";
                        } elseif ($ext === 'xls' || $ext === 'xlsx' || $ext === 'csv') {
                            $icon = "file-excel.svg";
                        } elseif ($ext === 'doc' || $ext === 'docx') {
                            $icon = "file-earmark-word.svg";
                        } elseif ($ext === 'ppt' || $ext === 'pptx') {
                            $icon = "filetype-ppt.svg";
                        } elseif ($ext === 'png' || $ext === 'jpg' || $ext === 'jpeg' || $ext === 'gif' || $ext === 'bmp' || $ext === 'webp') {
                            $icon = "filetype-png.svg";
                        }

                        $iconPath = 'imagenes/' . $icon;

                        echo '<th><a href="abrArchi2.php?arch=' . urlencode($elem) . '&rutaActual=' . urlencode($ruta_actual) . '" style="text-decoration:none; color:inherit;"><img src="' . $iconPath . '" alt="" style="width:16px;height:16px;vertical-align:middle;margin-right:6px;"><span style="vertical-align:middle;">' . htmlspecialchars($elem) . '</span></a></th>';

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

                    // Columna compartir: sólo si es archivo o carpeta mostramos el formulario
                    echo '<th>';
                    if (is_file($ruta_elem) || is_dir($ruta_elem)) {
                        // Formulario mínimo para enviar al script compartir
                        echo '<form action="./codigos/compartir.php" method="POST" style="display:inline-block;">';
                        // archivo: la ruta relativa dentro del usuario
                        echo '<input type="hidden" name="archivo" value="' . htmlspecialchars($sub_ruta) . '">';
                        echo '<input type="text" name="destinatario" placeholder="Usuario destino" required style="width:110px;"> ';
                        echo '<button type="submit">Compartir</button>';
                        echo '</form>';
                    }
                    echo '</th>';

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

            <?php
                // === Mostrar recursos compartidos por otros usuarios ===
                $ruta_compartidos = $base . '\\compartidos';

                if (is_dir($ruta_compartidos)) {
                    echo '<hr>';
                    echo '<h4>📁 Recursos compartidos contigo</h4>';

                    $conta_comp = 0;
                    $dir_comp = opendir($ruta_compartidos);
                    echo '<table class="table table-striped">';
                    echo '<tr>
                            <th>Nombre</th>
                            <th>Tamaño</th>
                            <th>Último acceso</th>
                            <th>Archivo</th>
                            <th>Directorio</th>
                            <th>Acciones</th>
                        </tr>';

                    while ($comp = readdir($dir_comp)) {
                        if ($comp == '.' || $comp == '..') continue;

                        $ruta_elem = $ruta_compartidos . '\\' . $comp;
                        $sub_ruta_comp = 'compartidos\\' . $comp; // ruta relativa para abrir

                        echo '<tr>';
                        if (is_dir($ruta_elem)) {
                            // Si es directorio, permitimos navegar dentro (envía parametro ruta relativo dentro de carpeta compartidos)
                            echo '<th><a href="carpetas2.php?ruta=' . urlencode('compartidos\\' . $comp) . '">' . htmlspecialchars($comp) . '</a></th>';
                        } elseif (is_file($ruta_elem)) {
                            // Enlace para abrir/descargar con tu script existente (ajusta si hace falta)
                            echo '<th><a href="abrArchi2.php?arch=' . urlencode($comp) . '&rutaActual=' . urlencode('compartidos') . '">' . htmlspecialchars($comp) . '</a></th>';
                        } else {
                            echo '<th>' . htmlspecialchars($comp) . '</th>';
                        }

                        echo '<th>' . (is_file($ruta_elem) ? filesize($ruta_elem) . ' bytes' : '') . '</th>';
                        echo '<th>' . date("d/m/y H:i:s", fileatime($ruta_elem)) . '</th>';
                        echo '<th>' . (is_file($ruta_elem) ? 'Sí' : '') . '</th>';
                        echo '<th>' . (is_dir($ruta_elem) ? 'Sí' : '') . '</th>';

                        // Acciones: por ahora solo "Abrir" si es archivo, o "Entrar" si es carpeta
                        echo '<th>';
                        if (is_file($ruta_elem)) {
                            echo '<a href="abrArchi2.php?arch=' . urlencode($comp) . '&rutaActual=' . urlencode('compartidos') . '">Abrir</a>';
                        } elseif (is_dir($ruta_elem)) {
                            echo '<a href="carpetas2.php?ruta=' . urlencode('compartidos\\' . $comp) . '">Entrar</a>';
                        }
                        echo '</th>';

                        echo '</tr>';
                        $conta_comp++;
                    }

                    closedir($dir_comp);
                    echo '</table>';

                    if ($conta_comp == 0) {
                        echo 'No hay recursos compartidos aún.';
                    }
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
