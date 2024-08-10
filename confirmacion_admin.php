<?php
session_start();
include 'conection/conection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['num_asiento'], $_POST['estado_reserva'], $_POST['id_horario'])) {
    $num_asiento = $_POST['num_asiento'];
    $estado_reserva = $_POST['estado_reserva'];
    $id_horario = $_POST['id_horario'];

    $asientos = explode(',', $num_asiento);

    foreach ($asientos as $asiento) {
        $asiento = trim($asiento);
        if (!empty($asiento)) {
            if ($estado_reserva === 'disponible') {
                $stmt = $conection->prepare("DELETE FROM reservacion WHERE id_horario = ? AND asiento_reservado = ?");
                $stmt->bind_param("ii", $id_horario, $asiento);
            } else {
                $stmt = $conection->prepare("INSERT INTO reservacion (asiento_reservado, estado_reserva, id_horario) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE estado_reserva = VALUES(estado_reserva)");
                $stmt->bind_param("isi", $asiento, $estado_reserva, $id_horario);
            }
            $stmt->execute();
            $stmt->close();
        }
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request or missing fields']);
}
?>
