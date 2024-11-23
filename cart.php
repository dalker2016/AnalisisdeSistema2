<?php
session_start();
include 'productos.php';

if (isset($_POST['remove_from_cart'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
}

if (isset($_POST['confirm_order'])) {
    header("Location: confirm.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Carrito de Compras</h1>

    <?php if (empty($_SESSION['cart'])) { ?>
        <p>Tu carrito está vacío. <a href="index.php">Volver al menú</a></p>
    <?php } else { ?>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $id => $quantity) {
                    $producto = $productos[$id];
                    $subtotal = $producto['precio'] * $quantity;
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?php echo $producto['nombre']; ?></td>
                        <td><?php echo $quantity; ?></td>
                        <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                <button type="submit" name="remove_from_cart">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <h3>Total del Pedido: $<?php echo number_format($total, 2); ?></h3>
        <form method="POST">
            <button type="submit" name="confirm_order">Confirmar Pedido</button>
        </form>
    <?php } ?>
</body>
</html>