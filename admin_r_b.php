<?php
session_start();
include 'conection/conection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: auth/login.php");
    exit;
}

if (!isset($_GET['origen']) || !isset($_GET['destino']) || !isset($_GET['fecha'])) {
    echo "Faltan parámetros requeridos.";
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

if ($stmt === false) {
    echo "Error en la preparación de la consulta: " . $conection->error;
    exit;
}

$stmt->bind_param("sss", $origen, $destino, $fecha);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    echo "Error en la ejecución de la consulta: " . $stmt->error;
    exit;
}
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
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            max-width: 900px; /* Ajuste del ancho máximo */
        }

        .bus-image {
            width: 150px;
            height: auto;
            margin-right: 20px;
            margin-top: 5px; /* Ajuste para subir el autobús */
            padding-bottom: 20px; /* Añadir padding debajo de la imagen */
        }

        .info-section {
            background-color: #f8f9fa;
            border-radius: 20px;
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .info-text p {
            margin: 5px 0;
            font-size: 1.1rem;
            color: #333;
        }

        .info-text strong {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #4a69bd;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            text-align: center;
            margin-top: 10px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: #1e3799;
        }

        @media (max-width: 768px) {
            .result-card {
                flex-direction: column;
                padding: 20px;
                align-items: center;
            }

            .bus-image {
                margin-bottom: 20px;
                margin-top: 0; /* Restablecer margen en dispositivos móviles */
                padding-bottom: 0; /* Restablecer padding en dispositivos móviles */
            }

            .info-section {
                width: 100%;
            }
        }

        h3 {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            font-size: 20px;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container mt-4 mb-5">
        <h3 class="text-center">RESULTADOS DE BÚSQUEDA</h3>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="mt-5 row result-card">
                    <img class="bus-image" src="assets/images/autobus.png" alt="Bus">
                    <div class="info-section">
                        <div class="info-text">
                            <p><strong>Hora de salida:</strong> ' . substr($row['hora_salida'], 0, 5) . ' h ' . $row['origen'] . '</p>
                            <p><strong>Hora de llegada:</strong> ' . substr($row['hora_llegada'], 0, 5) . ' h ' . $row['destino'] . '</p>
                        </div>
                        <form action="admin_apartar_boleto.php" method="GET">
                            <input type="hidden" name="id_trayecto" value="' . $row['id_trayecto'] . '">
                            <input type="hidden" name="origen" value="' . $row['origen'] . '">
                            <input type="hidden" name="destino" value="' . $row['destino'] . '">
                            <input type="hidden" name="fecha" value="' . $row['fecha'] . '">
                            <input type="hidden" name="hora" value="' . $row['hora_salida'] . '">
                            <button type="submit" class="btn btn-primary">Administrar</button>
                        </form>
                    </div>
                </div>';
            }
        } else {
            echo '<p>No se encontraron resultados para su búsqueda.</p>';
        }
        ?>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>
