<?php
    include '../conection/conection.php';
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TicketOax</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@1,200&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="../styles/style.css">
        <style>

.nav-link {
    font-family: 'Inter', sans-serif; /* Aplica la fuente Inter */
    font-weight: 100; /* Hace el texto en negritas */
}

            h4 {
                font-family: 'Inter', sans-serif;
                font-weight: 600;

            }
            .texto_italic {
                font-family: 'Inter', sans-serif;
                font-weight: 200; /* 200 corresponde a Extra Light */
                font-style: italic;
            }
            input[type="password"], input[type="email"] {
                border: none;
                border-radius: 20px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                padding: 10px 15px;
                background-color: #FFFFFF;
                width: 60%;
                display: block;
                margin: 0px auto 0px;
            }
            a {
                text-decoration: none;
                color: black;
            }
            .form-control:focus {
                box-shadow: 0 0 0 0.2rem rgba(109, 160, 237, 0.25);
                border-color: #6DA0ED;
            }
            label {
                color: #465772;
                display: block;
                margin-bottom: 0px;
                font-size: 20px;
                font-family: 'Inter', sans-serif;
                font-weight: 200; /* 200 corresponde a Extra Light */
            }
            .bg-form {
                padding: 10px 30px 10px;
                border-radius: 20px;
                background-color: #d6e1f7;
                max-width: 60%;
                margin: 20px auto 30px;
            }
            .texto_italic {
                font-family: italic;
            }
            .btn-primary {
                background-color: #6DA0ED;
                border-radius: 20px;
                padding: 10px 60px;
            }
            @media (max-width: 768px) {
                .bg-form {
                    padding: 0px;
                    margin-top: 5% auto;
                    max-width: 90%;
                    border-radius: 20px;
                }
                .btn {
                    width: 100%;
                    margin: 10px 0;
                }
                input[type="password"], input[type="email"] {
                    border-radius: 20px;
                    width: 100%;
                    margin: 10px 0;
                    background-color: #FFFFFF;
                    padding: 10px 15px;
                    border: none;
                }
                .btn-primary {
                    font-size: 16px;
                    border-radius: 15px;
                    width: 70%;
                }
                label {
                    font-size: 14px;
                }
                .texto_italic {
                    margin-top: 20px;
                }
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">
                    <img src="../assets/images/logoTicket.png" alt="" style="width: 200px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="border-color: rgba(255,255,255,0.1);">
                    <span class="navbar-toggler-icon" style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 30 30\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath stroke=\'rgba(255, 255, 255, 1)\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-miterlimit=\'10\' d=\'M4 7h22M4 15h22M4 23h22\'/%3E%3C/svg%3E');"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="../index.php" style="color: white;">INICIO</a>
                        </li>
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="../contact.php" style="color: white;";>CONTACTO</a>
                        </li>
                        <a class="mx-3" href="../user.php">
                            <img style="width: 45px;" src="../assets/images/user.png" alt="Perfil del usuario">
                        </a>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="mensaje"></div>
        <h4 class="mt-4 text-center">INICIAR SESIÓN</h4>    
        <div class="container text-center bg-form mt-4 mx-auto">   
            <div class="p-3">
                <form action="procesar_login.php" method="POST" id="formulario">
                    <div class="form-group">
                        <label for="correo_electronico">Correo electrónico</label>
                        <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Contraseña</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" 
                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&_\-])[A-Za-z\d@$!%*?&_\-]{8,}"
                        title="Debe tener al menos 8 caracteres, incluir una mayúscula, una minúscula, un número y un carácter especial." required>
                    </div>
                    <p class="texto_italic"><a href="cambio_contrasena.php">¿Olvidaste tu contraseña?</a></p> 
                    <button class="btn btn-primary" type="submit" name="iniciar_sesion">Iniciar sesión</button>
                    <p class="texto_italic">¿No tienes una cuenta? <a style="color: #FF4081" href="registro.php">Regístrate</a></p>
                </form>
            </div>
        </div>
        <script>
            document.getElementById('formulario').onsubmit = function(event) {
                event.preventDefault(); // Prevenir la recarga de la página
                var formData = new FormData(this);
                fetch('procesar_login.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.includes('Error:')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al iniciar sesión',
                            text: data,
                            confirmButtonText: 'Intentar de nuevo'
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Inicio de sesión exitoso',
                            text: 'Redirigiendo al inicio.',
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../index.php';
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de comunicación',
                        text: 'No se pudo establecer comunicación con el servidor.'
                    });
                });
            };
        </script>
    </body>
    <footer style="position: fixed; bottom: 0; width:100%; height: 45px;" class="mt-4">
        <a href="https://www.facebook.com/autobuseshalconoficial?mibextid=ZbWKwL" target="_blank" style="text-decoration: none;">
            <img style="margin-right: 0px; margin-left: 20px; margin-top:10px;" src="../assets/images/icon-facebook-480.png" width="30px" alt="">
        </a>
        <a href="https://www.instagram.com/autobuseshalcon_oax?igsh=ZThic29ra2pkcW85" target="_blank" style="text-decoration: none;">
            <img style="margin-top:10px" src="../assets/images/icon-instagram-480.png" width="30px" alt="">
        </a>
    </footer>
</html>
