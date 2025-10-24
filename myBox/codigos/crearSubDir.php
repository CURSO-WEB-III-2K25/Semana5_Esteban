<?php
    session_start();

    // Comprueba autenticación
    if ($_SESSION["autenticado"] != "SI") {
        header("Location: index.php");
        exit();
    }
        $baseRoute = "c:/mybox/" . $_SESSION["usuario"];

    // Crear nueva carpeta si se envió el formulario
    if (isset($_POST['carpetaNueva']) && !empty(trim($_POST['carpetaNueva']))) {
        $nombre = basename(trim($_POST['carpetaNueva']));
        $rutaNueva = "c:/mybox/" . $_SESSION["usuario"] . "/" . $nombre;

        if (!file_exists($rutaNueva) && mkdir($rutaNueva, 0777, true)) {
            header("Location: ../carpetas.php");
            exit(); // Termina script inmediatamente
        } else {
            $mensaje = "No se pudo crear la carpeta";
        }
    }
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Sub-Carpeta</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background-color: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-align: center;
            width: 350px;
        }
        input[type="text"] {
            width: 90%;
            padding: 10px;
            margin: 15px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            background-color: #1976d2;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0d47a1;
        }
        .mensaje {
            margin-top: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Crear Nueva Carpeta</h2>
        <form method="POST" action="">
            <input type="text" name="carpetaNueva" placeholder="Nombre de la carpeta" required>
            <br>
            <button type="submit">Crear Carpeta</button>
        </form>
    </div>
</body>
</html>
