<?php
    session_start(); 
    include 'conection/conection.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit;
    }

    $codigo_apartado = $_POST['codigo_apartado'] ?? 'No proporcionado';
    $num_asiento = $_POST['numAsientos'] ?? 'No proporcionado';
    $estado_reserva = $_POST['estado_reserva'] ?? 'No proporcionado';
    $id_usuario = $_POST['id_usuario'] ?? 'No proporcionado';
    $id_horario = $_POST['id_horario'] ?? 'No proporcionado';
    $id_trayecto = $_POST['id_trayecto'] ?? 'No proporcionado';
    $origen = $_POST['origen'] ?? 'No proporcionado';
    $destino = $_POST['destino'] ?? 'No proporcionado';
    $fecha = $_POST['fecha'] ?? 'No proporcionado';
    $hora_salida = $_POST['hora_salida'] ?? 'No proporcionado';
    $monto_pagar = $_POST['monto_pagar'] ?? 'No proporcionado';
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head_meta.php'; ?>
    <style>
        .fondo_apartar {
            text-align: center;
            padding: 30px;
            background-color: #d6e1f7;
            border-radius: 10px;
            border-radius:15px;
        }
        .form_normal {
            background-color: white;
            border-radius:15px;
        }
        .variables {
            text-align: left;
        }
        h4 {
            text-align: center;
            font-family: 'Inter', sans-serif;
            font-size: 24px;
            font-weight: bold;
        }
        .timer {
            color: #B6357B;
            font-weight: bold;
            font-size: 24px;
        }
        .price {
            font-weight: bold;
            color: #2c3e50;
            margin-left:-0px;
        }
        label{
            margin-left:40px;
        }
        .hola{
            margin-left:0px;
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2"></div>
            <div class="col-8">
                <h4 class="mt-3">APARTADO DE BOLETOS</h4>
                <div class="fondo_apartar mt-3">
                    <div class="form_normal">
                        <h4>BOLETO APARTADO CON ÉXITO POR <span id="timer" class="timer">1:00:00</span></h4>
                        <div class="variables">
                            <label>Código de apartado: <?php echo htmlspecialchars($codigo_apartado); ?></label> <br>
                            <label>Num. de asiento: <?php echo htmlspecialchars($num_asiento); ?></label> <br>
                            <label>Origen: <?php echo htmlspecialchars($origen); ?></label> <br>
                            <label>Destino: <?php echo htmlspecialchars($destino); ?></label> <br>
                            <label>Fecha: <?php echo htmlspecialchars($fecha); ?><br>
                            <label class="hola">Hora: <?php echo htmlspecialchars($hora_salida); ?></label> <br>
                            <label class="price mt-3">Monto a pagar: $<?php echo htmlspecialchars($monto_pagar); ?> MX</label>
                        </div>
                        <a href="index.php">
                            <button style="background-color: #B6357B; padding: 6px; width: 160px; border-radius:15px; color: white;" class="mt-3 mb-3 btn" type="button">Ir a inicio</button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-2"></div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
    <script>
       function startTimer(duration, display, idBoleto) {
            var timer = duration, minutes, seconds;
            var interval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(interval);
                    // Envía la solicitud de cancelación
                    cancelReservation(idBoleto);
                }
            }, 1000);
        }

        function cancelReservation(idBoleto) {
            var xhr = new XMLHttpRequest();
            var fechaHoraActual = new Date(); 
            xhr.open("POST", "new_forms/verificar_reservacion.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    Swal.fire({
                        title: 'Resultado de la operación',
                        text: xhr.responseText,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.php';
                        }
                    });
                }
            };
            xhr.send("id_boleto=" + idBoleto);
        }

        window.onload = function () {
            var fiveMinutes = 60 * 1,
                display = document.querySelector('#timer'),
                idBoleto = "<?php echo $codigo_apartado; ?>";
            startTimer(fiveMinutes, display, idBoleto);
        };



    </script>
</body>
</html>
