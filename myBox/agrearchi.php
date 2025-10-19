<?php
    //Inicio la sesiÃ³n
    session_start();

    //Utiliza los datos de sesion comprueba que el usuario este autenticado
    if ($_SESSION["autenticado"] != "SI") {
       header("Location: index.php");
        exit(); //fin del scrip
    }

	//declara ruta carpeta del usuario
	$ruta = "d:\\mybox";
    $ruta = $ruta.'/'.$_SESSION["usuario"];

	$Accion_Formulario = $_SERVER['PHP_SELF'];
    if ((isset($_POST["OC_Aceptar"])) && ($_POST["OC_Aceptar"] == "frmArchi")) {
		$Sali = $_FILES['txtArchi']['name'];

		$Sali = str_replace(' ','_',$Sali);

      	move_uploaded_file($_FILES['txtArchi']['tmp_name'], $ruta . '/' . $Sali);
      	if(chmod($ruta. '/' . $Sali,0644)){
			header("Location: carpetas.php");
			exit(); //fin del scrip
		}else
			echo 'No se pudo cambiar los permisos, consulte a su administrador';
   }
?>
<!doctype html>
<html>
<head>
	<?php include_once('partes/encabe.inc'); ?>
    <title>Agregar archivos.</title>
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
		<div class="panel panel-primary datos1">
			<div class="panel-heading">
				<strong>Agregar archivo</strong>
			</div>
			<div class="panel-body">
				<form action="<?php echo $Accion_Formulario; ?>" method="post" enctype="multipart/form-data" name="frmArchi">
					<fieldset>
           				<label><strong>Archivo</strong></label><input name="txtArchi" type="file" id="txtArchi" size="60" />
           				<input type="submit" name="Submit" value="Cargar" />
         			</fieldset>
         			<input type="hidden" name="OC_Aceptar" value="frmArchi" />
      			</form>
			</div>
		</div>
    </main>

    <footer class="row">

    </footer>
	<?php include_once('partes/final.inc'); ?>
</body>
</html>
