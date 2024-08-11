<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head_meta.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h2 {
            font-weight: 800;
            margin-bottom: 30px;
            color: #333;
        }

        .form-label {
            font-weight: 800; /* Negrita */
            color: #555;
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 15px;
            background-color: #E9F2FF;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-control:focus {
            border-color: #6DA0ED;
            box-shadow: 0 0 0 0.2rem rgba(109, 160, 237, 0.25);
        }

        .btn-primary {
            background-color: #6DA0ED;
            border-radius: 20px;
            padding: 10px 40px;
            border: none;
            font-weight: 800;
        }

        .btn-primary:hover {
            background-color: #5a8fd9;
        }

        .container {
            max-width: 600px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 24px;
            }

            .container {
                padding: 20px;
            }

            .btn-primary {
                padding: 10px 30px;
            }
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container mt-5 mb-5 bg-form">
        <h2 class="text-center">Registrar nuevo autobús</h2>
        <form id="formAutobus" method="post">
            <div class="mb-3">
                <label for="numero_autobus" class="form-label">Número del Autobús:</label>
                <input type="text" class="form-control" id="numero_autobus" name="numero_autobus" required>
            </div>
            <div class="mb-3">
                <label for="modelo_autobus" class="form-label">Modelo del Autobús:</label>
                <select class="form-control" id="modelo_autobus" name="modelo_autobus" required>
                    <option value="" selected disabled>Seleccione un modelo</option>
                    <option value="Mercedes-Benz Sprinter 515">Mercedes-Benz Sprinter 515</option>
                    <option value="Volvo B8R">Volvo B8R</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="asientos" class="form-label">Asientos:</label>
                <input type="number" class="form-control" id="asientos" name="asientos" value="" placeholder="Selecciona un autobús" readonly>
            </div>

            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-primary" onclick="enviarDatosAutobus()">Registrar</button>
            </div>
        </form>
    </div>
    <?php include 'components/footer.php'; ?>

    <script>
        document.getElementById('modelo_autobus').addEventListener('change', function() {
            var modelo = this.value;
            var asientosInput = document.getElementById('asientos');

            if (modelo) {
                asientosInput.value = 44;
            } else {
                asientosInput.value = '';
                asientosInput.placeholder = 'Selecciona un autobús';
            }
        });

        function enviarDatosAutobus() {
            var modelo = document.getElementById('modelo_autobus').value;
            var asientosInput = document.getElementById('asientos');

            if (!modelo) {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, seleccione un modelo de autobús.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                });
                return;
            }

            if (asientosInput.value !== '44') {
                Swal.fire({
                    title: 'Error',
                    text: 'Por favor, seleccione un autobús válido.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                });
                return;
            }

            var xhr = new XMLHttpRequest();
            var url = "new_forms/insertar_autobus.php";
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                    if (xhr.responseText.includes("exitoso")) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'El autobús ha sido registrado correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Cerrar'
                        });
                        document.getElementById('formAutobus').reset();
                        asientosInput.value = '';
                        asientosInput.placeholder = 'Selecciona un autobús';
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo registrar el autobús.',
                            icon: 'error',
                            confirmButtonText: 'Cerrar'
                        });
                    }
                }
            };

            var formData = new FormData(document.getElementById('formAutobus'));
            var params = new URLSearchParams(formData).toString();
            xhr.send(params);
        }
    </script>
</body>
</html>
