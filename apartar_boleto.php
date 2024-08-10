<?php
session_start();
include 'conection/conection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$id_trayecto = $_POST['id_trayecto'] ?? 'No recibido';

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

$stmt = $conection->prepare("SELECT asiento_reservado FROM reservacion WHERE id_horario = ? AND estado_reserva = 'ocupado' ");
$stmt->bind_param("i", $id_horario);
$stmt->execute();
$result = $stmt->get_result();

$asientos_ocupados = [];
while ($row = $result->fetch_assoc()) {
    $asientos_ocupados[] = $row['asiento_reservado'];
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
            font-family: 'Inter', sans-serif;
        }
        .main-card {
            background-color: #d6e1f7;
            border-radius: 20px;
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
            border-radius: 20px;
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

        }
        .seat.selected {

        }
        .seat.selected .seat-number {
            color: #ffffff;
        }
        .seat.occupied {

        }
        .seat-number {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #000;
            pointer-events: none;
        }
        .price,
        .buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .price {
            width: 200px;
            height: 60px;
            margin-top: 60px;
            background-color: #bce5e5;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 1.2rem;
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
        <style>
    @media (max-width: 768px) {
        .main-card {
            padding: 10px;
            margin-top: 5px;
            
        }
        .bus-img {
            max-width: 100px; /* Ajusta el tamaño de la imagen del autobús */
        }
        .seat-container {
            padding: 20px;
            border-radius: 15px; /* Suaviza los bordes de los contenedores de asientos */
        }
        .seat-map {
            margin: 0 auto; /* Centra el mapa de asientos en la vista */
            
        }
        .row {
            justify-content: space-around; /* Distribuye los asientos uniformemente */
        }
        .seat {
            width: 30px; /* Reduce el tamaño de los asientos */
            height: 30px;
        }
        .seat img {
            width: 30px;
            height: 30px;
        }
        .buttons {
            flex-direction: row; /* Coloca los botones en fila */
            justify-content: space-around; /* Espacia los botones uniformemente */
            margin-top: 20px;
        }
        .buttons button {
            width: 48%; /* Ajusta el ancho de los botones para que se ajusten dentro de la vista */
        }
        .price {
            font-size: 16px; /* Ajusta el tamaño de la fuente del precio */
            width: 100%; /* Asegura que el contenedor del precio use todo el ancho disponible */
            text-align: center; /* Centra el texto del precio */
        }
    }
</style>

</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <h4 class="text-center mt-3">APARTADO DE BOLETOS</h4>
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
                        echo ' <img class="volantito" src="assets/images/volantito.png" alt="">';
                        // Grupo de arriba con 20 asientos
                        for ($fila = 0; $fila < 2; $fila++) {
                            echo '<div class="row">';
                            for ($col = 0; $col < 10; $col++) {
                                $i = $fila * 10 + $col;
                                $estado = in_array($i + 1, $asientos_ocupados) ? 'occupied' : 'available';
                                $src = ($estado == 'occupied') ? 'assets/images/ocupado.png' : 'assets/images/disponible.png';

                                echo '<div class="seat ' . $estado . '" data-index="' . $i . '">';
                                echo '<img src="' . $src . '" alt="Asiento">';
                                echo '<div class="seat-number">' . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        echo '<img class="niños" src="assets/images/niños.png" alt="">';
                        echo '<br>';
                        // Grupo de abajo con 24 asientos
                        for ($fila = 0; $fila < 2; $fila++) {
                            echo '<div class="row">';
                            for ($col = 0; $col < 12; $col++) {
                                $i = $num_asientos_arriba + $fila * 12 + $col;
                                $estado = in_array($i + 1, $asientos_ocupados) ? 'occupied' : 'available';
                                $src = ($estado == 'occupied') ? 'assets/images/ocupado.png' : 'assets/images/disponible.png';

                                echo '<div class="seat ' . $estado . '" data-index="' . $i . '">';
                                echo '<img src="' . $src . '" alt="Asiento">';
                                echo '<div class="seat-number">' . str_pad($i + 1, 2, '0', STR_PAD_LEFT) . '</div>';
                                echo '</div>';
                            }
                            echo '</div>';
                        }
                        ?>
                        <div class="row mx-0 px-0 d-lg-none d-block" style="width:100%;">
                            <div class="row">
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-6">
                                            a
                                        </div>
                                        <div class="col-6">
                                            a
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    
                                </div>
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-6">
                                            a
                                        </div>
                                        <div class="col-6">
                                            a
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-6">
                                            a
                                        </div>
                                        <div class="col-6">
                                            a
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    
                                </div>
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-6">
                                            a
                                        </div>
                                        <div class="col-6">
                                            a
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-6">
                                            a
                                        </div>
                                        <div class="col-6">
                                            a
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    
                                </div>
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-6">
                                            <img src="assets/images/ocupadoC.png" alt="" style="width:100%;">
                                        </div>
                                        <div class="col-6">
                                        <img src="assets/images/ocupadoC.png" alt="" style="width:100%;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-6">
                                            a
                                        </div>
                                        <div class="col-6">
                                            a
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    
                                </div>
                                <div class="col-5">
                                    <div class="row">
                                        <div class="col-6">
                                            a
                                        </div>
                                        <div class="col-6">
                                            a
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row text-center mt-2">
                        <div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center">
                            <img class="opcion" src="assets/images/seleccionado.jpg" alt="Seleccionado" >
                            <p>Seleccionado</p>
                        </div>

                        <div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center">
                            <img class="opcion" src="assets/images/ocupado.png" alt="Ocupado" >
                            <p>Ocupado</p>
                        </div>

                        <div class="col-12 col-md-4 d-flex flex-column align-items-center justify-content-center">
                            <img class="opcion" src="assets/images/disponible.png" alt="Disponible" >
                            <p>Disponible</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="button-container">
                    <div class="buttons">
                        <div id="bookingFormContainer" data-id-trayecto="<?php echo $id_trayecto; ?>">
                            <form id="bookingForm" action="apartar_boleto.php" method="POST">
                                <input type="hidden" name="codigo_apartado" id="codigo_apartado" value="">
                                <input type="hidden" name="num_asiento" id="num_asiento" value="">
                                <input type="hidden" name="estado_reserva" id="estado_reserva" value="ocupado">
                                <input type="hidden" name="id_usuario" id="id_usuario" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>">
                                <input type="hidden" name="id_horario" id="id_horario" value="<?php echo $id_horario; ?>">
                                <input type="hidden" name="id_trayecto" id="id_trayecto" value="<?php echo $id_trayecto; ?>">
                                <input type="hidden" name="origen" id="origen" value="<?php echo $origen; ?>">
                                <input type="hidden" name="destino" id="destino" value="<?php echo $destino; ?>">
                                <input type="hidden" name="fecha" id="fecha" value="<?php echo $fecha; ?>">
                                <input type="hidden" name="hora_salida" id="hora_salida" value="<?php echo $hora_salida; ?>">
                                <input type="hidden" name="monto_pagar" id="monto_pagar" value="100">
                                <button id="btn-apartar" class="btn btn-primary mb-3 btn-lg" type="submit">Apartar</button>
                            </form>
                        </div>
                        <button id="btn-cancelar" class="btn btn-danger btn-lg" onclick="window.location.href='index.php'">Cancelar</button>
                        <div style="border-radius: 15px; color: white;" class="price" id="price">$0.00MX</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>
    <script>
        const seatPrice = 520;
        const seats = document.querySelectorAll('.seat');
        let selectedSeats = [];

        document.querySelector('.seat-map').addEventListener('click', function(event) {
            let seat = event.target;

            if (!seat.classList.contains('seat')) {
                seat = seat.closest('.seat');
            }
            const img = seat.querySelector('img');

            if (seat && img && !seat.classList.contains('occupied')) {
                seat.classList.toggle('selected');
                const isSelected = seat.classList.contains('selected');
                img.src = isSelected ? 'assets/images/seleccionado.jpg' : 'assets/images/disponible.png';
                
                const seatIndex = seat.getAttribute('data-index');
                if (isSelected) {
                    if (!selectedSeats.includes(seatIndex)) {
                        selectedSeats.push(seatIndex);
                    }
                } else {
                    selectedSeats = selectedSeats.filter(index => index !== seatIndex);
                }
                updatePrice();
            }
        });

        document.getElementById('btn-apartar').addEventListener('click', (event) => {
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
                const numAsientos = selectedSeats.map(index => parseInt(index) + 1).join(', ');
                const codigoApartado = 1;
                document.getElementById('num_asiento').value = numAsientos;
                document.getElementById('codigo_apartado').value = numAsientos;
                
                monto_total = updatePrice();

                const formData = new FormData(document.getElementById('bookingForm'));
                
                fetch('confirmacion.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    formData.forEach((value, key) => console.log(key, value));
                    
                    if (data.success) {

                        const fields = {
                            numAsientos: num_asiento.value,
                            codigo_apartado: num_asiento.value,
                            estado_reserva: document.getElementById('estado_reserva').value,
                            id_usuario: document.getElementById('id_usuario').value,
                            id_horario: document.getElementById('id_horario').value,
                            id_trayecto: document.getElementById('id_trayecto').value,
                            origen: document.getElementById('origen').value,
                            destino: document.getElementById('destino').value,
                            fecha: document.getElementById('fecha').value,
                            hora_salida: document.getElementById('hora_salida').value,
                            monto_pagar: monto_total
                        };

                        let form = document.createElement('form');
                            form.method = 'POST';
                            form.action = 'reservar_boleto.php';

                            for (let key in fields) {
                                let input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = key;
                                input.value = fields[key];
                                form.appendChild(input);
                            }

                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        alert('Hubo un problema al apartar los boletos: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                alert('Por favor, selecciona al menos un asiento.');
            }
        });

        document.getElementById('btn-cancelar').addEventListener('click', () => {
            selectedSeats.forEach(index => {
                const seat = document.querySelector(`.seat[data-index="${index}"]`);
                seat.classList.remove('selected');
            });
            selectedSeats = [];
            updatePrice();
        });

        function updatePrice() {
            const seatPrice = 520;
            const totalPrice = selectedSeats.length * seatPrice;
            document.getElementById('price').textContent = `$${totalPrice.toFixed(2)}MX`;
            return totalPrice;
        }

    </script>
</body>
</html>
