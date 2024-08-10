<?php
session_start();
include 'conection/conection.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: auth/login.php');
    exit;
}

$id_trayecto = $_GET['id_trayecto'] ?? 'No recibido';

$stmt = $conection->prepare("SELECT origen, destino, fecha FROM trayecto WHERE id_trayecto = ?");
$stmt->bind_param("i", $id_trayecto);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $origen = $row['origen'];
    $destino = $row['destino'];
    $fecha = $row['fecha'];
} else {
    echo "<script>
            alert('Datos del trayecto no encontrados.');
            window.location.href = 'index.php';
          </script>";
    exit;  
}
$stmt->close();

$stmt = $conection->prepare("SELECT hora_salida FROM horario WHERE id_trayecto = ?");
$stmt->bind_param("i", $id_trayecto);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $hora_salida = $row['hora_salida'];
} else {
    echo "<script>
            alert('Hora de salida no encontrada.');
            window.location.href = 'index.php';
          </script>";
    exit;  
}
$stmt->close();

$stmt = $conection->prepare("SELECT id_horario FROM horario WHERE id_trayecto = ?");
$stmt->bind_param("i", $id_trayecto);
$stmt->execute();
$result = $stmt->get_result();
$id_horario = 0;

if ($row = $result->fetch_assoc()) {
    $id_horario = $row['id_horario'];
} else {
    echo "<script>
            alert('No se encontró un horario para el trayecto especificado.');
            window.location.href = 'index.php';
          </script>";
    exit;  
}
$stmt->close();

$stmt = $conection->prepare("SELECT id_autobus FROM horario WHERE id_trayecto = ?");
$stmt->bind_param("i", $id_trayecto);
$stmt->execute();
$result = $stmt->get_result();
$id_autobus = 0;

if ($row = $result->fetch_assoc()) {
    $id_autobus = $row['id_autobus'];
} else {
    echo "<script>
            alert('No se encontró un autobús para el trayecto especificado.');
            window.location.href = 'index.php';
          </script>";
    exit;  
}
$stmt->close();

$numero_autobus = $id_autobus;
$stmt = $conection->prepare("SELECT asientos FROM autobus WHERE id_autobus = ?");
$stmt->bind_param("i", $numero_autobus);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$numero_asientos = $row['asientos'] ?? 0;

$stmt = $conection->prepare("SELECT asiento_reservado, estado_reserva FROM reservacion WHERE id_horario = ?");
$stmt->bind_param("i", $id_horario);
$stmt->execute();
$result = $stmt->get_result();

