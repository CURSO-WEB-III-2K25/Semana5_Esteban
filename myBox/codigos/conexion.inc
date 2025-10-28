<?php
    //parametros de conexion (opcionales)
	$servidor  = "localhost";
    $basedatos = "mybox";
    $usuario   = "root";
    $contra    = "esteban2511";

    //suprime advertencias
    // error_reporting(0);
    // mostrar todas las advertencias y errores para depuración
    error_reporting(E_ALL);

	//proceso para conectar con el servidor
    if(!$conex = mysqli_connect($servidor, $usuario, $contra, $basedatos)){
        echo "<h3><font color='red'>Error: No se puede conectar al servidor de MySQL.</font></h3>" . "<hr>";
        echo "<strong>Número........:</strong> " . mysqli_connect_errno() . "<br>";
        echo "<strong>Descripción...:</strong> " . mysqli_connect_error() . "<br>";
        exit;
    }
?>
