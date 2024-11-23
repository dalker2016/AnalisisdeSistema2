<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'];

if ($role == 'admin') {
    header('Location: admin.php');
} elseif ($role == 'empleado') {
    header('Location: empleado.php');
} else {
    header('Location: index.php');
}
?>