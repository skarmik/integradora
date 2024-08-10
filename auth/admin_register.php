<?php
include '../conection/conection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .form-label {
            color: #465772;
            font-weight: bold;
        }
        .form-control {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px 15px;
            background-color: #e9f2ff;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(109, 160, 237, 0.25);
            border-color: #6DA0ED;
        }
        .btn-primary {
            background-color: #6DA0ED;
            border-radius: 20px;
            padding: 10px 60px;
            font-weight: 800;
            font-size: 16px;
            border: none;
            transition: background-color 0.3s ease;
            display: block;
            margin: 0 auto;
        }
        .btn-primary:hover {
            background-color: #5b8ad1;
        }
        @media (max-width: 768px) {
            .container {
                max-width: 90%;
                padding: 20px;
            }
            h2 {
                font-size: 24px;
            }
            .btn-primary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Asegúrate de que el navbar está correctamente ubicado dentro del body -->
    <?php include 'navbar2.php'; ?>

    <div class="container">
        <h2>Registro de Administrador</h2>
        <form action="procesar_registro_admin.php" method="POST">
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
            </div>
            <div class="mb-3">
                <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
            </div>
            <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
    </div>

    <!-- Asegúrate de que el footer está correctamente ubicado dentro del body -->
    <?php include 'footer2.php'; ?>
</body>
</html>
