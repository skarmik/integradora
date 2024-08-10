<?php
include 'conection/conection.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head_meta.php'; ?>
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
    <?php include 'components/navbar.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center">Registrar Horario de Autobús</h2>
        <form id="formHorario" action="insertar_horario.php" method="post">
            <div class="mb-3">
                <label for="fecha_salida" class="form-label">Fecha de Salida:</label>
                <input type="date" class="form-control" id="fecha_salida" name="fecha_salida" required>
            </div>
            <div class="mb-3">
                <label for="hora_salida" class="form-label">Hora de Salida:</label>
                <input type="time" class="form-control" id="hora_salida" name="hora_salida" required>
            </div>
            <div class="mb-3">
                <label for="hora_llegada" class="form-label">Hora de Llegada:</label>
                <input type="time" class="form-control" id="hora_llegada" name="hora_llegada" required>
            </div>
            <div class="mb-3">
                <label for="id_trayecto" class="form-label">Trayecto:</label>
                <select class="form-control" id="id_trayecto" name="id_trayecto" required>
                    <?php
                        $sql = "SELECT id_trayecto, origen, destino FROM trayecto";
                        $result = $conection->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id_trayecto'] . "'>" . $row['origen'] . " - " . $row['destino'] . "</option>";
                            }
                        } else {
                            echo "<option>No hay trayectos disponibles</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_autobus" class="form-label">Autobús:</label>
                <select class="form-control" id="id_autobus" name="id_autobus" required>
                    <?php
                        $sql = "SELECT id_autobus, numero_autobus, modelo_autobus, asientos FROM autobus";
                        $result = $conection->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id_autobus'] . "'>" . $row['modelo_autobus'] . " (" . $row['numero_autobus'] . ") - (" . $row['asientos'] . ") Asientos</option>";
                            }
                        } else {
                            echo "<option>No hay autobuses disponibles</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-primary" onclick="enviarDatosHorario()">Registrar Horario</button>
            </div>
        </form>
    </div>

    <script>
        function enviarDatosHorario() {
            var formData = new FormData(document.getElementById('formHorario'));
            var hora_salida = document.getElementById('hora_salida').value;
            var hora_llegada = document.getElementById('hora_llegada').value;
            
            var today = new Date().toISOString().split('T')[0];
            document.getElementById("fecha_salida").setAttribute("min", today);

            var xhr = new XMLHttpRequest();
            var url = "new_forms/insertar_horario.php";
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                    if (xhr.responseText.includes("exitoso")) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'El horario ha sido registrado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Cerrar'
                        });
                        document.getElementById('formHorario').reset();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo registrar el horario.',
                            icon: 'error',
                            confirmButtonText: 'Cerrar'
                        });
                    }
                }
            };

            var params = new URLSearchParams(formData).toString();
            xhr.send(params);
        }
    </script>

</body>
<?php include 'components/footer.php'; ?>
</html>
