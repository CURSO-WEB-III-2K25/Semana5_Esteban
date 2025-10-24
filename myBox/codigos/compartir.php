<?php
    session_start();
    include("conexion.inc");

    //verificación del usuario, para verificar si ya esta auntenticado
	if($_SESSION["autenticado"] != "SI") {
		header("Location: index.php");
		exit(); 
	}

    //Verfica que haya un recibido de los datos
    if (!isset($_POST["destinatario"]) || !isset($_POST["archivo"])){
        echo "Error: Faltan datos.";
        exit; 
    }

    $usuarioOrigen = $_SESSION["usuario"];
    $usuarioDestino = trim($_POST['destinatario']);
    $archivoOrigen = trim($_POST["archivo"]); // Aqui esta la ruta completa del archivo dentro del usuario origen

    //Vertificamos que el usuario destinatario se cuentre en la tabla usuarios 
    $mysql = "SELECT usuario FROM usuarios WHERE usuarios = ? "; 
    $stmt = mysqli_prepare($conex, $sql);
    mysqli_stmt_bind_param($stmt, "s", $usuarioDestino);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if(mysqli_stmt_num_rows($stmt) == 0){
        echo "Error el usuario '$usuarioDestino' no existe "; 
        exit;
    }

    mysqli_stmt_close($stmt);

    // Definición de rutas de origen y destino
    $carpetaBase = "c:\\mybox";
    $rutaOrigen = $carpetaBase . $usuarioOrigen . "/" . $archivoOrigen;
    $rutaDestino = $carpetaBase . $usuarioDestino . "/" . basename($archivoOrigen);

    // Verificacipon si el archivo existe
    if(!file_exists($rutaOrigen)){
        echo "Error: El archivo o carpeta origen no existe.";
        exit; 
    }

    // Si es carpeta → copiar recursivamente, si es archivo → copiar normal

   function copiarR($origen, $destino) {
    if (is_dir($origen)) {
        @mkdir($destino, 0700, true);
        $archivos = scandir($origen);
        foreach ($archivos as $archivo) {
            if ($archivo != "." && $archivo != "..") {
                copiarR("$origen/$archivo", "$destino/$archivo");
            }
        }
    } else {
        copy($origen, $destino);
    }
}

copiarR($rutaOrigen, $rutaDestino);

echo "En hora buena, el contenido fue compartido exitosamente con '$usuarioDestino'. :D";

mysqli_close($conex);


?>