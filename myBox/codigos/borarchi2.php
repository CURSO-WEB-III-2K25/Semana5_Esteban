<?php
	session_start();

	// Verifica autenticación
	if ($_SESSION["autenticado"] != "SI") {
		header("Location: index.php");
		exit();
	}

	// Carpeta base del usuario
	$base = realpath("c:/mybox/" . $_SESSION["usuario"]);

	if (!isset($_GET['carpeta'])) {
		die("No se especificó qué archivo o carpeta borrar.");
	}

	$rutaRelativa = urldecode($_GET['carpeta']);
	$rutaCompleta = realpath($rutaRelativa);

	// Función para eliminar carpetas con contenido
	function eliminarDirectorio($dir) {
		if (!file_exists($dir)) return;
		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') continue;
			$path = "$dir/$item";
			if (is_dir($path)) {
				eliminarDirectorio($path);
			} else {
				unlink($path);
			}
		}
		rmdir($dir);
	}

	// Elimina archivo o carpeta
	if (is_file($rutaCompleta)) {
		$resultado = @unlink($rutaCompleta);
		$mensaje = $resultado ? "Se ha eliminado el archivo correctamente." : "No se pudo eliminar el archivo.";
		
	} elseif (is_dir($rutaCompleta)) {
		eliminarDirectorio($rutaCompleta);
		$mensaje = "Carpeta eliminada correctamente.";
	} else {
		$mensaje = "El elemento no existe o no es válido.";
	}

	// Retorna al punto de invocación
	$Ir_A = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "carpetas2.php";
	echo "<script>alert('" . addslashes($mensaje) . "'); location.href='" . $Ir_A . "';</script>";
	exit();
?>
