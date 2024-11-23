<?php
session_start();
include 'productos.php';

// Inicializar carrito
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Añadir producto al carrito
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }
    header("Location: cart.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Empresa de Delivery de Comida</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Bienvenido a nuestra Empresa de Delivery de Comida</h1>
    <div class="productos">
        <?php foreach ($productos as $id => $producto) { ?>
            <div class="producto">
                <h2><?php echo $producto['nombre']; ?></h2>
                <p>Precio: $<?php echo number_format($producto['precio'], 2); ?></p>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <label for="quantity">Cantidad:</label>
                    <input type="number" name="quantity" value="1" min="1" max="10">
                    <button type="submit" name="add_to_cart">Añadir al carrito</button>
                </form>
            </div>
        <?php } ?>
    </div>
    <a href="cart.php" class="btn">Ver carrito</a>
</body>
</html>