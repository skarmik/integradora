<?php
include '../conection/conection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $correo_electronico = $_POST['correo_electronico'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    // Verificar si el correo electrónico ya está registrado
    $stmt = $conection->prepare("SELECT id FROM admins WHERE correo_electronico = ?");
    $stmt->bind_param("s", $correo_electronico);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Error: El correo electrónico ya está registrado.";
    } else {
        $stmt = $conection->prepare("INSERT INTO admins (nombre_usuario, correo_electronico, contrasena) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nombre_usuario, $correo_electronico, $contrasena);

        if ($stmt->execute()) {
            // Iniciar sesión automáticamente como administrador
            $_SESSION['admin_id'] = $stmt->insert_id;
            $_SESSION['admin_name'] = $nombre_usuario;
            header("Location: ../index.php");
            exit();
        } else {
            echo "Error en la ejecución: " . $stmt->error;
        }
    }

    $stmt->close();
    $conection->close();
} else {
    echo "Error: Solicitud no válida.";
}
?>