$asientos_reservados = [];
while ($row = $result->fetch_assoc()) {
    $asientos_reservados[$row['asiento_reservado']] = $row['estado_reserva'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head_meta.php'; ?>
    <style>
        h4 {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
        }
        body {
            font-family: Arial, sans-serif;
        }
        .main-card {
            background-color: #d6e1f7;
            border-radius: 10px;
            margin-top: 20px;
            padding: 20px;
        }
        .bus-img {
            width: 100%;
            max-width: 200px;
        }
        .seat-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
        }
        .seat-map {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 8px 0;
        }
        .row {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .seat {
            position: relative;
            width: 40px;
            height: 40px;
            display: inline-block;
            margin: 5px;
        }
        .seat img {
            width: 40px;
            height: 40px;
            display: block;
            margin-left: -12px;
        }
        .seat.available {
            background-color: #e1e1e1;
        }
        .seat.selected {
            background-color: #7f5a83;
            color: white;
        }
        .seat.selected .seat-number {
            color: #ffffff;
        }
        .seat.occupied {
            background-color: #b0b0b0;
        }
        .seat.paid {
            background-color: #28a745;
            color: white;
        }
        .seat.paid .seat-number {
            color: #ffffff;
        }
        .seat-number {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #000;
            pointer-events: none;
        }
        .buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .btn-primary {
            background-color: #465772;
            color: white;
        }
        .btn-danger {
            background-color: #B6357B;
        }
        .button-container {
            padding: 20px;
            border-radius: 10px;
            background-color: transparent;
        }
        .buttons button {
            border-radius: 25px;
            width: 200px;
            height: 40px;
        }
        .niños {
            width: 40px;
            position: absolute;
            margin-left: 540px;
            margin-top: 5px;
        }
        .volantito {
            width: 30px;
            position: absolute;
            margin-right: 700px;
            margin-top: 215px;
        }
        .opcion {
            width: 30px;
            height: 30px;
            border-radius: 5px;
        }

        @media (max-width: 768px) {
            .bus-img {
                width: 100px;
                margin-left: -270px;
            }
            .buttons button {
                border-radius: 25px;
                width: 380px;
                height: 60px;
            }
            .seat.selected {
                background-color: #7f5a83;
                color: white;
            }
            .seat-container {
                background-color: white;
                padding: 40px;
                border-radius: 10px;
            }
            .seat-map {
                display: flex;
                flex-direction: column;
                align-items: center;
                margin: 8px 0;
            }
            .row {
                display: flex;
                justify-content: center;
                margin-bottom: 10px;
            }
            .seat {
                position: relative;
                width: 30px;
                height: 30px;
                display: inline-block;
                margin: 5px;
            }
            .seat img {
                width: 30px;
                height: 30px;
                display: block;
                margin-left: -12px;
            }
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <h3 class="text-center mt-3">ADMINISTRAR BOLETOS</h3>
    <div class="container main-card">
        <div class="row">
            <div class="col-md-2 text-center">
                <img src="assets/images/autobus.png" alt="Autobús" class="bus-img">
                <p class="mt-4">Autobús</p>
            </div>
            <div class="col-md-8">
                <div class="seat-container">
                    <div class="seat-map">
                        <?php
                        $num_asientos_arriba = 20;
                        $num_asientos_abajo = 24;
                        echo ' <img class="volantito" src="assets\images\volantito.png" alt="">';
                        // Grupo de arriba con 20 asientos
                        for ($fila = 0; $fila < 2; $fila++) {
                            echo '<div class="row">';
                            for ($col = 0; $col < 10; $col++) {
                                $i = $fila * 10 + $col;
                                $estado = isset($asientos_reservados[$i + 1]) ? $asientos_reservados[$i + 1] : 'available';
                                $class = ($estado == 'ocupado') ? 'occupied' : (($estado == 'pagado') ? 'paid' : 'available');
                                $src = ($class == 'occupied') ? 'assets/images/ocupado.png' : (($class == 'paid') ? 'assets/images/paid.jpg' : 'assets/images/disponible.png');

                                echo '<div class="seat ' . $class . '" data-index="' . $i . '">';
                                echo '<img src="' . $src . '" alt="Asiento">';
                                echo '<div class="seat-number">' . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        echo '<img class="niños" src="assets\images\niños.png" alt="">';
                        echo '<br>';
                        // Grupo de abajo con 24 asientos
                        for ($fila = 0; $fila < 2; $fila++) {
                            echo '<div class="row">';
                            for ($col = 0; $col < 12; $col++) {
                                $i = $num_asientos_arriba + $fila * 12 + $col;
                                $estado = isset($asientos_reservados[$i + 1]) ? $asientos_reservados[$i + 1] : 'available';
                                $class = ($estado == 'ocupado') ? 'occupied' : (($estado == 'pagado') ? 'paid' : 'available');
                                $src = ($class == 'occupied') ? 'assets/images/ocupado.png' : (($class == 'paid') ? 'assets/images/paid.jpg' : 'assets/images/disponible.png');

                                echo '<div class="seat ' . $class . '" data-index="' . $i . '">';
                                echo '<img src="' . $src . '" alt="Asiento">';
                                echo '<div class="seat-number">' . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>

                    <div class="row text-center mt-2">
                        <div class="col-12 col-md-3 d-flex flex-column align-items-center justify-content-center">
                            <img class="opcion" src="assets/images/seleccionado.jpg" alt="Seleccionado">
                            <p>Seleccionado</p>
                        </div>

                        <div class="col-12 col-md-3 d-flex flex-column align-items-center justify-content-center">
                            <img class="opcion" src="assets/images/ocupado.png" alt="Ocupado">
                            <p>Ocupado</p>
                        </div>

                        <div class="col-12 col-md-3 d-flex flex-column align-items-center justify-content-center">
                            <img class="opcion" src="assets/images/disponible.png" alt="Disponible">
                            <p>Disponible</p>
                        </div>
                        
                        <div class="col-12 col-md-3 d-flex flex-column align-items-center justify-content-center">
                            <img class="opcion" src="assets/images/paid.jpg" alt="Pagado">
                            <p>Pagado</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="button-container">
                    <div class="buttons">
                        <div id="bookingFormContainer" data-id-trayecto="<?php echo $id_trayecto; ?>">
                            <form id="bookingForm" method="POST">
                                <input type="hidden" name="codigo_apartado" id="codigo_apartado" value="">
                                <input type="hidden" name="num_asiento" id="num_asiento" value="">
                                <input type="hidden" name="estado_reserva" id="estado_reserva" value="pagado">
                                <input type="hidden" name="id_horario" id="id_horario" value="<?php echo $id_horario; ?>">
                                <input type="hidden" name="id_trayecto" id="id_trayecto" value="<?php echo $id_trayecto; ?>">
                                <input type="hidden" name="origen" id="origen" value="<?php echo $origen; ?>">
                                <input type="hidden" name="destino" id="destino" value="<?php echo $destino; ?>">
                                <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha; ?>">
                                <input type="hidden" name="hora_salida" id="hora_salida" value="<?php echo $hora_salida; ?>">
                                <button id="btn-administrar" class="btn btn-primary mb-3 btn-lg" type="submit">Pagado</button>
                            </form>
                            <form id="cancelForm" method="POST">
                                <input type="hidden" name="codigo_apartado" id="cancel_codigo_apartado" value="">
                                <input type="hidden" name="num_asiento" id="cancel_num_asiento" value="">
                                <input type="hidden" name="estado_reserva" id="cancel_estado_reserva" value="disponible">
                                <input type="hidden" name="id_horario" id="cancel_id_horario" value="<?php echo $id_horario; ?>">
                                <input type="hidden" name="id_trayecto" id="cancel_id_trayecto" value="<?php echo $id_trayecto; ?>">
                                <input type="hidden" name="origen" id="cancel_origen" value="<?php echo $origen; ?>">
                                <input type="hidden" name="destino" id="cancel_destino" value="<?php echo $destino; ?>">
                                <input type="hidden" name="fecha" id="cancel_fecha" value="<?php echo $fecha; ?>">
                                <input type="hidden" name="hora_salida" id="cancel_hora_salida" value="<?php echo $hora_salida; ?>">
                                <button id="btn-cancelar" class="btn btn-danger btn-lg" type="submit">Cancelar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
    <script>
        const seats = document.querySelectorAll('.seat');
        let selectedSeats = [];

        document.querySelector('.seat-map').addEventListener('click', function(event) {
            let seat = event.target;

            if (!seat.classList.contains('seat')) {
                seat = seat.closest('.seat');
            }
            const img = seat.querySelector('img');

            if (seat && img) {
                seat.classList.toggle('selected');
                const isSelected = seat.classList.contains('selected');
                img.src = isSelected ? 'assets/images/seleccionado.jpg' : seat.classList.contains('paid') ? 'assets/images/paid.jpg' : seat.classList.contains('occupied') ? 'assets/images/ocupado.png' : 'assets/images/disponible.png';
                
                const seatIndex = seat.getAttribute('data-index');
                if (isSelected) {
                    if (!selectedSeats.includes(seatIndex)) {
                        selectedSeats.push(seatIndex);
                    }
                } else {
                    selectedSeats = selectedSeats.filter(index => index !== seatIndex);
                }
            }
        });

        document.getElementById('btn-administrar').addEventListener('click', (event) => {
            event.preventDefault();

            const container = document.getElementById('bookingFormContainer');
            if (!container) {
                console.error('El contenedor del formulario no se encontró');
                return;
            }
            const idTrayecto = container.getAttribute('data-id-trayecto');
            if (!idTrayecto) {
                console.error('El ID del trayecto no está definido');
                return;
            }

            if (selectedSeats.length > 0) {
                const invalidSelection = selectedSeats.some(index => {
                    const seat = document.querySelector(`.seat[data-index="${index}"]`);
                    return !seat.classList.contains('occupied');
                });

                if (invalidSelection) {
                    alert('Solo puedes marcar como pagados los asientos ocupados.');
                    return;
                }

                const numAsientos = selectedSeats.map(index => parseInt(index) + 1).join(', ');
                document.getElementById('num_asiento').value = numAsientos;
                document.getElementById('codigo_apartado').value = numAsientos;

                const formData = new FormData(document.getElementById('bookingForm'));

                fetch('confirmacion_admin.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar visualmente los asientos pagados
                        selectedSeats.forEach(index => {
                            const seat = document.querySelector(`.seat[data-index="${index}"]`);
                            seat.classList.remove('selected');
                            seat.classList.add('paid');
                            seat.querySelector('img').src = 'assets/images/paid.jpg';
                        });
                        selectedSeats = [];
                    } else {
                        alert('Hubo un problema al marcar los boletos como pagados: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                alert('Por favor, selecciona al menos un asiento.');
            }
        });

        document.getElementById('btn-cancelar').addEventListener('click', (event) => {
            event.preventDefault();

            if (selectedSeats.length > 0) {
                const numAsientos = selectedSeats.map(index => parseInt(index) + 1).join(', ');
                document.getElementById('cancel_num_asiento').value = numAsientos;
                document.getElementById('cancel_codigo_apartado').value = numAsientos;

                const formData = new FormData(document.getElementById('cancelForm'));

                fetch('confirmacion_admin.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar visualmente los asientos disponibles
                        selectedSeats.forEach(index => {
                            const seat = document.querySelector(`.seat[data-index="${index}"]`);
                            seat.classList.remove('selected');
                            seat.classList.remove('occupied');
                            seat.classList.remove('paid');
                            seat.classList.add('available');
                            seat.querySelector('img').src = 'assets/images/disponible.png';
                        });
                        selectedSeats = [];
                    } else {
                        alert('Hubo un problema al cancelar los boletos: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                alert('Por favor, selecciona al menos un asiento.');
            }
        });
    </script>
</body>
</html>
