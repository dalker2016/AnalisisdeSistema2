<?php
session_start();
if ($_SESSION['role'] != 'empleado') {
    header('Location: login.php');
    exit();
}

include 'database.php';

// Marcar pedido como completado
if (isset($_POST['completar_pedido'])) {
    $pedido_id = $_POST['pedido_id'];
    $stmt = $pdo->prepare('UPDATE pedidos SET estado = "completado" WHERE id = ?');
    $stmt->execute([$pedido_id]);
    header('Location: empleado.php');
}

// Listar pedidos pendientes
$pedidos = $pdo->query('SELECT p.id, p.cantidad, pr.nombre, u.username FROM pedidos p 
                        JOIN productos pr ON p.producto_id = pr.id 
                        JOIN users u ON p.user_id = u.id WHERE p.estado = "pendiente"')->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Empleado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Panel de Empleado</h1>

    <h2>Pedidos Pendientes</h2>
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido) { ?>
                <tr>
                    <td><?php echo $pedido['username']; ?></td>
                    <td><?php echo $pedido['nombre']; ?></td>
                    <td><?php echo $pedido['cantidad']; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="pedido_id" value="<?php echo $pedido['id']; ?>">
                            <button type="submit" name="completar_pedido">Marcar como completado</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>
</html>
   