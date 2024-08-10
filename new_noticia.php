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
            max-width: 700px;
            background-color: #f7f9fc;
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
    <div class="container mt-5 mb-5">
        <h2 class="text-center">Agregar Nueva Noticia</h2>
        <form id="formNoticias" action="insertar_noticia.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título:</label>
                <input type="text" class="form-control" id="titulo" name="titulo" required>
            </div>
            <div class="mb-3">
                <label for="resumen" class="form-label">Resumen:</label>
                <textarea class="form-control" id="resumen" name="resumen" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label for="contenido" class="form-label">Contenido:</label>
                <textarea class="form-control" id="contenido" name="contenido" rows="2" required></textarea>
            </div>
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" required>
            </div>
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen:</label>
                <input type="file" class="form-control" id="imagen" name="imagen" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Agregar Noticia</button>
            </div>
        </form>
    </div>
    <?php include 'components/footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById('formNoticias');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                enviarDatosNoticia();
            });
        });

        function enviarDatosNoticia() {
            var xhr = new XMLHttpRequest();
            var url = "new_forms/insertar_noticia.php";
            xhr.open("POST", url, true);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText);
                    if (xhr.responseText.includes("exitoso")) {
                        Swal.fire({
                            title: '¡Éxito!',
                            text: 'La noticia ha sido registrada correctamente.',
                            icon: 'success',
                            confirmButtonText: 'Cerrar'
                        });
                        document.getElementById('formNoticias').reset();
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: 'No se pudo registrar la noticia.',
                            icon: 'error',
                            confirmButtonText: 'Cerrar'
                        });
                    }
                }
            };

            var formData = new FormData(document.getElementById('formNoticias'));
            xhr.send(formData);
        }
    </script>

</body>
</html>
