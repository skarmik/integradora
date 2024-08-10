<?php
session_start();
include 'conection/conection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}

$origen = $_GET['origen'];
$destino = $_GET['destino'];
$fecha = $_GET['fecha'];

$sql = "SELECT t.id_trayecto, t.origen, t.destino, t.fecha, h.hora_salida, h.hora_llegada
        FROM trayecto t
        JOIN horario h ON t.id_trayecto = h.id_trayecto
        WHERE t.origen = ? AND t.destino = ? AND h.fecha_salida = ?";
$stmt = $conection->prepare($sql);
$stmt->bind_param("sss", $origen, $destino, $fecha);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include 'components/head_meta.php'; ?>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .result-card {
            background-color: #C9D8F5;
            border-radius: 25px;
            margin: 20px auto;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .info-section {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 20px;
            padding: 20px;
            margin-left: 150px;
        }
        .info-text {
            text-align: center;
            margin-bottom: 10px;
        }
        .price {
            background-color: #bce5e5;
            padding: 10px 16px;
            border-radius: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
        }
        .btn-primary {
            background-color: #4a69bd;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-align: center;
            width: auto;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background-color: #1e3799;
        }
        .bus-image {
            position: absolute;
            left: 20px;
            top: 50%;
            width: 130px;
            height: auto;
        }
        @media (max-width: 768px) {
            .result-card {
                flex-direction: column;
                padding: 20px;
                align-items: center;
            }
            .bus-image {
                position: static;
                margin-bottom: 20px;
            }
            .info-section {
                margin-left: 0;
                width: 100%;
            }
            .info-text p {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
        }

        h3 {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container mt-4 mb-5">
        <h3 class="text-center">RESULTADOS DE BÚSQUEDA</h3>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="result-card">
                    <img class="bus-image" src="assets/images/autobus.png" alt="Bus">
                    <div class="info-section">
                        <div class="info-text">
                            <p><strong>&bull; Hora de salida:</strong> <span><?= substr($row['hora_salida'], 0, 5) ?> h</span> <?= $row['origen'] ?></p>
                            <p><strong>&bull; Hora de llegada:</strong> <span><?= substr($row['hora_llegada'], 0, 5) ?> h</span> <?= $row['destino'] ?></p>
                        </div>
                        <div class="price">$520 MXN</div>
                        <form action="apartar_boleto.php" method="POST">
                            <input type="hidden" name="id_trayecto" value="<?= $row['id_trayecto'] ?>">
                            <input type="hidden" name="origen" value="<?= $row['origen'] ?>">
                            <input type="hidden" name="destino" value="<?= $row['destino'] ?>">
                            <input type="hidden" name="fecha" value="<?= $row['fecha'] ?>">
                            <input type="hidden" name="hora" value="<?= $row['hora_salida'] ?>">
                            <button type="submit" class="btn btn-primary">Apartar</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No se encontraron resultados para su búsqueda.</p>
        <?php endif; ?>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>
