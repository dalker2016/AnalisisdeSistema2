<?php
session_start();
if ($_SESSION['role'] != 'empleado') {
    header('Location: login.php');
    exit();
}
include 'database.php';

if (isset($_POST['confirm_factura'])) {
    $factura_id = $_POST['factura_id'];
    $stmt = $pdo->prepare('UPDATE facturas SET estado = "pagada" WHERE id = ?');
    $stmt->execute([$factura_id]);

    // Enviar confirmación al
