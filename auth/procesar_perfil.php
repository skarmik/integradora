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
    if (isset($_POST['cambio_contraseña']) || isset($_POST['cerrar_sesion'])) {
        $correo_electronico = limpiarDatos($_POST['correo_electronico']);
        $tipo_usuario = isset($_SESSION['admin_id']) ? 'admin' : 'usuario';
        
        if ($tipo_usuario === 'admin') {
            $sql = "SELECT * FROM admins WHERE correo_electronico = ?";
        } else {
            $sql = "SELECT * FROM usuarios WHERE correo_electronico = ?";
        }

        $stmt = $conection->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $correo_electronico);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                if (isset($_POST['cambio_contraseña'])) {
                    header("Location: ../auth/cambio_contrasena.php");
                    exit();
                } elseif (isset($_POST['cerrar_sesion'])) {
                    session_destroy();
                    header("Location: ../index.php");
                    exit();
                }
            } else {
                echo "<script>alert('El correo electrónico no existe'); window.location.href='../admin_profile.php';</script>";
                exit();
            }
            $stmt->close();
        } else {
            echo "<script>alert('Error preparando la consulta.'); window.location.href='../admin_profile.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Solicitud no válida.'); window.location.href='../admin_profile.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Método no permitido.'); window.location.href='../admin_profile.php';</script>";
    exit();
}

$conection->close();
?>
