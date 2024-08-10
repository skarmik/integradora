<?php
session_start();

$origen = $_POST['origen'];
$destino = $_POST['destino'];
$fecha = $_POST['fecha'];

// Verificar si el usuario es un administrador
if (isset($_SESSION['admin_id'])) {
    $url = "admin_r_b.php?origen=$origen&destino=$destino&fecha=$fecha";
} elseif (isset($_SESSION['user_id'])) {
    $url = "r_busqueda.php?origen=$origen&destino=$destino&fecha=$fecha";
} else {
    // Redirigir al login si no hay sesión activa
    $url = "auth/login.php";
}

header("Location: $url");
exit();
?>
