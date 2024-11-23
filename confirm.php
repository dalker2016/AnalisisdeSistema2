<?php
session_start();

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// Vaciar el carrito después de la confirmación
$_SESSION['cart'] = array();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Gracias por tu pedido</h1>
    <p>Tu pedido ha sido confirmado. En breve recibirás tu comida.</p>
    <a href="index.php">Volver al Menú</a>
</body>
</html>
