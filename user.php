<?php
session_start();
include 'conection/conection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// Preparar y ejecutar la consulta para obtener los datos del usuario
$stmt = $conection->prepare("SELECT nombre_usuario, correo_electronico FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<script>
            alert('No se encontraron datos del usuario.');
            window.location.href = 'index.php';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'components/head_meta.php'; ?>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h4 {
            font-weight: 800;
        }

        input[type="text"], input[type="email"] {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px 15px;
            background-color: #E9F2FF;
            width: 60%;
            display: block;
            margin: 5px auto 15px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(109, 160, 237, 0.25);
            border-color: #6DA0ED;
        }

        label {
            color: #333;
            display: block;
            margin-bottom: 5px;
            font-weight: 200; /* Extra Light */
        }

        .bg-form {
            padding: 10px 30px 50px;
            border-radius: 20px;
            background-color: #d6e1f7;
            max-width: 500px;
            margin: 20px auto 30px;
        }

        .btn-primary {
            background-color: #6DA0ED;
            border-radius: 20px;
        }

        .btn-danger {
            background-color: #B6357B;
            border-radius: 20px;
        }

        .user-icon-container {
            position: relative;
            top: -45px;
            margin: 0 auto;
            width: 50px;
        }

        .user-icon {
            display: block;
            width: 100%;
            height: auto;
        }

        @media (max-width: 768px) {
            .bg-form {
                padding: 0px;
                margin-top: 10px;
                max-width: 90%;
                border-radius: 10px;
            }

            .user-icon-container {
                display: none;
            }

            input[type="text"], input[type="email"], .btn {
                width: 100%;
                margin: 10px 0;
            }

            input[type="text"], input[type="email"] {
                border-radius: 10px;
                width: 100%;
                margin: 10px 0;
                background-color: #FFFFFF;
            }

            .btn-primary, .btn-danger {
                font-size: 16px;
                border-radius: 15px;
                width: 80%;
            }

            label {
                font-size: 20px;
            }
        }

        @media (max-width: 576px) {
            input[type="text"], input[type="email"], .btn {
                width: 90%;
                margin: 10px auto;
            }

            label {
                font-size: 18px;
            }
        }

        @media (min-width: 992px) {
            input[type="text"], input[type="email"], .btn {
                width: 70%;
            }

            .bg-form {
                max-width: 600px;
            }
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container text-center mb-5">
        <h4 class="mt-4 mb-4">MI PERFIL</h4>
        <div class="bg-form mx-auto" style="max-width: 100%;">
            <div class="p-3">
                <form action="auth/procesar_perfil.php" method="POST" id="formulario">
                    <div class="user-icon-container">
                        <img class="user-icon img-fluid" src="assets/images/user.png" alt="User Icon">
                    </div>
                    <div class="form-group">
                        <label for="nombre_usuario">Nombre completo</label>
                        <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo htmlspecialchars($user['nombre_usuario']); ?>" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="correo_electronico">Correo electrónico</label>
                        <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($user['correo_electronico']); ?>" readonly required>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <button class="btn btn-primary btn-lg w-100 mt-3" type="submit" name="cambio_contraseña">Cambiar contraseña</button>
                        </div>
                        <div class="col-12 col-md-6">
                            <button class="btn btn-danger btn-lg w-100 mt-3" type="submit" name="cerrar_sesion">Cerrar sesión</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>
