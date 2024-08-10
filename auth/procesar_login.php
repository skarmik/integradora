<?php
session_start();
include '../conection/conection.php';

$email = $_POST['correo_electronico'];
$password = $_POST['contrasena'];

// Primero, intenta encontrar el correo electrónico en la tabla de administradores
$sql = "SELECT * FROM admins WHERE correo_electronico = ?";
$stmt = $conection->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Usuario encontrado en la tabla de administradores
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['contrasena'])) {
        $_SESSION['admin_id'] = $user['id'];
        echo "Login exitoso";
    } else {
        echo "Error: Contraseña incorrecta";
    }
} else {
    // Si no se encuentra en la tabla de administradores, busca en la tabla de usuarios
    $sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
    $stmt = $conection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Usuario encontrado en la tabla de usuarios
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['contrasena'])) {
            $_SESSION['user_id'] = $user['id_usuario'];
            echo "Login exitoso";
        } else {
            echo "Error: Contraseña incorrecta";
        }
    } else {
        echo "Error: No existe una cuenta con ese correo electrónico";
    }
}

$stmt->close();
$conection->close();
?>
