
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Exitosa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .success-message {
            color: #28a745;
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .buttons-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-message">
            <h1>¡Compra Realizada con Éxito!</h1>
            <p>Gracias por tu compra. Tu pedido ha sido procesado correctamente.</p>
        </div>
        <div class="buttons-container">
            <a href="historial_compras.php" class="button">Ver Historial de Compras</a>
            <a href="index.php" class="button">Volver al Inicio</a>
        </div>
    </div>
</body>
</html>
