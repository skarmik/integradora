<?php
session_start();
include '../conection/conection.php';

function limpiarDatos($dato) {
    global $conection;
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $conection->real_escape_string($dato);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['correo']) && isset($_POST['contrasena_nueva']) && isset($_POST['confirmar_contrasena'])) {
        $correo = limpiarDatos($_POST['correo']);
        $contrasena_nueva = limpiarDatos($_POST['contrasena_nueva']);
        $confirmar_contrasena = limpiarDatos($_POST['confirmar_contrasena']);
        $tipo_usuario = limpiarDatos($_POST['tipo_usuario']);

        if ($contrasena_nueva !== $confirmar_contrasena) {
            echo "Error: Las contraseñas no coinciden.";
            exit;
        }

        $contrasena_hash = password_hash($contrasena_nueva, PASSWORD_DEFAULT);

        if ($tipo_usuario === 'admin') {
            $sql = "UPDATE admins SET contrasena = ? WHERE correo_electronico = ?";
        } else {
            $sql = "UPDATE usuarios SET contrasena = ? WHERE correo_electronico = ?";
        }

        $stmt = $conection->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ss", $contrasena_hash, $correo);
            if ($stmt->execute()) {
                echo "Contraseña actualizada correctamente.";
            } else {
                echo "Error: No se pudo actualizar la contraseña.";
            }
            $stmt->close();
        } else {
            echo "Error: No se pudo preparar la consulta.";
        }
    } else {
        echo "Error: Faltan datos requeridos.";
    }
} else {
    echo "Error: Solicitud no válida.";
}

$conection->close();
?>
