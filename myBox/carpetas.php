<?php
	//Inicio la sesión
	session_start();

	//Utiliza los datos de sesion comprueba que el usuario este autenticado
	if($_SESSION["autenticado"] != "SI") {
		header("Location: index.php");
		exit(); //fin del scrip
	}

	$ruta  = "c:\\mybox".'\\'.$_SESSION["usuario"];
	$datos = explode('\\',"d:\\mybox");
?>
<!doctype html>
<html>
<head>
	<?php include_once('partes/encabe.inc'); ?>
    <title>Ingreso al Sitio</title>
</head>
<body class="container cuerpo">
	<header class="row">
        <div class="row">
        	<div class="col-lg-6 col-sm-6">
        		<img  src="imagenes/encabe.png" alt="logo institucional" width="100%">
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
					$conta = 0;
					$directorio = opendir($ruta);
					echo '<a href=./agrearchi.php>'.'Agregar archivo&nbsp&nbsp&nbsp</a>';
					echo '<a href=codigos/crearSubDir.php>'.'Agregar Carpeta</a>';
					echo '<br><br>';
					echo '<table class="table table-striped">';
						echo '<tr>';
							echo '<th>Nombre</th>';
							echo '<th>Tama&ntilde;o</th>';
							echo '<th>Ultimo acceso</th>';
							echo '<th>Archivo</th>';
							echo '<th>Directorio</th>';
							echo '<th>Lectura</th>';
							echo '<th>Escritura</th>';
							echo '<th>Ejecutable</th>';
							echo '<th>Borrar</th>';
						echo '</tr>';
						while($elem = readdir($directorio)){
							if(($elem!='.') and ($elem!='..')){
								echo '<tr>';
                                    echo '<th><a href=abrArchi.php?arch='.$elem.'>'.$elem.'</a></th>';
									echo '<th>'.filesize($ruta.'/'.$elem).' bytes</th>';
									echo '<th>'.date("d/m/y h:i:s",fileatime($ruta.'/'.$elem)).'</th>';
									echo '<th>'.is_file($ruta.'/'.$elem).'</th>';
									echo '<th>'.is_dir($ruta.'/'.$elem).'</th>';
									echo '<th>'.is_readable($ruta.'/'.$elem).'</th>';
									echo '<th>'.is_writeable($ruta.'/'.$elem).'</th>';
									echo '<th>'.is_executable($ruta.'/'.$elem).'</th>';
									echo '<th><a href=./codigos/borarchi.php?archi='.$elem.'>Hacer</a></th>';
								echo '</tr>';
								$conta++;
							} // fin del if
						} // fin del while
					echo '</table>';
					echo '<br><br>';
					closedir($directorio);
					if($conta == 0)
						echo 'La carpeta del usuario se encuetra vac&iacute;a';
				?>
			</div>
		</div>
    </main>

    <footer class="row">

    </footer>
	<?php include_once('partes/final.inc'); ?>
</body>
</html>
