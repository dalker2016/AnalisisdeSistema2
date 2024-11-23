<?php
session_start();
include 'database.php';

// Verificar si el cliente está autenticado
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'usuario') {
    header('Location: login.php');
    exit();
}

// Mostrar productos y permitir añadir al carrito
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    header('Location: pedido_cliente.php');
}

// Confirmar el pedido
if (isset($_POST['confirm_order'])) {
    $user_id = $_SESSION['user_id'];
    $total = 0;

    // Crear factura
    $stmt = $pdo->prepare('INSERT INTO facturas (cliente_id, total, tipo) VALUES (?, ?, ?)');
    $stmt->execute([$user_id, $total, 'cliente']);
    $factura_id = $pdo->lastInsertId();

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        // Obtener precio del producto
        $producto = $pdo->prepare('SELECT precio FROM productos WHERE id = ?');
        $producto->execute([$product_id]);
        $precio = $producto->fetch()['precio'];
        $total += $precio * $quantity;

        // Insertar pedido
        $stmt = $pdo->prepare('INSERT INTO pedidos (user_id, producto_id, cantidad, factura_id) VALUES (?, ?, ?, ?)');
        $stmt->execute([$user_id, $product_id, $quantity, $factura_id]);
    }

    // Actualizar el total de la factura
    $stmt = $pdo->prepare('UPDATE facturas SET total = ? WHERE id = ?');
    $stmt->execute([$total, $factura_id]);

    // Enviar factura a atención al cliente
    header('Location: facturacion_cliente.php?factura_id=' . $factura_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pedido</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Realizar Pedido</h1>
    <form method="POST">
        <select name="product_id">
            <?php
            $productos = $pdo->query('SELECT * FROM productos')->fetchAll();
            foreach ($productos as $producto) {
                echo "<option value='{$producto['id']}'>{$producto['nombre']} - $ {$producto['precio']}</option>";
            }
            ?>
        </select>
        <input type="number" name="quantity" min="1" max="10" value="1">
        <button type="submit" name="add_to_cart">Añadir al Carrito</button>
    </form>

    <h2>Carrito</h2>
    <?php if (!empty($_SESSION['cart'])) { ?>
        <form method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $product_id => $quantity) {
                        $producto = $pdo->prepare('SELECT * FROM productos WHERE id = ?');
                        $producto->execute([$product_id]);
                        $producto = $producto->fetch();
                        $subtotal = $producto['precio'] * $quantity;
                        $total += $subtotal;
                        echo "<tr>
                            <td>{$producto['nombre']}</td>
                            <td>$quantity</td>
                            <td>{$producto['precio']}</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
            <h3>Total: $<?php echo number_format($total, 2); ?></h3>
            <button type="submit" name="confirm_order">Confirmar Pedido</button>
        </form>
    <?php } else { ?>
        <p>No has añadido productos.</p>
    <?php } ?>
</body>
</html>
