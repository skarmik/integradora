<?php
include '../conection/conection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $correo_electronico = $_POST['correo_electronico'];
    $contrasena = $_POST['contrasena'];

    $stmt = $conection->prepare("SELECT id, nombre_usuario, contrasena FROM admins WHERE correo_electronico = ?");
    $stmt->bind_param("s", $correo_electronico);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $nombre_usuario, $hashed_password);
        $stmt->fetch();

        if (password_verify($contrasena, $hashed_password)) {
            // Iniciar sesión como administrador
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_name'] = $nombre_usuario;
            header("Location: ../index.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Correo electrónico no encontrado.";
    }

    $stmt->close();
    $conection->close();
}
?>
