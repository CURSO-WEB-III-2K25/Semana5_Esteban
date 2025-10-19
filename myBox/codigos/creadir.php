<?php
	//Inicio la sesión
	session_start();

	//Utiliza los datos de sesion comprueba que el usuario este autenticado
	if ($_SESSION["autenticado"] != "SI") {
		header("Location: ../index.php");
		exit(); //fin del script
	}

	//declara ruta carpeta del usuario
	$ruta = "d:\\mybox";
	$ruta = $ruta.'/'.$_SESSION["usuario"];

	if(!mkdir($ruta,0700)){
		echo 'ERROR:\\ NO se pudo crear directorio para almacenar archivos.<br>';
		echo 'Favor pongase en contacto con el departamento de servicio al cliente.<br>';
        echo 'Ruta.....'.$ruta;
    }else{
		header("Location: ../carpetas.php");
	} // fin del if del mkdir
?>
