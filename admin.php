<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'database.php';

// Agregar nuevo producto
if (isset($_POST['add_product'])) {
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $stmt = $pdo->prepare('INSERT INTO productos (nombre, precio) VALUES (?, ?)');
    $stmt->execute([$nombre, $precio]);
    header('Location: admin.php');
}

// Listar productos
$productos = $pdo->query('SELECT * FROM productos')->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Panel de Administrador</h1>

    <h2>Agregar Producto</h2>
    <form method="POST">
        <label for="nombre">Nombre del producto:</label>
        <input type="text" name="nombre" required>
        <br>
        <label for="precio">Precio:</label>
        <input type="text" name="precio" required>
        <br>
        <button type="submit" name="add_product">Agregar</button>
    </form>

    <h2>Lista de Productos</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto) { ?>
                <tr>
                    <td><?php echo $producto['nombre']; ?></td>
                    <td>$<?php echo $producto['precio']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
